// Gtk4\Gtk: static entry points (init, exception handler / mode).
#include "php_gtk4.h"
#include "core/error.h"

using namespace phpgtk;

/**
 * static Gtk4\Gtk::init(): bool
 *
 * Initialise GTK (gtk_init_check). Returns false if no display is available.
 */
ZEND_METHOD(Gtk4_Gtk, init) {
  ZEND_PARSE_PARAMETERS_NONE();
  RETURN_BOOL(gtk_init_check());
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
