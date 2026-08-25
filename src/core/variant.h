// GVariant <-> PHP values. Variants are mapped to plain values, not handles:
//   b -> bool; y n q i u x t h -> int; d -> float; s o g -> string;
//   v -> the inner value; m<T> -> null or T; a<T> -> list; a{s*} -> assoc array;
//   tuples -> list.
// PHP -> GVariant needs a type: either an explicit GVariantType (the action's
// parameter type, the state's current type) or inference (bool->b, int->i,
// float->d, string->s, list of strings->as, other list->av, assoc->a{sv}).
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

// Never fails; unknown/unsupported variants become null.
void variant_to_php(GVariant *variant, zval *rv);

// Returns a floating-or-full reference the caller sinks/owns, or nullptr
// (TypeError thrown) if the value does not fit `type`. `type` may be nullptr
// to infer.
GVariant *php_to_variant(zval *value, const GVariantType *type);

}  // namespace phpgtk
