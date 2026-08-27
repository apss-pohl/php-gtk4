// Gtk4\Gtk: static entry points (init, exception handler / mode).
#include "php_gtk4.h"
#include "core/error.h"
#include "core/globals.h"

using namespace phpgtk;

/**
 * static Gtk4\Gtk::init(): bool
 *
 * Initialise GTK (gtk_init_check). Returns false if no display is available.
 */
ZEND_METHOD(Gtk4_Gtk, init) {
  ZEND_PARSE_PARAMETERS_NONE();
  if (!assert_gui_thread("Gtk::init()")) RETURN_THROWS();
  const bool ok = gtk_init_check();
  if (ok) record_gui_thread();
  RETURN_BOOL(ok);
}

/**
 * static Gtk4\Gtk::set_exception_handler(?callable $handler): void
 *
 * Install (or with null, remove) the callable that receives exceptions thrown inside signal
 * handlers and other callbacks. Signature: `function (\Throwable $exception, string $origin):
 * void`; $origin is the signal name, or the installing method for non-signal callbacks. Called in
 * both exception modes, before a rethrow.
 */
ZEND_METHOD(Gtk4_Gtk, set_exception_handler) {
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  set_exception_handler(ZEND_FCI_INITIALIZED(fci) ? &fci.function_name : nullptr);
}

/**
 * static Gtk4\Gtk::set_exception_mode(ExceptionMode $mode): void
 */
ZEND_METHOD(Gtk4_Gtk, set_exception_mode) {
  zval *mode;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(mode, ce_ExceptionMode)
  ZEND_PARSE_PARAMETERS_END();
  const zval *value = zend_enum_fetch_case_value(Z_OBJ_P(mode));
  set_exception_mode(static_cast<ExceptionMode>(Z_LVAL_P(value)));
}

/**
 * static Gtk4\Gtk::get_exception_mode(): ExceptionMode
 */
ZEND_METHOD(Gtk4_Gtk, get_exception_mode) {
  ZEND_PARSE_PARAMETERS_NONE();
  const char *name = exception_mode() == ExceptionMode::Rethrow ? "Rethrow" : "Log";
  zend_object *c = zend_enum_get_case_cstr(ce_ExceptionMode, name);
  RETURN_OBJ_COPY(c);
}

#ifdef PHPGTK_TESTING
/**
 * static Gtk4\Gtk::testing_iterate_nested(int $iterations): void
 *
 * Test builds only (`--enable-gtk4-testing`, `FEATURES` has `testing=yes`): iterate the default
 * context $iterations times from C, blocking each time, the way GTK does inside DnD or a portal
 * call. Deliberately *not* a rethrow boundary - a Throwable parked in {@see
 * ExceptionMode::Rethrow} surfaces from the enclosing run(), as it would then.
 */
ZEND_METHOD(Gtk4_Gtk, testing_iterate_nested) {
  zend_long iterations;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_LONG(iterations)
  ZEND_PARSE_PARAMETERS_END();
  if (iterations < 0) {
    zend_argument_value_error(1, "must be greater than or equal to 0");
    RETURN_THROWS();
  }
  // C-driven nesting: no PHP boundary in between, so a parked Throwable stays parked and
  // a pending one (no registered loop) simply propagates when this call returns.
  for (zend_long i = 0; i < iterations && EG(exception) == nullptr; i++) {
    g_main_context_iteration(nullptr, TRUE);
  }
}
#endif
