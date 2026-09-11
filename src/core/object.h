// PHP handle for a GObject: the zend_object layout, its handlers, the
// GType -> class registry, and the two conversions wrap()/unwrap().
//
// Ownership: the handle holds one TOGGLE reference (g_object_add_toggle_ref on
// attach, removed in free_obj). A qdata back-pointer makes wrapping the same C
// object twice return the same PHP object (`===`). While *other* references
// exist (GTK holds the object: a parented widget, a store item) the GObject in
// turn holds the zend_object (GC_ADDREF, `held`), so a PHP subclass' state and
// identity survive the script dropping its own reference; when the toggle
// notify says ours is the last reference again the hold is released (and the
// handle freed with it if PHP had let go too). Handles cannot be cloned or
// serialized.
// GObject properties are exposed as PHP properties ($win->title) through
// the read/write/has_property handlers and in var_dump() via get_debug_info.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

struct Object {
  GObject *obj;     // nullptr = not attached yet
  bool held;        // the GObject holds a reference on `std` (toggle ref: not the last ref)
  bool disposed;    // GObject::dispose ran while PHP still referenced it (gtk_window_destroy)
  zval owner;       // handle this one may not outlive (object_hold_owner), IS_UNDEF for most
  zend_object std;  // must be last (zend_object is variable-sized)
};

// zend_object -> the embedding handle.
inline Object *object_from_zend(zend_object *o) {
  return reinterpret_cast<Object *>(reinterpret_cast<char *>(o) - XtOffsetOf(Object, std));
}
// zval (IS_OBJECT) -> the embedding handle.
inline Object *object_from_zval(const zval *zv) {
  return object_from_zend(Z_OBJ_P(zv));
}

// Make the handle in `handle` keep the handle in `owner` alive: GTK hands out objects that
// point back at the object they came from *without* a reference of their own - a GtkStackPages
// keeps a bare GtkStack pointer, a composite widget's internal child measures through its
// parent's private struct - so `new GtkStack()->get_pages()` leaves a live handle onto freed
// memory. The object analogue of BOXED_OWNERS (gen/gir/config.php), and deliberately held
// between the *handles* rather than between the GObjects: a parent already owns its child, so a
// GObject-level back-reference would be an uncollectable cycle, while this one is an ordinary
// zval the cycle collector can see (get_gc). The other half of that cycle runs through C - the
// parent GObject holds the child GObject, which holds the child handle (`held`) - so the owner's
// get_gc reports every held dependent whose GTK reference is the owner's own (the child of a
// parent widget), the one edge Zend cannot see by itself. Emitted by the generator for the
// members listed in RETURNS_HOLD_SELF; a second call with the same owner is a no-op.
void object_hold_owner(zval *handle, zval *owner);

// A converted property value against the pspec's own bounds (a float's minimum/maximum, an
// enum's members): GLib would otherwise refuse it with a warning and keep the old value, telling
// PHP nothing. False with a ValueError pending, naming `what` (`GtkLabel::$xalign`).
bool property_value_in_range(GParamSpec *spec, const GValue *value, const char *what);
// A property PHP may write at all: false with an Error pending for a read-only or a
// construct-only one (GLib would warn and keep the old value).
bool property_writable(GParamSpec *spec, const char *class_name, const char *member);

// The handle registered on a GObject (qdata back-pointer), nullptr if none.
Object *object_handle(GObject *obj);
// subtype.cpp instance_init: bind the handle under construction to its instance before
// g_object_new() returns (so vfuncs and wrap() find it); attach*() then settles the ref.
void object_prebind(Object *self, GObject *obj);

// Bind a handle to an object we are BORROWING (wrap() of something GTK owns,
// or a floating widget): takes its own reference with g_object_ref_sink().
void attach(Object *self, GObject *obj);
// Bind a handle to an object we just CREATED (constructors): a floating
// instance is sunk, a plain GObject's initial reference is adopted as ours -
// no extra ref, so the object really dies with the last handle/owner. NOT for
// GtkRoot implementors (gtk_window_new(): GTK's toplevel list owns that
// reference) - use attach(); attach_new() detects and corrects it with a diagnostic().
void attach_new(Object *self, GObject *obj);

// Call once from MINIT before registering classes.
void object_handlers_init();
// RSHUTDOWN (teardown_request): release every hold a GObject has on a PHP handle and stop
// taking new ones. Objects still referenced at shutdown are reported as leaks by Zend
// ("so they show up as leaks" - zend_objects_store_free_object_storage), and a widget kept
// alive by its window at exit is exactly that unless the hold goes first.
void object_release_holds();
// RINIT: re-arm holding for the next request.
void object_request_init();
// Install create_object on a class entry (subclasses inherit it) and add the
// GType -> class mapping used by wrap().
// `type` is the GType (its *_get_type() is called here, which also forces GTK's
// lazy type registration so lookups by name work from the start).
void register_class(const char *gtype_name, zend_class_entry *ce, GType type);
// MINIT: record an interface's class entry and GType (no create_object: nothing instantiates
// it) so class_for_gtype(G_TYPE_LIST_MODEL) works for ?GListModel parameters.
void register_interface(const char *gtype_name, zend_class_entry *ce, GType type);
// The concrete class wrap() uses for a GObject whose own class is unregistered, whose nearest
// registered class is `ce`'s parent, and which implements `iface` (generated as
// Gtk4\<Interface>Object with a private constructor: extends GObject for a GTK-private class,
// or a bound base for a backend-private subclass of it - GdkToplevelObject is a GdkSurface).
void register_interface_fallback(GType iface, zend_class_entry *ce);
// Registry lookup by GType name (gtype_from_php_name(); wrap() uses class_for_gtype()).
zend_class_entry *class_for_gtype_name(const char *gtype_name);
// Registered class of exactly this GType (no parent walk - for Z_PARAM_OBJECT_OF_CLASS).
zend_class_entry *class_for_gtype(GType type);
// GType of a registered PHP class (0 if unknown).
GType gtype_for_class(zend_class_entry *ce);

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
// NOLINTBEGIN(bugprone-macro-parentheses) `ctype` is a type name; the macro is a statement pair
#define PHPGTK_SELF(ctype, gtype)                                                  \
  reinterpret_cast<ctype *>(phpgtk::self_object(execute_data, (gtype), __func__)); \
  if (EG(exception)) return
// NOLINTEND(bugprone-macro-parentheses)
