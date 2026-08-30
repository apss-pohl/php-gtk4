#include "object.h"
#include "callback.h"
#include "error.h"
#include "globals.h"
#include "marshal.h"
#include "subtype.h"
#include <array>
#include <string>
#include <string_view>
#include <unordered_map>

namespace phpgtk {

namespace {
zend_object_handlers handlers;

// GType name -> class (MINIT-filled). Plain function-static: destroyed when the module is
// unloaded, holds no Zend resources (class entries belong to the engine), so that is safe.
std::unordered_map<std::string, zend_class_entry *> &registry() {
  static std::unordered_map<std::string, zend_class_entry *> map;
  return map;
}

// GType per registered class (for GListStore item types etc.).
std::unordered_map<zend_class_entry *, GType> &gtypes() {
  static std::unordered_map<zend_class_entry *, GType> map;
  return map;
}
// GType -> class (the inverse of gtypes(), for parameter parsing by *_TYPE_* macro).
std::unordered_map<GType, zend_class_entry *> &classes() {
  static std::unordered_map<GType, zend_class_entry *> map;
  return map;
}

// qdata key under which a GObject stores the pointer to its PHP handle.
GQuark handle_quark() {
  static GQuark q = g_quark_from_static_string("php-gtk4-handle");
  return q;
}
}  // namespace

// The handle registered on a GObject (qdata), nullptr if none.
Object *object_handle(GObject *obj) {
  return static_cast<Object *>(g_object_get_qdata(obj, handle_quark()));
}

// subtype.cpp instance_init: bind a handle under construction (no reference yet).
void object_prebind(Object *self, GObject *obj) {
  if (self->obj != nullptr) return;  // a deeper PHP level's instance_init already did it
  self->obj = obj;
  g_object_set_qdata(obj, handle_quark(), self);
}

namespace {

// True while `self` is only pre-bound to this very object (subtype construction: obj set
// by instance_init, no reference yet). A second attach of the same object is nothing else.
bool prebound(const Object *self, const GObject *obj) {
  return self->obj == obj;
}

// The GObject takes a reference on the zend_object: PHP-side state survives while GTK
// holds the C object.
void hold(Object *self) {
  if (self->held || GTK4_G(shutting_down)) return;
  self->held = true;
  GTK4_G(held).insert(self);
  GC_ADDREF(&self->std);
}

// Drop that reference. Runs inside the g_object_unref() that made ours the last ref; when
// it was also the last PHP reference the handle is freed right here (free_obj -> detach ->
// remove_toggle_ref -> finalize). GLib does not touch the object after calling the
// toggle notify, so the nested finalize is fine - it is how every toggle-ref binding
// (PyGObject, gjs) releases wrappers, and it keeps release deterministic ("the store
// dropped the item, the payload is gone").
// Releasing a handle may run a PHP __destruct. Inside a main-loop dispatch (no PHP frame
// below us: GTK finalizing widgets in a frame, a store dropping items) a Throwable from it
// would stay pending inside GLib, so it goes through the exception boundary like any other
// callback's. With a PHP frame below (a method that made GTK let go) it propagates by itself.
void settle_destructor_exception() {
  if (EG(exception) != nullptr && g_main_depth() > 0) report_pending_exception("__destruct");
}

// Drop the GObject's reference on the handle (see hold()); frees it if PHP had let go too.
void release_hold(Object *self) {
  if (!self->held) return;
  self->held = false;
  GTK4_G(held).erase(self);
  OBJ_RELEASE(&self->std);
  settle_destructor_exception();
}
}  // namespace

// RSHUTDOWN: drop every hold (freeing handles only GTK still referenced) and refuse new ones.
void object_release_holds() {
  GTK4_G(shutting_down) = true;
  while (!GTK4_G(held).empty()) release_hold(*GTK4_G(held).begin());
}

// RINIT: holding is allowed again for the new request.
void object_request_init() {
  GTK4_G(shutting_down) = false;
}

namespace {

// GToggleNotify: our reference became the last one (release the hold) or stopped being
// it (take it).
void on_toggle(gpointer data, GObject *, gboolean is_last_ref) {
  auto *self = static_cast<Object *>(data);
  if (is_last_ref == TRUE) {
    release_hold(self);
  } else {
    hold(self);
  }
}

// GWeakNotify, run from g_object_real_dispose(): GTK disposed the object under a live handle
// (gtk_window_destroy(), a GtkApplication closing its windows). The C object stays allocated
// while PHP references it, but it is gutted - method calls on it are refused from here on
// (self_object()/unwrap() throw), the same "dead $this" rule as a finalized one. The weak
// reference is removed in detach() before our own last unref, so a normal finalization
// (dispose from *our* unref) never reaches this.
void on_dispose(gpointer data, GObject *) {
  static_cast<Object *>(data)->disposed = true;
}

// self->obj holds one plain reference of ours: convert it into the toggle reference,
// register the back-pointer and take the hold if GTK already holds the object.
void arm(Object *self) {
  g_object_set_qdata(self->obj, handle_quark(), self);
  g_object_weak_ref(self->obj, on_dispose, self);
  g_object_add_toggle_ref(self->obj, on_toggle, self);
  g_object_unref(self->obj);  // 2 -> 1 notifies is_last_ref, a no-op while nothing is held
  if (g_atomic_int_get(&self->obj->ref_count) > 1) hold(self);
}
}  // namespace

// Take ownership: ref_sink, then the toggle-ref dance.
void attach(Object *self, GObject *obj) {
  if ((self->obj != nullptr && !prebound(self, obj)) || obj == nullptr) {
    g_critical("php-gtk4: attach() misuse");
    return;
  }
  self->obj = G_OBJECT(g_object_ref_sink(obj));
  arm(self);
}

// Constructor variant of attach(): adopt the initial ref instead of adding one.
// The ownership rule (docs/PLAN.md "Ownership", gen/README.md): floating -> sink, plain
// GObject -> adopt, GtkRoot (windows) -> GTK's toplevel list owns the initial reference,
// so those must use attach(). Enforced here rather than trusted: a root is ref'd like
// attach() would, with a g_critical so the misuse shows up in tests.
void attach_new(Object *self, GObject *obj) {
  if ((self->obj != nullptr && !prebound(self, obj)) || obj == nullptr) {
    g_critical("php-gtk4: attach_new() misuse");
    return;
  }
  if (GTK_IS_ROOT(obj)) {
    g_critical(
        "php-gtk4: attach_new() on a %s: GTK owns a toplevel's initial reference, use "
        "attach()",
        G_OBJECT_TYPE_NAME(obj));
    attach(self, obj);
    return;
  }
  self->obj = g_object_is_floating(obj) ? G_OBJECT(g_object_ref_sink(obj)) : obj;
  arm(self);
}

namespace {

// Release ownership (free_obj): drop the back-pointer and the toggle reference. A hold
// cannot be set here (it owns a reference, so the refcount was not zero) except when the
// object store frees everything at shutdown regardless of refcount.
void detach(Object *self) {
  if (self->obj == nullptr) return;
  GObject *obj = self->obj;
  self->obj = nullptr;
  if (self->held) {
    self->held = false;
    GTK4_G(held).erase(self);
  }
  g_object_set_qdata(obj, handle_quark(), nullptr);
  if (!self->disposed) g_object_weak_unref(obj, on_dispose, self);  // dispose already consumed it
  g_object_remove_toggle_ref(obj, on_toggle, self);
}

// ---------------------------------------------------------------- handlers

// create_object handler: the Object struct with an unattached obj; every GObject class shares it.
zend_object *create_object(zend_class_entry *ce) {
  auto *self = static_cast<Object *>(zend_object_alloc(sizeof(Object), ce));
  self->obj = nullptr;
  self->held = false;
  self->disposed = false;
  zend_object_std_init(&self->std, ce);
  object_properties_init(&self->std, ce);
  self->std.handlers = &handlers;
  return &self->std;
}

// free_obj handler: release the C object, then the standard zend_object parts.
void free_obj(zend_object *o) {
  Object *self = object_from_zend(o);
  detach(self);
  callback_drain();  // the unref may have run destroy notifies
  zend_object_std_dtor(o);
  settle_destructor_exception();  // a __destruct of something the callables / properties held
}

// "$obj->default_width" -> GParamSpec "default-width" (nullptr if no such property)
GParamSpec *find_property(Object *self, zend_string *member) {
  if (self->obj == nullptr) return nullptr;
  // On the $obj->prop path for every access: translate into a stack buffer, no heap.
  std::array<char, 96> buf{};
  const size_t len = ZSTR_LEN(member);
  if (len >= buf.size()) return nullptr;  // no GObject property name is that long
  size_t i = 0;
  for (const char c : std::string_view(ZSTR_VAL(member), len)) buf.at(i++) = c == '_' ? '-' : c;
  return g_object_class_find_property(G_OBJECT_GET_CLASS(self->obj), buf.data());
}

// read_property handler: GObject properties first, then standard (declared/dynamic) ones.
zval *read_property(zend_object *o, zend_string *member, int type, void **cache_slot, zval *rv) {
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
zval *write_property(zend_object *o, zend_string *member, zval *value, void **cache_slot) {
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
int has_property(zend_object *o, zend_string *member, int has_set_exists, void **cache_slot) {
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
zval *get_property_ptr_ptr(zend_object *o, zend_string *member, int type, void **cache_slot) {
  Object *self = object_from_zend(o);
  if (find_property(self, member) != nullptr) return nullptr;
  return zend_std_get_property_ptr_ptr(o, member, type, cache_slot);
}

// unset_property handler: GObject properties cannot be unset (they always exist on the C object).
void unset_property(zend_object *o, zend_string *member, void **cache_slot) {
  Object *self = object_from_zend(o);
  if (find_property(self, member) != nullptr) {
    zend_throw_error(nullptr, "Cannot unset GObject property %s::$%s", ZSTR_VAL(o->ce->name),
                     ZSTR_VAL(member));
    return;
  }
  zend_std_unset_property(o, member, cache_slot);
}

// var_dump()/print_r(): every readable GObject property that we can convert - that is one
// g_object_get_property() per property (a GtkWindow has ~80), some of which realize GTK
// internals; fine for debugging, not something to do inside a draw or measure vfunc.
HashTable *get_debug_info(zend_object *o, int *is_temp) {
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
int compare_objects(zval *a, zval *b) {
  ZEND_COMPARE_OBJECTS_FALLBACK(a, b);
  return Z_OBJ_P(a) == Z_OBJ_P(b) ? 0 : 1;
}
}  // namespace

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

namespace {

// The root class entry, cached for unwrap() (registered first, before any other GObject class).
zend_class_entry *ce_root = nullptr;
}  // namespace

// MINIT: install create_object on a GObject class and record its GType (name and value).
void register_class(const char *gtype_name, zend_class_entry *ce, GType type) {
  ce->create_object = create_object;
  registry()[gtype_name] = ce;
  gtypes()[ce] = type;
  classes()[type] = ce;
  if (type == G_TYPE_OBJECT) ce_root = ce;
}

// MINIT: interfaces take part in both lookups but never get create_object.
void register_interface(const char *gtype_name, zend_class_entry *ce, GType type) {
  registry()[gtype_name] = ce;
  gtypes()[ce] = type;
  classes()[type] = ce;
}

namespace {
// Interface GType -> the concrete class wrap() falls back to (MINIT-filled).
std::unordered_map<GType, zend_class_entry *> &fallbacks() {
  static std::unordered_map<GType, zend_class_entry *> map;
  return map;
}

// The fallback class for an object whose own classes are all unregistered: the most derived
// registered interface it implements (GtkSelectionModel over its GListModel prerequisite), or
// nullptr. That is how a GTK-private class (GtkNotebookPages) still gets get_n_items().
zend_class_entry *fallback_for(GType type) {
  guint n = 0;
  GType *ifaces = g_type_interfaces(type, &n);
  GType best = 0;
  for (guint i = 0; i < n; i++) {
    if (!fallbacks().contains(ifaces[i])) continue;
    if (best == 0 || g_type_is_a(ifaces[i], best)) best = ifaces[i];
  }
  g_free(ifaces);
  return best == 0 ? nullptr : fallbacks()[best];
}
}  // namespace

// MINIT: the class wrap() uses for an unregistered GObject class implementing `iface`.
void register_interface_fallback(GType iface, zend_class_entry *ce) {
  fallbacks()[iface] = ce;
  gtypes()[ce] = iface;
}

// Registry lookup by GType value.
zend_class_entry *class_for_gtype(GType type) {
  auto it = classes().find(type);
  return it == classes().end() ? nullptr : it->second;
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

// C -> PHP: the existing handle (qdata) or a new one of the nearest registered class.
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
    zend_class_entry *ce =
        is_php_type(t) ? subtype_class_for_gtype(t) : class_for_gtype_name(g_type_name(t));
    if (t == G_TYPE_OBJECT && ce != nullptr) {  // nothing more specific: an interface's class?
      if (zend_class_entry *fb = fallback_for(G_OBJECT_TYPE(obj))) ce = fb;
    }
    if (ce != nullptr) {
      if (object_init_ex(rv, ce) == FAILURE) {  // abstract/uninstantiable class: Error is pending
        ZVAL_NULL(rv);
        return;
      }
      attach(object_from_zval(rv), obj);
      return;
    }
  }
  ZVAL_NULL(rv);
  zend_throw_error(nullptr, "no PHP class registered for %s", G_OBJECT_TYPE_NAME(obj));
}

// PHP -> C: the live GObject behind a handle that is-a `expected`, else TypeError + nullptr.
GObject *unwrap(zval *zv, GType expected) {
  if (Z_TYPE_P(zv) != IS_OBJECT || ce_root == nullptr ||
      !instanceof_function(Z_OBJCE_P(zv), ce_root)) {
    zend_type_error("expected a GObject instance, %s given", zend_zval_value_name(zv));
    return nullptr;
  }
  Object *self = object_from_zval(zv);
  if (self->obj == nullptr || self->disposed) {  // the handle itself cannot do it: an Error
    zend_throw_error(nullptr, "%s given: this GObject was %s", ZSTR_VAL(Z_OBJCE_P(zv)->name),
                     self->obj == nullptr ? "finalized" : "disposed");
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
  if (self->obj == nullptr || self->disposed) {
    zend_throw_error(nullptr, "%s() on a %s GObject", method,
                     self->obj == nullptr ? "dead" : "disposed");
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
