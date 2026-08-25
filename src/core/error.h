// The C++/PHP exception boundary.
//
// A PHP throwable must never unwind across GLib's C frames (signal emission,
// the main loop), and Zend refuses to run PHP code while an exception is
// pending. So every trampoline that calls into PHP checks EG(exception)
// afterwards, takes the Throwable object out of the engine
// (zend_clear_exception) and hands it to report_callback_exception(), which
// calls the handler installed with Gtk::set_exception_handler() or falls
// back to g_critical(). The emitting GTK call then continues normally.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

// Store (Z_ADDREF'd copy) or clear (nullptr) the handler. Request-scoped:
// released in RSHUTDOWN.
void set_exception_handler(zval *handler);
bool has_exception_handler();
void exception_handler_shutdown();

// If EG(exception) is set: take it, report it with `origin` (signal name or
// installing method) and return true. Never leaves an exception pending.
bool report_pending_exception(const char *origin);

}  // namespace phpgtk
