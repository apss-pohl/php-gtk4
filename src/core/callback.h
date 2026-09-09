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
// GDestroyNotify-compatible. Frees the struct now but DEFERS releasing the
// callable to callback_drain(): destroy notifies run inside GLib/GTK frames
// (a widget's dispose, a filter's finalize, set_draw_func() itself), and
// releasing the callable there can drop the last PHP reference on that very
// owner - a nested finalize the C caller does not survive.
void callback_free(gpointer p);
// Park any zval the same way (the stream an I/O watch keeps): released by callback_drain().
void callback_park(zval *zv);
// Release every callable parked by callback_free(). Called wherever no C frame
// is in the middle of the owner: after a trampoline returns, after a method
// installed/removed a callback, after a handle released its GObject, at RSHUTDOWN.
void callback_drain();

// Invoke with `argc` arguments; the return value (if any) is written to
// `retval` (caller zval_ptr_dtor()s it; IS_UNDEF when the call failed). Any
// Throwable is reported per the exception mode. Returns false if it threw.
bool callback_invoke(Callback *cb, uint32_t argc, zval *args, zval *retval);

}  // namespace phpgtk
