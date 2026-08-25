// The single GValue <-> zval bridge. Properties, signal arguments, signal
// return values and (later) list-model items all go through here.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

// GValue -> zval. On an unsupported GType throws (TypeError) and sets rv to null.
void to_php(const GValue *v, zval *rv);
bool to_php_supported(GType type);

// zval -> GValue. Initialises *out with `type`; caller g_value_unset()s.
// Returns false (exception thrown) if the value cannot be converted.
bool to_gvalue(zval *pv, GType t, GValue *out);

}  // namespace phpgtk
