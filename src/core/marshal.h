// The single GValue <-> zval bridge. Properties, signal arguments, signal
// return values and list-model items all go through here.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

// GValue -> zval. On an unsupported GType throws (TypeError) and sets rv to null.
void to_php(const GValue *v, zval *rv);
// Whether to_php() can convert a value of this GType (var_dump() skips the fields it cannot).
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
// The G_TYPE_VARIANT arm of to_gvalue() with a type to fit (nullptr infers).
bool to_gvalue_variant(zval *pv, const GVariantType *type, GValue *out);
// to_gvalue() for a property write on `obj`: a variant property converts to the type its pspec
// (or, for a GAction's `state`, the instance) says it holds, like the setter's parameter would.
bool to_gvalue_property(zval *pv, GObject *obj, GParamSpec *spec, GValue *out);

// The coercion rules a property write shares with every other conversion (core/variant): whether
// the assigning PHP code declared strict_types, and what weak mode accepts for a string.
bool caller_is_strict();
// What PHP's weak mode accepts for a string parameter: scalars, and objects with __toString().
bool weak_to_string_ok(const zval *pv);

}  // namespace phpgtk
