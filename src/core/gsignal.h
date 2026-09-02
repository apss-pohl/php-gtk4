// $obj->connect(string $signal, callable $handler): int
//
// A GClosure with a custom marshaller receiving GValue arrays - no varargs,
// so every parameter type the marshal bridge knows is supported and unknown
// ones fail per-signal instead of corrupting the stack. The callable is
// resolved once at connect time (zend_fcall_info_cache) and invoked with
// zend_call_function directly.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

// A floating GClosure invoking `callable` through the same GValue-array marshaller connect()
// uses, with `origin` naming it at the exception boundary. The caller owns the reference
// (g_closure_sink() it or hand it to something that does) and is responsible for tracking it
// with core/teardown if it outlives the request.
GClosure *php_closure_new(zval *callable, zend_string *origin);
// Implements connect()/connect_after(); parses its own arguments.
void signal_connect_method(INTERNAL_FUNCTION_PARAMETERS, bool after);
// Implements emit(); parses its own arguments.
void signal_emit_method(INTERNAL_FUNCTION_PARAMETERS);

}  // namespace phpgtk
