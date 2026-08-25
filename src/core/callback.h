// One abstraction for every non-signal PHP callback handed to C (idle and
// timeout sources, sort/filter funcs, draw funcs, ...): keeps the callable
// alive, invokes it with zval arguments, and routes a thrown Throwable through
// the exception boundary with the installing method as origin.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

struct Callback {
  zval callable;
  const char *origin;  // string literal: "GLib::timeout_add" etc.
  guint source_id;     // for GLib sources (0 otherwise)
};

// Takes an ADDREF'd copy of `callable` (already validated by Z_PARAM_FUNC).
Callback *callback_new(zval *callable, const char *origin);
// GDestroyNotify-compatible.
void callback_free(gpointer p);

// Invoke with `argc` arguments; the return value (if any) is written to
// `retval` (caller zval_ptr_dtor()s it; IS_UNDEF when the call failed). Any
// Throwable is reported per the exception mode. Returns false if it threw.
bool callback_invoke(Callback *cb, uint32_t argc, zval *args, zval *retval);

}  // namespace phpgtk
