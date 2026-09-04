#include "subtype.h"

#include "error.h"
#include "globals.h"
#include "diagnostics.h"
#include "marshal.h"
#include "object.h"

#include <algorithm>
#include <atomic>
#include <cstring>
#include <deque>
#include <mutex>
#include <string>
#include <unordered_map>
#include <utility>
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
// Interface slots, keyed by the interface GType (MINIT-filled).
std::vector<Vfunc> &iface_vfuncs() {
  static std::vector<Vfunc> v;
  return v;
}

// Stable storage for the interface GType an iface_init receives as iface_data (process-wide,
// like the GTypes themselves; a deque never moves its elements).
std::deque<GType> &iface_keys() {
  static std::deque<GType> keys;
  return keys;
}

// GInterfaceInitFunc: the PHP class implements every slot (PHP made it implement the
// interface's methods), so install all of them.
void iface_init(gpointer iface, gpointer iface_data) {
  const GType type = *static_cast<const GType *>(iface_data);
  for (const Vfunc &vf : iface_vfuncs()) {
    if (vf.owner == type) vf.install(iface);
  }
}

// Whether `it` names `other` among its prerequisites - GtkSelectionModel requires GListModel,
// GActionMap requires GActionGroup. GLib refuses to add an interface to a type that does not
// implement its prerequisites yet, so the one that is required has to go on first.
bool interface_requires(GType it, GType other) {
  guint n = 0;
  GType *prerequisites = g_type_interface_prerequisites(it, &n);
  bool found = false;
  for (guint i = 0; i < n && !found; i++) found = g_type_is_a(prerequisites[i], other) == TRUE;
  g_free(prerequisites);
  return found;
}

// The interfaces a PHP class declares that are registered GTK interfaces with slots, ordered so
// that every prerequisite comes before what requires it. `ce->interfaces` is in no particular
// order, and adding GtkSelectionModel to a type that does not implement GListModel yet is a
// GLib CRITICAL and a half-built GType. Prerequisites are transitive and cannot form a cycle, so
// "how many of the others does this one require" is already the order to add them in.
std::vector<GType> php_interfaces(zend_class_entry *ce) {
  std::vector<GType> out;
  for (uint32_t i = 0; i < ce->num_interfaces; i++) {
    const GType it = gtype_for_class(ce->interfaces[i]);
    if (it == 0 || G_TYPE_IS_INTERFACE(it) == FALSE) continue;
    // an interface PHP can implement: one with slots php-gtk4 thunks, or with properties
    // (GtkOrientable has only `orientation`) that override_interface_properties() backs
    if (std::ranges::any_of(iface_vfuncs(), [it](const Vfunc &vf) { return vf.owner == it; })) {
      out.push_back(it);
      continue;
    }
    gpointer iface = g_type_default_interface_ref(it);
    guint n_props = 0;
    g_free(static_cast<gpointer>(g_object_interface_list_properties(iface, &n_props)));
    g_type_default_interface_unref(iface);
    if (n_props > 0) out.push_back(it);
  }
  std::vector<std::pair<long, GType>> ranked;  // how many of the others it requires, then itself
  ranked.reserve(out.size());
  for (const GType it : out) {
    ranked.emplace_back(std::count_if(out.begin(), out.end(),
                                      [it](GType other) { return interface_requires(it, other); }),
                        it);
  }
  std::ranges::stable_sort(ranked, [](const auto &a, const auto &b) { return a.first < b.first; });
  out.clear();
  for (const auto &[required, it] : ranked) out.push_back(it);
  return out;
}

