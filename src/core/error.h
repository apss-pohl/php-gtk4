// The C++/PHP exception boundary.
//
// A PHP throwable must never unwind across GLib's C frames (signal emission,
// the main loop), and Zend refuses to run PHP code while an exception is
// pending. Every trampoline that calls into PHP therefore ends with
// report_pending_exception(origin), which implements the two policies of
// Gtk4\ExceptionMode:
//
//   Log      take the Throwable out of the engine, hand it to the handler
//            installed with Gtk::set_exception_handler() (else g_critical()),
//            and let GTK continue as if nothing happened.
//   Rethrow  call the handler too (for observability), then leave the Throwable
//            pending in the engine and quit every running main loop. Zend then
//            skips all further PHP callbacks of the emission (zend_call_function
//            is a no-op while an exception is pending) and the Throwable
//            propagates from whatever PHP call triggered the C code - the
//            emitting method, or GMainLoop::run() / GtkApplication::run().
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

enum class ExceptionMode : zend_long { Log = 0, Rethrow = 1 };

void set_exception_handler(zval *handler);  // ADDREF'd copy, nullptr clears
bool has_exception_handler();
void set_exception_mode(ExceptionMode mode);
ExceptionMode exception_mode();
void exception_state_shutdown();  // RSHUTDOWN

// If EG(exception) is set: apply the policy above and return true.
// Log mode guarantees no exception is pending afterwards.
bool report_pending_exception(const char *origin);

}  // namespace phpgtk
