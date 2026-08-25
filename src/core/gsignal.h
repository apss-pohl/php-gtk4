#pragma once
#include <phpcpp.h>
#include <glib-object.h>

namespace phpgtk {

class GObjectWrapper;

// $obj->connect(string $signal, callable $cb, mixed ...$userdata): int
//
// Uses a GClosure with a custom marshaller that receives GValue arrays -
// no varargs, so every parameter type the marshal bridge knows is supported
// and unknown ones fail loudly per-signal instead of corrupting the stack.
Php::Value signal_connect(GObjectWrapper *self, Php::Parameters &params, bool after);

}  // namespace phpgtk
