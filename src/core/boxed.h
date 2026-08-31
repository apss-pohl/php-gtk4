// PHP handles for GLib boxed types (GdkRGBA, GdkRectangle, ...). Unlike
// GObject handles these are VALUE types: the handle owns a g_boxed_copy(),
// clone makes another copy, and struct fields are exposed as PHP properties.
//
// Boxed types that map better to plain PHP values are converted in marshal
// instead of getting a handle: GStrv <-> list<string> (likewise GBytes <-> string,
// GError -> exception).
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

struct Boxed {
  GType type;
  gpointer data;   // owned copy, never nullptr once constructed
  GObject *owner;  // the object the value points into (BoxedClass::owner), ref held; or nullptr
  zend_object std;
};

// zend_object -> the embedding handle.
inline Boxed *boxed_from_zend(zend_object *o) {
  return reinterpret_cast<Boxed *>(reinterpret_cast<char *>(o) - XtOffsetOf(Boxed, std));
}
// zval (IS_OBJECT) -> the embedding handle.
inline Boxed *boxed_from_zval(const zval *zv) {
  return boxed_from_zend(Z_OBJ_P(zv));
}

// Field access for a boxed class: return false for an unknown field.
using BoxedReader = bool (*)(gpointer data, const char *field, zval *rv);
using BoxedWriter = bool (*)(gpointer data, const char *field, zval *value);
// The GObject a live value points into (a GtkTextIter's buffer): called at wrap/clone time
// on valid data, and the handle refs the result so the value cannot dangle when the script
// drops the owner. nullptr for self-contained values (GdkRGBA).
using BoxedOwner = GObject *(*)(gpointer data);

struct BoxedClass {
  GType type{};
  zend_class_entry *ce{};
  const char *const *fields{};  // nullptr-terminated, for var_dump()
  BoxedReader read{};
  BoxedWriter write{};
  BoxedOwner owner = nullptr;
};

void boxed_handlers_init();
void register_boxed(const BoxedClass &info);
const BoxedClass *boxed_class_for_type(GType type);

// Give a freshly created (constructor) handle its data: takes ownership of `data`
// (already allocated with the type's allocator, e.g. g_new0 or a copy).
void boxed_adopt(Boxed *self, GType type, gpointer data);

// C -> PHP: a new handle holding a copy of data (null for nullptr, TypeError
// for an unregistered boxed type).
void wrap_boxed(GType type, gconstpointer data, zval *rv);
// PHP -> C: the handle's data (borrowed); TypeError + nullptr if not a handle of `expected`.
gpointer unwrap_boxed(zval *zv, GType expected);

// $this of a boxed method as its data; Error + nullptr on a handle that never got any
// (a record without a constructor, see get_constructor in boxed.cpp).
gpointer boxed_self(zend_execute_data *execute_data, const char *method);

}  // namespace phpgtk

// In a ZEND_METHOD of a boxed class: `GdkRGBA *c = PHPGTK_BOXED_SELF(GdkRGBA);` -
// returns from the method (exception already thrown) if the handle has no data.
// NOLINTBEGIN(bugprone-macro-parentheses) `ctype` is a type name; the macro is a statement pair
#define PHPGTK_BOXED_SELF(ctype)                                    \
  static_cast<ctype *>(phpgtk::boxed_self(execute_data, __func__)); \
  if (EG(exception)) return
// NOLINTEND(bugprone-macro-parentheses)