// Process-wide (GTypes cannot be unregistered): PHP GType -> PHP class name. The strings
// are leaked on purpose: class_data of the GTypeInfo points at them for the process' life.
// Copy-on-write: readers (wrap() walks the parent chain of every wrapped object, on hot
// paths) load the current snapshot pointer and never lock; writers (rare: first `new` of a
// class) serialise on the mutex and publish a new map. Every snapshot ever published stays
// owned by a process-wide deque (stable addresses, a few small maps) so a reader mid-walk
// never sees a freed one - simpler than hazard pointers and nothing for LSan to report.
using TypeMap = std::unordered_map<GType, const std::string *>;
// Every snapshot ever published, in order (process-wide static; deque elements never move).
std::deque<TypeMap> &php_type_snapshots() {
  static std::deque<TypeMap> maps(1);  // the empty initial snapshot
  return maps;
}
// The pointer readers load (process-wide static, atomic).
std::atomic<const TypeMap *> &php_types_snapshot() {
  static std::atomic<const TypeMap *> snapshot{&php_type_snapshots().front()};
  return snapshot;
}
// A reader's view of the registry: no lock; valid for the process' life.
const TypeMap *php_types() {
  return php_types_snapshot().load();
}
// Serialises registrations (register-check-publish must be atomic between ZTS threads).
std::mutex &types_mutex() {
  static std::mutex m;
  return m;
}
// Publish a copy with `type` added (under types_mutex()); the previous map stays alive.
void php_types_add(GType type, const std::string *name) {
  TypeMap &next = php_type_snapshots().emplace_back(*php_types());
  next[type] = name;
  php_types_snapshot().store(&next);
}

// A vfunc_<name>() the PHP class declares that no slot answers - a typo, a slot GTK does not
// route through the class struct for this widget, or a member the generator skipped (Gtk.Snapshot
// is unbound, so vfunc_snapshot() is not a thing) - is otherwise ignored without a word: nothing
// is installed and the method simply never runs. Say so once, when the class' GType is created.
void warn_about_unbound_vfuncs(zend_class_entry *ce, GType type) {
  zend_string *name = nullptr;
  void *ptr = nullptr;
  ZEND_HASH_MAP_FOREACH_STR_KEY_PTR(&ce->function_table, name, ptr) {
    auto *fn = static_cast<zend_function *>(ptr);
    if (name == nullptr || fn->type != ZEND_USER_FUNCTION) continue;
    // Only where it is declared: every PHP class in the chain gets its own GType and its own
    // class_init, so an inherited method would otherwise be reported once per subclass.
    if (fn->common.scope != ce) continue;
    if (!zend_string_starts_with_literal_ci(name, "vfunc_")) continue;
    const char *slot = ZSTR_VAL(name) + strlen("vfunc_");
    bool bound = false;
    for (const Vfunc &vf : vfuncs()) {
      if (g_type_is_a(type, vf.owner) != FALSE && strcmp(vf.name, slot) == 0) {
        bound = true;
        break;
      }
    }
    if (!bound) {
      diagnostic(
          "php-gtk4: %s::%s() overrides no slot php-gtk4 binds on %s - it will never be "
          "called",
          ZSTR_VAL(ce->name), ZSTR_VAL(name), g_type_name(type));
    }
  }
  ZEND_HASH_FOREACH_END();
}

// The PHP method a property of an interface the class implements maps to: GAction's
// `state-type` is answered by get_state_type() and GtkOrientable's `orientation` set by
// set_orientation() - the interface declares the accessor next to the property.
std::string property_accessor(const char *prefix, const char *property) {
  std::string method = prefix;
  for (const char *c = property; *c != '\0'; c++) method += *c == '-' ? '_' : *c;
  return method;
}

// The accessor to call for an interface property, with the handle as `self` (owned): nullptr
// without a handle (mid-construction, after shutdown - the property keeps its default, like a
// vfunc chains to nothing) or, with a diagnostic, when the class has no such method.
zend_function *property_accessor_of(GObject *obj, const std::string &method, GParamSpec *pspec,
                                    zval *self) {
  Object *handle = object_handle(obj);
  if (handle == nullptr) return nullptr;
  auto *fn = static_cast<zend_function *>(
      zend_hash_str_find_ptr(&handle->std.ce->function_table, method.c_str(), method.size()));
  if (fn == nullptr) {
    diagnostic("php-gtk4: %s has no %s() to back the property '%s' of %s",
               ZSTR_VAL(handle->std.ce->name), method.c_str(), pspec->name,
               g_type_name(pspec->owner_type));
    return nullptr;
  }
  ZVAL_OBJ_COPY(self, &handle->std);
  return fn;
}

