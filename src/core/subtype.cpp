#include "subtype.h"

#include "error.h"
#include "globals.h"
#include "object.h"

#include <mutex>
#include <string>
#include <unordered_map>
#include <vector>

namespace phpgtk {

namespace {

struct Vfunc {
  GType owner;
  const char *name;  // string literal from the generated registration
  VfuncInstall install;
};

// MINIT-filled, read-only afterwards (like the class registries).
std::vector<Vfunc> &vfuncs() {
  static std::vector<Vfunc> v;
  return v;
}

// Process-wide (GTypes cannot be unregistered): PHP GType -> PHP class name. The strings
// are leaked on purpose: class_data of the GTypeInfo points at them for the process' life.
std::unordered_map<GType, const std::string *> &php_types() {
  static std::unordered_map<GType, const std::string *> map;
  return map;
}
// Guards php_types() (a ZTS thread may register while another wraps).
std::mutex &types_mutex() {
  static std::mutex m;
  return m;
}

// GTypeClassInit: install a thunk for every vfunc the PHP class defines as vfunc_<name>().
void class_init(gpointer klass, gpointer class_data) {
  const auto *name = static_cast<const std::string *>(class_data);
  zend_string *zn = zend_string_init(name->c_str(), name->size(), false);
  zend_class_entry *ce =
      zend_lookup_class_ex(zn, nullptr, ZEND_FETCH_CLASS_NO_AUTOLOAD | ZEND_FETCH_CLASS_SILENT);
  zend_string_release(zn);
  if (ce == nullptr) {
    g_warning("php-gtk4: class_init of %s: PHP class not found in this request", name->c_str());
    return;
  }
  const GType type = G_TYPE_FROM_CLASS(klass);
  for (const Vfunc &vf : vfuncs()) {
    if (g_type_is_a(type, vf.owner) == FALSE) continue;
    const std::string method = std::string("vfunc_") + vf.name;
    const auto *fn = static_cast<zend_function *>(
        zend_hash_str_find_ptr(&ce->function_table, method.c_str(), method.size()));
    if (fn != nullptr && fn->type == ZEND_USER_FUNCTION) vf.install(klass);
  }
}

// GInstanceInitFunc: bind the handle subtype_new() is constructing to its instance so that
// vfuncs running during the rest of g_object_new() and wrap() find it.
void instance_init(GTypeInstance *instance, gpointer) {
  Object *self = GTK4_G(constructing);
  if (self == nullptr) return;
  // Only the instance subtype_new() is building - not one GTK constructs on the side (a
  // template child, a factory item): its class must be the handle's class or an ancestor.
  zend_class_entry *ce = subtype_class_for_gtype(G_TYPE_FROM_INSTANCE(instance));
  if (ce == nullptr || !instanceof_function(self->std.ce, ce)) return;
  object_prebind(self, G_OBJECT(instance));
}

// "App\\MyButton" -> "Php__App__MyButton" (GType names allow [A-Za-z0-9_+-]). The whole
// zend_string, not up to the first NUL: an anonymous class is "class@anonymous\0<file>:<line>$<n>",
// and stopping at the NUL would give every anonymous subclass the same GType name.
std::string gtype_name_for(zend_class_entry *ce) {
  std::string name = "Php__";
  const char *p = ZSTR_VAL(ce->name);
  for (size_t i = 0; i < ZSTR_LEN(ce->name); i++) {
    const char c = p[i];
    if (c == '\\') {
      name += "__";
    } else if (g_ascii_isalnum(c) || c == '_') {
      name += c;
    } else {
      name += '-';
    }
  }
  return name;
}

}  // namespace

// MINIT (generated): remember a vfunc thunk installer for class_init.
void register_vfunc(GType owner, const char *name, VfuncInstall install) {
  vfuncs().push_back({.owner = owner, .name = name, .install = install});
}

// Registered here for a PHP class? (process-wide registry)
bool is_php_type(GType type) {
  const std::lock_guard<std::mutex> lock(types_mutex());
  return php_types().contains(type);
}

// The PHP class' GType, registered on first use with its PHP ancestors (parents first).
GType subtype_for_class(zend_class_entry *ce) {
  if (ce->type != ZEND_USER_CLASS) return 0;
  if (ce->parent == nullptr) return 0;
  GType parent = 0;
  if (ce->parent->type == ZEND_USER_CLASS) {
    parent = subtype_for_class(ce->parent);
  } else {
    parent = gtype_for_class(ce->parent);
  }
  if (parent == 0) {
    if (EG(exception) == nullptr) {
      zend_throw_error(nullptr, "%s: cannot register a GType, %s is not a registered GObject class",
                       ZSTR_VAL(ce->name), ZSTR_VAL(ce->parent->name));
    }
    return 0;
  }
  const std::string name = gtype_name_for(ce);
  const std::lock_guard<std::mutex> lock(types_mutex());
  if (const GType existing = g_type_from_name(name.c_str()); existing != 0) {
    // Registered for this very class? (`App\Foo` and `App__Foo` map to the same GType name.)
    if (auto it = php_types().find(existing); it != php_types().end()) {
      const std::string *owner = it->second;
      if (owner->size() == ZSTR_LEN(ce->name) &&
          zend_binary_strcasecmp(owner->c_str(), owner->size(), ZSTR_VAL(ce->name),
                                 ZSTR_LEN(ce->name)) == 0) {
        return existing;
      }
      zend_throw_error(nullptr, "%s: GType name %s is already used by the PHP class %s",
                       ZSTR_VAL(ce->name), name.c_str(), owner->c_str());
      return 0;
    }
    zend_throw_error(nullptr, "%s: GType name %s is already taken", ZSTR_VAL(ce->name),
                     name.c_str());
    return 0;
  }
  GTypeQuery query;
  g_type_query(parent, &query);
  // NOLINTNEXTLINE(cppcoreguidelines-owning-memory) process-lifetime class_data, see php_types()
  const auto *cname = new std::string(ZSTR_VAL(ce->name), ZSTR_LEN(ce->name));
  const GTypeInfo info = {
      .class_size = static_cast<guint16>(query.class_size),
      .base_init = nullptr,
      .base_finalize = nullptr,
      .class_init = class_init,
      .class_finalize = nullptr,
      .class_data = cname,
      .instance_size = static_cast<guint16>(query.instance_size),
      .n_preallocs = 0,
      .instance_init = instance_init,
      .value_table = nullptr,
  };
  const GType type =
      g_type_register_static(parent, name.c_str(), &info, static_cast<GTypeFlags>(0));
  if (type == 0) {
    zend_throw_error(nullptr, "%s: g_type_register_static(%s) failed", ZSTR_VAL(ce->name),
                     name.c_str());
    return 0;
  }
  php_types()[type] = cname;
  return type;
}

// NOLINTNEXTLINE(cppcoreguidelines-pro-type-vararg) mirrors g_object_new()
GObject *subtype_new(zval *self, const char *first_property, ...) {
  zend_class_entry *ce = Z_OBJCE_P(self);
  const GType type = subtype_for_class(ce);
  if (type == 0) return nullptr;
  Object *handle = object_from_zval(self);
  Object *previous = GTK4_G(constructing);
  GTK4_G(constructing) = handle;
  va_list ap;
  va_start(ap, first_property);
  GObject *obj = g_object_new_valist(type, first_property, ap);
  va_end(ap);
  GTK4_G(constructing) = previous;
  if (obj == nullptr) {
    zend_throw_error(nullptr, "%s: g_object_new() failed", ZSTR_VAL(ce->name));
    return nullptr;
  }
  if (handle->obj != obj) {
    // instance_init always runs for our type; anything else is a bug, not a user error.
    g_critical("php-gtk4: %s: instance_init did not bind the handle", ZSTR_VAL(ce->name));
    object_prebind(handle, obj);
  }
  return obj;
}

// Thunks: the user-defined vfunc_<name>() on the instance's handle, or nullptr (chain native).
zend_function *subtype_vfunc(GObject *obj, const char *method, zval *self) {
  Object *handle = object_handle(obj);
  if (handle == nullptr) return nullptr;
  auto *fn = static_cast<zend_function *>(
      zend_hash_str_find_ptr(&handle->std.ce->function_table, method, strlen(method)));
  if (fn == nullptr || fn->type != ZEND_USER_FUNCTION) return nullptr;
  ZVAL_OBJ_COPY(self, &handle->std);
  return fn;
}

// Class struct of the nearest GTK (non-PHP) ancestor type of the instance.
gpointer subtype_native_class(GObject *obj) {
  GType type = G_OBJECT_TYPE(obj);
  while (is_php_type(type)) type = g_type_parent(type);
  return g_type_class_peek(type);
}

// wrap(): the PHP class behind a PHP GType in this request (no autoload), or nullptr.
zend_class_entry *subtype_class_for_gtype(GType type) {
  const std::string *name = nullptr;
  {
    const std::lock_guard<std::mutex> lock(types_mutex());
    auto it = php_types().find(type);
    if (it == php_types().end()) return nullptr;
    name = it->second;
  }
  zend_string *zn = zend_string_init(name->c_str(), name->size(), false);
  zend_class_entry *ce =
      zend_lookup_class_ex(zn, nullptr, ZEND_FETCH_CLASS_NO_AUTOLOAD | ZEND_FETCH_CLASS_SILENT);
  zend_string_release(zn);
  return ce;
}

}  // namespace phpgtk
