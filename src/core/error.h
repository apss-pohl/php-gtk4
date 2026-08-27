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
//
//            Leaving it pending is only safe when that PHP call is the next
//            thing to return. Inside an *unregistered* nested loop (GTK
//            iterating the context itself, or GLib::main_context_iteration())
//            the quit cannot take effect yet, and C code would keep running with
//            an exception pending. The Throwable is then PARKED instead: taken
//            out of the engine, GTK continues normally (handlers keep running so
//            the inner loop can finish), and it is rethrown by the next
//            loop-driving call that returns to PHP - run(), or
//            main_context_iteration(). A second Throwable raised while one is
//            parked is chained onto it as `previous`. Whatever is still parked at
//            request shutdown goes to the handler / g_critical, never nowhere.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

enum class ExceptionMode : zend_long { Log = 0, Rethrow = 1 };
extern zend_class_entry *ce_ExceptionMode;  // the PHP enum, set in MINIT

void set_exception_handler(zval *handler);  // ADDREF'd copy, nullptr clears
bool has_exception_handler();
void set_exception_mode(ExceptionMode mode);
ExceptionMode exception_mode();
void exception_state_shutdown();  // RSHUTDOWN

// If EG(exception) is set: apply the policy above and return true.
// Log mode guarantees no exception is pending afterwards.
bool report_pending_exception(const char *origin);

// Boundary of a loop-driving call (run(), main_context_iteration()): hand a parked
// Throwable back to the engine. True if one was rethrown (caller RETURN_THROWS()).
bool rethrow_parked_exception();

}  // namespace phpgtk