// GObjectClass.get_property for the properties of the interfaces a PHP class implements: what
// the PHP getter of the same name answers, converted like a property write (core/marshal).
void iface_get_property(GObject *obj, guint /*id*/, GValue *value, GParamSpec *pspec) {
  const std::string method = property_accessor("get_", pspec->name);
  zval zself;
  zend_function *fn =
      EG(exception) == nullptr ? property_accessor_of(obj, method, pspec, &zself) : nullptr;
  if (fn == nullptr) return;
  const std::string origin = std::string(ZSTR_VAL(Z_OBJCE(zself)->name)) + "::" + method;
  zval ret;
  ZVAL_UNDEF(&ret);
  zend_call_known_instance_method(fn, Z_OBJ(zself), &ret, 0, nullptr);
  if (EG(exception) == nullptr && !Z_ISUNDEF(ret)) {
    GValue converted = G_VALUE_INIT;
    if (to_gvalue(&ret, G_VALUE_TYPE(value), &converted)) {
      g_value_copy(&converted, value);
      g_value_unset(&converted);
    }
  }
  zval_ptr_dtor(&ret);
  zval_ptr_dtor(&zself);
  report_pending_exception(origin.c_str());
}

// GObjectClass.set_property for the same properties: the PHP setter of the same name gets the
// value as PHP data (core/marshal).
void iface_set_property(GObject *obj, guint /*id*/, const GValue *value, GParamSpec *pspec) {
  const std::string method = property_accessor("set_", pspec->name);
  zval zself;
  zend_function *fn =
      EG(exception) == nullptr ? property_accessor_of(obj, method, pspec, &zself) : nullptr;
  if (fn == nullptr) return;
  const std::string origin = std::string(ZSTR_VAL(Z_OBJCE(zself)->name)) + "::" + method;
  zval arg;
  to_php(value, &arg);
  zval ret;
  ZVAL_UNDEF(&ret);
  if (EG(exception) == nullptr) zend_call_known_instance_method(fn, Z_OBJ(zself), &ret, 1, &arg);
  zval_ptr_dtor(&arg);
  zval_ptr_dtor(&ret);
  zval_ptr_dtor(&zself);
  report_pending_exception(origin.c_str());
}

// class_init: the properties of every interface the PHP class adds (GAction's name/state/...,
// GtkOrientable's orientation). GObject requires an implementation to override each of them -
// it warns about every one it does not find - and routes them to the class' own
// get_property/set_property, which here are the PHP accessors of the same name.
void override_interface_properties(GObjectClass *oc, GType type) {
  // installed before the first override (GObject asserts that), and inert without one: a
  // parent's property keeps dispatching to the parent's class
  oc->get_property = iface_get_property;
  oc->set_property = iface_set_property;
  guint n_ifaces = 0;
  GType *ifaces = g_type_interfaces(type, &n_ifaces);
  guint next_id = 1;
  for (guint i = 0; i < n_ifaces; i++) {
    if (g_type_is_a(g_type_parent(type), ifaces[i]) == TRUE) continue;  // the parent's: inherited
    gpointer iface = g_type_default_interface_ref(ifaces[i]);
    guint n_props = 0;
    GParamSpec **props = g_object_interface_list_properties(iface, &n_props);
    for (guint p = 0; p < n_props; p++)
      g_object_class_override_property(oc, next_id++, props[p]->name);
    g_free(static_cast<gpointer>(props));
    g_type_default_interface_unref(iface);
  }
  g_free(ifaces);
}

