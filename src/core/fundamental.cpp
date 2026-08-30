#include "fundamental.h"

#include "globals.h"

#include <unordered_map>

namespace phpgtk {

namespace {

zend_object_handlers handlers;

// GType -> fundamental class info (MINIT-filled).
std::unordered_map<GType, FundamentalClass> &registry() {
  static std::unordered_map<GType, FundamentalClass> map;
  return map;
}

// create_object handler: the instance is set by wrap_fundamental().
zend_object *create_object(zend_class_entry *ce) {
  auto *self = static_cast<Fundamental *>(zend_object_alloc(sizeof(Fundamental), ce));
  self->type = 0;
  self->instance = nullptr;
  zend_object_std_init(&self->std, ce);
  object_properties_init(&self->std, ce);
  self->std.handlers = &handlers;
  return &self->std;
}

// get_constructor handler: handles only come from C (wrap_fundamental) - `new X()` would
// otherwise yield an empty handle whose methods dereference nullptr.
zend_function *get_constructor(zend_object *o) {
  zend_throw_error(nullptr, "%s instances are created by the extension, not with new",
                   ZSTR_VAL(o->ce->name));
  return nullptr;
}

// free_obj handler: forget the identity entry, then drop our reference through the type's unref.
void free_obj(zend_object *o) {
  Fundamental *self = fundamental_from_zend(o);
  if (self->instance != nullptr) {
    auto &handles = GTK4_G(fundamental_handles);
    auto it = handles.find(self->instance);
    if (it != handles.end() && it->second == o) handles.erase(it);
    const FundamentalClass *info = fundamental_class_for_type(self->type);
    if (info != nullptr) info->unref(self->instance);
  }
  zend_object_std_dtor(o);
}

}  // namespace

// MINIT: build the shared handler table for all fundamental handle classes.
void fundamental_handlers_init() {
  memcpy(&handlers, &std_object_handlers, sizeof(zend_object_handlers));
  handlers.offset = XtOffsetOf(Fundamental, std);
  handlers.free_obj = free_obj;
  handlers.clone_obj = nullptr;
  handlers.get_constructor = get_constructor;
}

// MINIT: bind a PHP class to a fundamental GType with its ref/unref pair.
void register_fundamental(const FundamentalClass &info) {
  info.ce->create_object = create_object;
  registry()[info.type] = info;
}

// Registry lookup by GType, walking up to the nearest registered ancestor (a GdkKeyEvent is
// handled by the GdkEvent class, like wrap() does for GObjects).
const FundamentalClass *fundamental_class_for_type(GType type) {
  for (GType t = type; t != 0; t = g_type_parent(t)) {
    auto it = registry().find(t);
    if (it != registry().end()) return &it->second;
  }
  return nullptr;
}

// C -> PHP: the live handle of this instance (`===` holds while PHP keeps it, like GObject
// handles do through their qdata), else a new one with its own reference. The map is keyed by
// the instance pointer; a handle owns a reference, so the pointer cannot be reused behind it -
// except for types without a refcount (GdkEventSequence: GTK frees the sequence when the touch
// ends and may hand the address out again; a stale handle then names the new sequence).
void wrap_fundamental(GType type, gpointer instance, zval *rv) {
  if (instance == nullptr) {
    ZVAL_NULL(rv);
    return;
  }
  auto &handles = GTK4_G(fundamental_handles);
  if (auto it = handles.find(instance); it != handles.end()) {
    ZVAL_OBJ_COPY(rv, it->second);
    return;
  }
  // An instantiatable fundamental (GdkEvent) knows its real type: a GdkKeyEvent handed over as
  // a GdkEvent still gets the GdkKeyEvent class. Refcounted boxed types (cairo_t) have no header.
  if (G_TYPE_IS_INSTANTIATABLE(type)) type = G_TYPE_FROM_INSTANCE(instance);
  const FundamentalClass *info = fundamental_class_for_type(type);
  if (info == nullptr) {
    ZVAL_NULL(rv);
    zend_type_error("to_php: unsupported fundamental type %s", g_type_name(type));
    return;
  }
  object_init_ex(rv, info->ce);
  Fundamental *self = fundamental_from_zval(rv);
  self->type = type;
  self->instance = info->ref(instance);
  handles[instance] = Z_OBJ_P(rv);
}

// PHP -> C: borrowed instance of exactly `expected`.
gpointer unwrap_fundamental(zval *zv, GType expected) {
  const FundamentalClass *info = fundamental_class_for_type(expected);
  if (info == nullptr || Z_TYPE_P(zv) != IS_OBJECT ||
      !instanceof_function(Z_OBJCE_P(zv), info->ce) ||
      g_type_is_a(fundamental_from_zval(zv)->type, expected) == FALSE) {
    zend_type_error("expected %s, %s given", g_type_name(expected), zend_zval_value_name(zv));
    return nullptr;
  }
  return fundamental_from_zval(zv)->instance;
}

}  // namespace phpgtk
