#include "object.h"
#include "callback.h"
#include "marshal.h"
#include <string>
#include <unordered_map>

namespace phpgtk {

static zend_object_handlers handlers;

static std::unordered_map<std::string, zend_class_entry *> &registry() {
  // Plain function-static: destroyed when the module is unloaded, holds no Zend
  // resources (class entries belong to the engine), so that is safe.
  static std::unordered_map<std::string, zend_class_entry *> map;
  return map;
}

// GType per registered class (for GListStore item types etc.).
static std::unordered_map<zend_class_entry *, GType> &gtypes() {
  static std::unordered_map<zend_class_entry *, GType> map;
  return map;
}

// qdata key under which a GObject stores the pointer to its PHP handle.
static GQuark handle_quark() {
  static GQuark q = g_quark_from_static_string("php-gtk4-handle");
  return q;
}

// GWeakNotify: GTK finalized the object behind our back - null the handle instead of dangling.
static void on_finalized(gpointer data, GObject *) {
  static_cast<Object *>(data)->obj = nullptr;
}

// Take ownership: ref_sink, register the back-pointer, arm the weak notify.
void attach(Object *self, GObject *obj) {
  if (self->obj != nullptr || obj == nullptr) {
    g_critical("php-gtk4: attach() misuse");
    return;
  }
  self->obj = G_OBJECT(g_object_ref_sink(obj));
  g_object_set_qdata(self->obj, handle_quark(), self);
  g_object_weak_ref(self->obj, on_finalized, self);
}

// Constructor variant of attach(): adopt the initial ref instead of adding one.
void attach_new(Object *self, GObject *obj) {
  if (self->obj != nullptr || obj == nullptr) {
    g_critical("php-gtk4: attach_new() misuse");
    return;
  }
  self->obj = g_object_is_floating(obj) ? G_OBJECT(g_object_ref_sink(obj)) : obj;
  g_object_set_qdata(self->obj, handle_quark(), self);
  g_object_weak_ref(self->obj, on_finalized, self);
}

// Release ownership (free_obj): drop weak notify and back-pointer, unref.
static void detach(Object *self) {
  if (self->obj == nullptr) return;
  g_object_weak_unref(self->obj, on_finalized, self);
  g_object_set_qdata(self->obj, handle_quark(), nullptr);
  g_object_unref(self->obj);
  self->obj = nullptr;
}

// ---------------------------------------------------------------- handlers

static zend_object *create_object(zend_class_entry *ce) {
  auto *self = static_cast<Object *>(zend_object_alloc(sizeof(Object), ce));
  self->obj = nullptr;
  zend_object_std_init(&self->std, ce);
  object_properties_init(&self->std, ce);
  self->std.handlers = &handlers;
  return &self->std;
}

// free_obj handler: release the C object, then the standard zend_object parts.
static void free_obj(zend_object *o) {
  Object *self = object_from_zend(o);
  detach(self);
  callback_drain();  // the unref may have run destroy notifies
  zend_object_std_dtor(o);
}

// "$obj->default_width" -> GParamSpec "default-width" (nullptr if no such property)
static GParamSpec *find_property(Object *self, zend_string *member) {
  if (self->obj == nullptr) return nullptr;
  std::string name(ZSTR_VAL(member), ZSTR_LEN(member));
  for (auto &c : name) {
    if (c == '_') c = '-';
  }
  return g_object_class_find_property(G_OBJECT_GET_CLASS(self->obj), name.c_str());
}

// read_property handler: GObject properties first, then standard (declared/dynamic) ones.
static zval *read_property(zend_object *o, zend_string *member, int type, void **cache_slot,
                           zval *rv) {
  Object *self = object_from_zend(o);
  GParamSpec *spec = find_property(self, member);
  if (spec == nullptr || (spec->flags & G_PARAM_READABLE) == 0) {
    return zend_std_read_property(o, member, type, cache_slot, rv);
  }
  GValue v = G_VALUE_INIT;
  g_value_init(&v, spec->value_type);
  g_object_get_property(self->obj, spec->name, &v);
  to_php(&v, rv);
  g_value_unset(&v);
  return rv;
}

// write_property handler: GObject properties first, then standard ones.
static zval *write_property(zend_object *o, zend_string *member, zval *value, void **cache_slot) {
  Object *self = object_from_zend(o);
  GParamSpec *spec = find_property(self, member);
  if (spec == nullptr || (spec->flags & G_PARAM_WRITABLE) == 0) {
    return zend_std_write_property(o, member, value, cache_slot);
  }
  GValue v = G_VALUE_INIT;
  if (to_gvalue(value, spec->value_type, &v)) {
    g_object_set_property(self->obj, spec->name, &v);
    g_value_unset(&v);
  }
  return value;
}

// has_property handler: isset()/empty()/property_exists() for GObject properties.
static int has_property(zend_object *o, zend_string *member, int has_set_exists,
                        void **cache_slot) {
  Object *self = object_from_zend(o);
  GParamSpec *spec = find_property(self, member);
  if (spec == nullptr) return zend_std_has_property(o, member, has_set_exists, cache_slot);
  if (has_set_exists == ZEND_PROPERTY_EXISTS) return 1;
  zval rv;
  read_property(o, member, BP_VAR_IS, nullptr, &rv);
  int result = has_set_exists == ZEND_PROPERTY_NOT_EMPTY ? zend_is_true(&rv) : !Z_ISNULL(rv);
  zval_ptr_dtor(&rv);
  return result;
}

// get_property_ptr_ptr handler: no direct slot for GObject properties, so `++`, `.=`, `+=`
// and `&$obj->prop` go through read_property/write_property instead of creating a dynamic
// property that would shadow nothing and silently swallow the write.
static zval *get_property_ptr_ptr(zend_object *o, zend_string *member, int type,
                                  void **cache_slot) {
  Object *self = object_from_zend(o);
  if (find_property(self, member) != nullptr) return nullptr;
  return zend_std_get_property_ptr_ptr(o, member, type, cache_slot);
}

// unset_property handler: GObject properties cannot be unset (they always exist on the C object).
static void unset_property(zend_object *o, zend_string *member, void **cache_slot) {
  Object *self = object_from_zend(o);
  if (find_property(self, member) != nullptr) {
    zend_throw_error(nullptr, "Cannot unset GObject property %s::$%s", ZSTR_VAL(o->ce->name),
                     ZSTR_VAL(member));
    return;
  }
  zend_std_unset_property(o, member, cache_slot);
}

// var_dump()/print_r(): every readable GObject property that we can convert.
static HashTable *get_debug_info(zend_object *o, int *is_temp) {
  Object *self = object_from_zend(o);
  HashTable *ht = zend_new_array(8);
  *is_temp = 1;
  if (self->obj == nullptr) {
    zval z;
    ZVAL_STRING(&z, "(finalized)");
    zend_hash_str_update(ht, "gobject", sizeof("gobject") - 1, &z);
    return ht;
  }
  guint n = 0;
  GParamSpec **specs = g_object_class_list_properties(G_OBJECT_GET_CLASS(self->obj), &n);
  for (guint i = 0; i < n; i++) {
    if ((specs[i]->flags & G_PARAM_READABLE) == 0) continue;
    if (!to_php_supported(specs[i]->value_type)) continue;
    GValue v = G_VALUE_INIT;
    g_value_init(&v, specs[i]->value_type);
    g_object_get_property(self->obj, specs[i]->name, &v);
    zval z;
    to_php(&v, &z);
    g_value_unset(&v);
    zend_hash_str_update(ht, specs[i]->name, strlen(specs[i]->name), &z);
  }
  g_free(static_cast<void *>(specs));
  return ht;
}

// compare handler: handles are only equal to themselves (identity == same C object).
static int compare_objects(zval *a, zval *b) {
  ZEND_COMPARE_OBJECTS_FALLBACK(a, b);
  return Z_OBJ_P(a) == Z_OBJ_P(b) ? 0 : 1;
}

// MINIT: build the shared handler table for all GObject classes.
void object_handlers_init() {
  memcpy(&handlers, &std_object_handlers, sizeof(zend_object_handlers));
  handlers.offset = XtOffsetOf(Object, std);
  handlers.free_obj = free_obj;
  handlers.clone_obj = nullptr;  // "Trying to clone an uncloneable object of class ..."
  handlers.read_property = read_property;
  handlers.write_property = write_property;
  handlers.has_property = has_property;
  handlers.get_property_ptr_ptr = get_property_ptr_ptr;
  handlers.unset_property = unset_property;
  handlers.get_debug_info = get_debug_info;
  handlers.compare = compare_objects;
}

// ---------------------------------------------------------------- registry

// The root class entry, cached for unwrap() (registered first, before any other GObject class).
static zend_class_entry *ce_root = nullptr;

// MINIT: install create_object on a GObject class and record its GType (name and value).
void register_class(const char *gtype_name, zend_class_entry *ce, GType type) {
  ce->create_object = create_object;
  registry()[gtype_name] = ce;
  gtypes()[ce] = type;
  if (type == G_TYPE_OBJECT) ce_root = ce;
}

// GType of a registered class (walks up to a registered parent for subclasses).
GType gtype_for_class(zend_class_entry *ce) {
  for (; ce != nullptr; ce = ce->parent) {
    auto it = gtypes().find(ce);
    if (it != gtypes().end()) return it->second;
  }
  return 0;
}

// Registry lookup by GType name.
zend_class_entry *class_for_gtype_name(const char *gtype_name) {
  auto it = registry().find(gtype_name);
  return it == registry().end() ? nullptr : it->second;
}

// ---------------------------------------------------------------- conversions

void wrap(GObject *obj, zval *rv) {
  if (obj == nullptr) {
    ZVAL_NULL(rv);
    return;
  }
  if (auto *existing = static_cast<Object *>(g_object_get_qdata(obj, handle_quark()))) {
    ZVAL_OBJ_COPY(rv, &existing->std);
    return;
  }
  for (GType t = G_OBJECT_TYPE(obj); t != 0; t = g_type_parent(t)) {
    if (zend_class_entry *ce = class_for_gtype_name(g_type_name(t))) {
      object_init_ex(rv, ce);
      attach(object_from_zval(rv), obj);
      return;
    }
  }
  ZVAL_NULL(rv);
  zend_throw_error(nullptr, "no PHP class registered for %s", G_OBJECT_TYPE_NAME(obj));
}

// PHP -> C: the live GObject behind a handle that is-a `expected`, else TypeError + nullptr.
GObject *unwrap(zval *zv, GType expected) {
  if (Z_TYPE_P(zv) != IS_OBJECT || !instanceof_function(Z_OBJCE_P(zv), ce_root)) {
    zend_type_error("expected a GObject instance, %s given", zend_zval_value_name(zv));
    return nullptr;
  }
  Object *self = object_from_zval(zv);
  if (self->obj == nullptr) {
    zend_type_error("expected a live GObject instance (this handle was finalized)");
    return nullptr;
  }
  if (g_type_is_a(G_OBJECT_TYPE(self->obj), expected) == FALSE) {
    zend_type_error("expected %s, got %s", g_type_name(expected), G_OBJECT_TYPE_NAME(self->obj));
    return nullptr;
  }
  return self->obj;
}

// $this of a method as the C object, validated for liveness and GType.
GObject *self_object(zend_execute_data *execute_data, GType expected, const char *method) {
  Object *self = object_from_zend(Z_OBJ_P(ZEND_THIS));
  if (self->obj == nullptr) {
    zend_throw_error(nullptr, "%s() on a dead GObject", method);
    return nullptr;
  }
  if (g_type_is_a(G_OBJECT_TYPE(self->obj), expected) == FALSE) {
    zend_type_error("%s(): expected %s, got %s", method, g_type_name(expected),
                    G_OBJECT_TYPE_NAME(self->obj));
    return nullptr;
  }
  return self->obj;
}

}  // namespace phpgtk
