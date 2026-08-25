#pragma once
#include <phpcpp.h>
#include <glib-object.h>

// The single GValue <-> Php::Value bridge. Properties, signal arguments,
// signal return values and list-model items all go through here.
namespace phpgtk {

Php::Value to_php(const GValue *value);
// Initialises *out with `type` and fills it from value. Caller g_value_unset()s.
void to_gvalue(const Php::Value &value, GType type, GValue *out);

}  // namespace phpgtk
