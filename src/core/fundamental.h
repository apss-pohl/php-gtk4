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

// The type's own reference pair, as GLib spells it (g_param_spec_ref/_unref,
// gdk_event_ref/_unref, ...): a registered type hands both to register_fundamental() and the
// handle calls them, since a fundamental type has no shared refcounting API to call instead.
using RefFn = gpointer (*)(gpointer);
using UnrefFn = void (*)(gpointer);

// What one registered fundamental class is: its GType, the PHP class bound to it, and the pair.
struct FundamentalClass {
  GType type;
  zend_class_entry *ce;
  RefFn ref;
  UnrefFn unref;
};

// MINIT: build the shared handler table for all fundamental handle classes.
void fundamental_handlers_init();
// MINIT: bind a PHP class to a fundamental GType with its ref/unref pair.
void register_fundamental(const FundamentalClass &info);
// Registry lookup by GType, walking up to the nearest registered ancestor (a GdkKeyEvent is
// handled by the GdkEvent class, like wrap() does for GObjects).
const FundamentalClass *fundamental_class_for_type(GType type);

// C -> PHP: new handle holding a ref on `instance` (null for nullptr; TypeError
// for an unregistered type).
void wrap_fundamental(GType type, gpointer instance, zval *rv);
// PHP -> C: borrowed instance of a handle of `expected`, else TypeError + nullptr.
gpointer unwrap_fundamental(zval *zv, GType expected);

// A generated constructor (`new GskColorNode(...)`): the handle takes over the instance the C
// constructor allocated - no extra reference - and registers it for identity. `type` is the
// class' GType; an instantiatable instance answers its real one.
void fundamental_adopt(Fundamental *self, GType type, gpointer instance);
// $this's instance in a ZEND_METHOD of a fundamental class, or nullptr with an Error thrown when
// the handle never got one (its constructor failed, or a subclass' constructor skipped
// parent::__construct()) - the counterpart of PHPGTK_BOXED_SELF for generated code.
gpointer fundamental_self(zend_execute_data *execute_data);

}  // namespace phpgtk

// In a ZEND_METHOD of a fundamental class: `GParamSpec *s = PHPGTK_FUNDAMENTAL_SELF(GParamSpec);`
// NOLINTBEGIN(bugprone-macro-parentheses) `ctype` is a type name in a cast, not an expression
#define PHPGTK_FUNDAMENTAL_SELF(ctype) \
  (static_cast<ctype *>(phpgtk::fundamental_from_zval(ZEND_THIS)->instance))
// NOLINTEND(bugprone-macro-parentheses)
