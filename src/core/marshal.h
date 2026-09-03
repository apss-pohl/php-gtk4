// The single GValue <-> zval bridge. Properties, signal arguments, signal
// return values and list-model items all go through here.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

// GValue -> zval. On an unsupported GType throws (TypeError) and sets rv to null.
void to_php(const GValue *v, zval *rv);
bool to_php_supported(GType type);

// A GType named the way PHP names one: "string", "int", "float"/"double", "bool", or the name
// of a registered class ("GdkTexture", "Gtk4\\GdkTexture"). Drag and drop and the clipboard
// declare payload types that way. 0 with a ValueError thrown when it is neither.
GType gtype_from_php_name(zend_string *name, uint32_t arg);
// The reverse, so a getter answers in the spelling its setter accepts.
zend_string *php_name_for_gtype(GType type);

// zval -> GValue. Initialises *out with `type`; caller g_value_unset()s.
// Returns false (exception thrown) if the value cannot be converted.
bool to_gvalue(zval *pv, GType t, GValue *out);

}  // namespace phpgtk
