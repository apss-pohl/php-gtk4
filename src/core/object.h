// PHP handle for a GObject: the zend_object layout, its handlers, the
// GType -> class registry, and the two conversions wrap()/unwrap().
//
// Ownership: the handle OWNS one reference (g_object_ref_sink on attach,
// unref in free_obj). A qdata back-pointer makes wrapping the same C object
// twice return the same PHP object (`===`), a weak ref nulls the handle if
// GTK finalizes the object anyway. Handles cannot be cloned or serialized.
// GObject properties are exposed as PHP properties ($win->title) through
// the read/write/has_property handlers and in var_dump() via get_debug_info.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

struct Object {
  GObject *obj;     // nullptr = not attached yet, or finalized behind our back
  zend_object std;  // must be last (zend_object is variable-sized)
};

inline Object *object_from_zend(zend_object *o) {
  return reinterpret_cast<Object *>(reinterpret_cast<char *>(o) - XtOffsetOf(Object, std));
}
inline Object *object_from_zval(const zval *zv) {
  return object_from_zend(Z_OBJ_P(zv));
}

// Bind a handle to an object we are BORROWING (wrap() of something GTK owns,
// or a floating widget): takes its own reference with g_object_ref_sink().
void attach(Object *self, GObject *obj);
// Bind a handle to an object we just CREATED (constructors): a floating
// instance is sunk, a plain GObject's initial reference is adopted as ours -
// no extra ref, so the object really dies with the last handle/owner. NOT for
// gtk_window_new(): that reference is owned by GTK's toplevel list (use attach()).
void attach_new(Object *self, GObject *obj);

// Call once from MINIT before registering classes.
void object_handlers_init();
// Install create_object on a class entry (subclasses inherit it) and add the
// GType -> class mapping used by wrap().
void register_class(const char *gtype_name, zend_class_entry *ce);
zend_class_entry *class_for_gtype_name(const char *gtype_name);

// C -> PHP. Writes the handle into rv (existing object if one is registered,
// else a new one of the nearest registered ancestor class); null for nullptr.
void wrap(GObject *obj, zval *rv);

// PHP -> C. Throws TypeError and returns nullptr if zv is not a live handle of
// a GObject that is-a `expected`.
GObject *unwrap(zval *zv, GType expected = G_TYPE_OBJECT);

// The C object behind $this in a method; throws and returns nullptr if the
// handle is dead or not the expected GType.
GObject *self_object(zend_execute_data *execute_data, GType expected, const char *method);

}  // namespace phpgtk

// In a ZEND_METHOD: `GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);` -
// returns from the method (exception already thrown) if unavailable.
#define PHPGTK_SELF(ctype, gtype)                                                  \
  reinterpret_cast<ctype *>(phpgtk::self_object(execute_data, (gtype), __func__)); \
  if (EG(exception)) return
