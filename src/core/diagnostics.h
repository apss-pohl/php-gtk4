// GLib/GTK diagnostics as PHP errors.
//
// GLib reports a caller's mistake by logging: g_return_if_fail() is a g_critical(), and
// GTK uses it for every precondition it refuses. When PHP is the caller that is a PHP
// programming error, and stderr is the wrong place for it - no file/line, no
// error_reporting, no set_error_handler, no error_log, and gone entirely when the
// process's stderr is redirected.
//
// Reporting cannot happen where the message arrives. The GLib writer runs inside
// arbitrary GTK frames, and zend_error() there would either run a user error handler
// (which may throw, leaving EG(exception) set as we return into GTK - the one thing
// error.h forbids) or bail out by longjmp across GTK's frames. So the writer only
// *records* - no PHP runs in it - and sets EG(vm_interrupt). The VM then calls our
// zend_interrupt_function at its next safe point, which the engine documents as "the
// loop header, user function entry, and after internal function calls" (Zend/zend.h):
// for a message raised inside a method that is the PHP line which called it, so the
// report keeps its attribution while running where PHP may safely throw or bail out.
//
// Each message also carries the file/line PHP was executing when it arrived, so one
// recorded with no PHP on the stack (GTK's main loop between callbacks) still names
// something useful whenever it is finally reported.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

// gtk4.diagnostics: what a GLib message becomes.
//
//   Warning  (default) CRITICAL and WARNING -> E_WARNING. A GLib CRITICAL says the script did
//                      something GTK refuses, not that the process is unusable: GTK
//                      returns and carries on, so ending a desktop application over
//                      it would lose more than it reports. It is also the only level
//                      set_error_handler() can reach.
//   Fatal              CRITICAL -> E_ERROR, WARNING -> E_WARNING. Development and CI,
//                      where an unguarded boundary should stop the run.
//   Stderr             GLib's own writer, unchanged
//   Off                dropped
//
// G_LOG_LEVEL_MESSAGE is GTK advising rather than refusing, and becomes an E_NOTICE in both
// reporting modes. INFO and DEBUG are left to GLib, which honours G_MESSAGES_DEBUG.
enum class DiagnosticsMode : zend_long { Fatal = 0, Warning = 1, Stderr = 2, Off = 3 };

// Parse an ini value. False (leaving *out alone) on an unknown name.
bool diagnostics_mode_from_name(const char *name, size_t len, DiagnosticsMode *out);

void diagnostics_minit();             // install the GLib writer, chain zend_interrupt_function
void diagnostics_mshutdown();         // unchain before this .so is unloaded
void diagnostics_request_init();      // claim this thread as the one messages belong to
void diagnostics_request_shutdown();  // report whatever never reached a safe point

// php-gtk4's own diagnostics. Always an E_WARNING, never fatal: ExceptionMode::Log
// promises GTK keeps running, and its report must not be what stops it.
void diagnostic(const char *format, ...) G_GNUC_PRINTF(1, 2);

// Report everything recorded so far. This is the interrupt handler; calling it anywhere
// else is only safe where PHP itself may run.
void diagnostics_flush();

}  // namespace phpgtk
