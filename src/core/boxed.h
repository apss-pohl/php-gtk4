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
  gpointer data;  // owned copy, never nullptr once constructed
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

struct BoxedClass {
  GType type;
  zend_class_entry *ce;
  const char *const *fields;  // nullptr-terminated, for var_dump()
  BoxedReader read;
  BoxedWriter write;
};

void boxed_handlers_init();
void register_boxed(const char *gtype_name, const BoxedClass &info);
const BoxedClass *boxed_class_for_type(GType type);

// Give a freshly created (constructor) handle its data: takes ownership of `data`
// (already allocated with the type's allocator, e.g. g_new0 or a copy).
void boxed_adopt(Boxed *self, GType type, gpointer data);

// C -> PHP: a new handle holding a copy of data (null for nullptr, TypeError
// for an unregistered boxed type).
void wrap_boxed(GType type, gconstpointer data, zval *rv);
// PHP -> C: the handle's data (borrowed); TypeError + nullptr if not a handle of `expected`.
gpointer unwrap_boxed(zval *zv, GType expected);

}  // namespace phpgtk

// In a ZEND_METHOD of a boxed class: `GdkRGBA *c = PHPGTK_BOXED_SELF(GdkRGBA);`
// NOLINTNEXTLINE(bugprone-macro-parentheses) `ctype` is a type name in a cast, not an expression
#define PHPGTK_BOXED_SELF(ctype) (static_cast<ctype *>(phpgtk::boxed_from_zval(ZEND_THIS)->data))