// GTypeClassInit: install a thunk for every vfunc the PHP class defines as vfunc_<name>().
void class_init(gpointer klass, gpointer class_data) {
  const auto *name = static_cast<const std::string *>(class_data);
  zend_string *zn = zend_string_init(name->c_str(), name->size(), false);
  zend_class_entry *ce =
      zend_lookup_class_ex(zn, nullptr, ZEND_FETCH_CLASS_NO_AUTOLOAD | ZEND_FETCH_CLASS_SILENT);
  zend_string_release(zn);
  if (ce == nullptr) {
    diagnostic("php-gtk4: class_init of %s: PHP class not found in this request", name->c_str());
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
  override_interface_properties(G_OBJECT_CLASS(klass), type);
  warn_about_unbound_vfuncs(ce, type);
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

// MINIT (generated): remember an interface slot installer for iface_init.
void register_iface_vfunc(GType iface, const char *name, VfuncInstall install) {
  iface_vfuncs().push_back({.owner = iface, .name = name, .install = install});
}

// Registered here for a PHP class? (process-wide registry)
bool is_php_type(GType type) {
  return php_types()->contains(type);
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
    if (auto it = php_types()->find(existing); it != php_types()->end()) {
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
  // process-lifetime class_data, see php_types()
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
  // `implements GListModel` on the PHP class: the GType implements the GTK interface too,
  // each slot a thunk into the PHP method of the same name (the parent's own interfaces are
  // inherited by GType and need nothing).
  for (const GType it : php_interfaces(ce)) {
    if (g_type_is_a(parent, it) == TRUE) continue;
    iface_keys().push_back(it);
    const GInterfaceInfo iinfo = {
        .interface_init = iface_init,
        .interface_finalize = nullptr,
        .interface_data = &iface_keys().back(),
    };
    g_type_add_interface_static(type, it, &iinfo);
  }
  php_types_add(type, cname);
  return type;
}

// mirrors g_object_new()
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
    diagnostic("php-gtk4: %s: instance_init did not bind the handle", ZSTR_VAL(ce->name));
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

// qdata key of the borrowed value slot `slot` keeps (interned once per slot name).
static GQuark keep_quark(const char *slot) {
  return g_quark_from_string((std::string("php-gtk4-keep-") + slot).c_str());
}

// Thunks returning a borrowed `const char *`: the instance's copy of PHP's answer.
const char *subtype_keep_string(GObject *obj, const char *slot, const char *value) {
  const GQuark q = keep_quark(slot);
  auto *kept = static_cast<char *>(g_object_get_qdata(obj, q));
  if (kept != nullptr && std::strcmp(kept, value) == 0) return kept;
  kept = g_strdup(value);
  g_object_set_qdata_full(obj, q, kept, g_free);
  return kept;
}

// Thunks returning a borrowed `const GVariantType *`: the instance's copy of PHP's answer.
const GVariantType *subtype_keep_variant_type(GObject *obj, const char *origin, const char *slot,
                                              const char *value) {
  if (g_variant_type_string_is_valid(value) == FALSE) {
    zend_type_error("%s(): Return value must be a valid GVariant type string, \"%s\" returned",
                    origin, value);
    return nullptr;
  }
  const GQuark q = keep_quark(slot);
  auto *kept = static_cast<GVariantType *>(g_object_get_qdata(obj, q));
  if (kept != nullptr && g_variant_type_equal(kept, value) == TRUE) return kept;
  kept = g_variant_type_new(value);
  g_object_set_qdata_full(obj, q, kept, reinterpret_cast<GDestroyNotify>(g_variant_type_free));
  return kept;
}

// wrap(): the PHP class behind a PHP GType in this request (no autoload), or nullptr.
zend_class_entry *subtype_class_for_gtype(GType type) {
  const TypeMap *types = php_types();
  auto it = types->find(type);
  if (it == types->end()) return nullptr;
  const std::string *name = it->second;
  zend_string *zn = zend_string_init(name->c_str(), name->size(), false);
  zend_class_entry *ce =
      zend_lookup_class_ex(zn, nullptr, ZEND_FETCH_CLASS_NO_AUTOLOAD | ZEND_FETCH_CLASS_SILENT);
  zend_string_release(zn);
  return ce;
}

}  // namespace phpgtk
