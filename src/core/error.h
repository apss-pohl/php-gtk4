#pragma once
#include <phpcpp.h>
#include <string>

// The C++/PHP exception boundary.
//
// A PHP throwable must never unwind across GLib's C frames. Every trampoline
// that calls into PHP wraps its whole body in try/catch(Php::Throwable&),
// copies the details out, LEAVES the catch scope (PHP-CPP clears the pending
// Zend exception when the caught object is destroyed - while it is pending,
// every Php::call() silently no-ops), and only then calls
// phpgtk_report_callback_exception().
namespace phpgtk {

void set_exception_handler(const Php::Value &handler);  // null removes
bool has_exception_handler();

// context: signal name, or installing method (e.g. "GLib::timeout_add").
void report_callback_exception(const std::string &message, long code, const char *context);

}  // namespace phpgtk
