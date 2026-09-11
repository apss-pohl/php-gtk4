// GVariant <-> PHP values. Variants are mapped to plain values, not handles:
//   b -> bool; y n q i u x t h -> int; d -> float; s o g -> string;
//   v -> the inner value; m<T> -> null or T; a<T> -> list; a{s*} -> assoc array;
//   tuples -> list.
// PHP -> GVariant needs a type: either an explicit GVariantType (the action's
// parameter type, the state's current type) or inference (bool->b, int->i,
// float->d, string->s, list of strings->as, other list->av, assoc->a{sv}).
// A typed `ay` also takes a PHP string as the bytes (D-Bus binary payloads).
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

// Never fails; unknown/unsupported variants become null.
void variant_to_php(GVariant *variant, zval *rv);

// Returns a floating-or-full reference the caller sinks/owns, or nullptr
// (TypeError thrown) if the value does not fit `type`. `type` may be nullptr
// to infer.
GVariant *php_to_variant(zval *value, const GVariantType *type);

// A D-Bus message body: always a tuple. `type` is the tuple type (a method's in/out
// signature from its GDBusMethodInfo, or one the caller spelled) or nullptr, when a PHP list
// becomes a tuple of its inferred members and null the empty tuple. A non-list is refused
// (TypeError naming `arg_num`). Same ownership as php_to_variant().
GVariant *php_to_variant_tuple(zval *value, const GVariantType *type, uint32_t arg_num);

}  // namespace phpgtk
