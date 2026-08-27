// Handles for refcounted G_TYPE_FUNDAMENTAL types that are neither GObjects
// nor boxed: GParamSpec, GdkEvent, GskRenderNode, GtkExpression, ... Each
// registered type brings its ref/unref pair; the handle owns one reference.
// Not cloneable (they are not value types), not serializable.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

struct Fundamental {
  GType type;
  gpointer instance;  // owned reference
  zend_object std;
};

// zend_object -> the embedding handle.
inline Fundamental *fundamental_from_zend(zend_object *o) {
  return reinterpret_cast<Fundamental *>(reinterpret_cast<char *>(o) -
                                         XtOffsetOf(Fundamental, std));
}
// zval (IS_OBJECT) -> the embedding handle.
inline Fundamental *fundamental_from_zval(const zval *zv) {
  return fundamental_from_zend(Z_OBJ_P(zv));
}

using RefFn = gpointer (*)(gpointer);
using UnrefFn = void (*)(gpointer);

struct FundamentalClass {
  GType type;
  zend_class_entry *ce;
  RefFn ref;
  UnrefFn unref;
};

void fundamental_handlers_init();
void register_fundamental(const FundamentalClass &info);
const FundamentalClass *fundamental_class_for_type(GType type);

// C -> PHP: new handle holding a ref on `instance` (null for nullptr; TypeError
// for an unregistered type).
void wrap_fundamental(GType type, gpointer instance, zval *rv);
// PHP -> C: borrowed instance of a handle of `expected`, else TypeError + nullptr.
gpointer unwrap_fundamental(zval *zv, GType expected);

}  // namespace phpgtk

// In a ZEND_METHOD of a fundamental class: `GParamSpec *s = PHPGTK_FUNDAMENTAL_SELF(GParamSpec);`
#define PHPGTK_FUNDAMENTAL_SELF(ctype) \
  static_cast<ctype *>(phpgtk::fundamental_from_zval(ZEND_THIS)->instance)
