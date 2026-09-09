#include "error.h"

#include <array>

#include "globals.h"
#include "diagnostics.h"
#include "mainloop.h"

namespace phpgtk {

zend_class_entry *ce_ExceptionMode = nullptr;

// Per-request state (handler zval, parked Throwable, mode) lives in the module globals
// (core/globals.h); accessed through GTK4_G() below.

// Store (ADDREF'd) or clear the handler installed via Gtk::set_exception_handler().
void set_exception_handler(zval *h) {
  if (!Z_ISUNDEF(GTK4_G(exception_handler))) zval_ptr_dtor(&GTK4_G(exception_handler));
  if (h == nullptr || Z_TYPE_P(h) == IS_NULL) {
    ZVAL_UNDEF(&GTK4_G(exception_handler));
  } else {
    ZVAL_COPY(&GTK4_G(exception_handler), h);
  }
}

// Whether a handler is installed (inspects the slot only - never calls into PHP).
bool has_exception_handler() {
  return !Z_ISUNDEF(GTK4_G(exception_handler));
}
// Gtk::set_exception_mode().
void set_exception_mode(ExceptionMode m) {
  GTK4_G(exception_mode) = m;
}
// Current Gtk4\ExceptionMode.
ExceptionMode exception_mode() {
  return GTK4_G(exception_mode);
}

namespace {
void log_uncaught(zval *exception, const char *origin);
bool call_handler(zval *exception, const char *origin);
}  // namespace

// RSHUTDOWN: a Throwable still parked (no boundary returned to PHP) is reported, not
// dropped; then release the handler zval and reset the mode.
void exception_state_shutdown() {
  if (!Z_ISUNDEF(GTK4_G(parked_exception))) {
    if (!(has_exception_handler() && call_handler(&GTK4_G(parked_exception), "request shutdown"))) {
      log_uncaught(&GTK4_G(parked_exception), "request shutdown");
    }
    zval_ptr_dtor(&GTK4_G(parked_exception));
    ZVAL_UNDEF(&GTK4_G(parked_exception));
  }
  set_exception_handler(nullptr);
  GTK4_G(exception_mode) = ExceptionMode::Log;
}

namespace {

// Rethrow mode inside an unregistered nested loop: keep the Throwable (takes the
// reference) until a loop-driving call returns to PHP; a later one becomes `previous`.
void park_exception(zval *exception, const char *origin) {
  if (Z_ISUNDEF(GTK4_G(parked_exception))) {
    ZVAL_COPY_VALUE(&GTK4_G(parked_exception), exception);
    diagnostic(
        "php-gtk4: %s thrown in '%s' inside a nested main loop (depth %d); rethrow is "
        "deferred until control returns to PHP",
        ZSTR_VAL(Z_OBJCE_P(exception)->name), origin, g_main_depth());
    return;
  }
  // Appends at the end of the parked exception's previous-chain and owns the reference.
  zend_exception_set_previous(Z_OBJ(GTK4_G(parked_exception)), Z_OBJ_P(exception));
}
}  // namespace

// Boundary back to PHP: throw the parked Throwable (if any) from the calling method.
bool rethrow_parked_exception() {
  if (Z_ISUNDEF(GTK4_G(parked_exception))) return false;
  zval exception = GTK4_G(parked_exception);
  ZVAL_UNDEF(&GTK4_G(parked_exception));
  zend_throw_exception_object(&exception);  // takes the reference
  return true;
}

namespace {

// Fallback when no handler is installed (or it failed): a PHP warning with class + message.
void log_uncaught(zval *exception, const char *origin) {
  zval rv;
  zval *msg = zend_read_property_ex(Z_OBJCE_P(exception), Z_OBJ_P(exception),
                                    ZSTR_KNOWN(ZEND_STR_MESSAGE), /* silent */ true, &rv);
  diagnostic("php-gtk4: uncaught %s in '%s' handler: %s", ZSTR_VAL(Z_OBJCE_P(exception)->name),
             origin, msg != nullptr && Z_TYPE_P(msg) == IS_STRING ? Z_STRVAL_P(msg) : "");
}

// Returns true if the handler ran without throwing.
bool call_handler(zval *exception, const char *origin) {
  std::array<zval, 2> args{};
  ZVAL_COPY(args.data(), exception);
  ZVAL_STRING(&args[1], origin);
  zval retval;
  ZVAL_UNDEF(&retval);
  bool ok = false;
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  if (zend_fcall_info_init(&GTK4_G(exception_handler), 0, &fci, &fcc, nullptr, nullptr) ==
      SUCCESS) {
    fci.retval = &retval;
    fci.params = args.data();
    fci.param_count = 2;
    zend_call_function(&fci, &fcc);
    zval_ptr_dtor(&retval);
    if (EG(exception) != nullptr) {
      // The handler itself failed; it must not escape into GLib either.
      diagnostic(
          "php-gtk4: Gtk::set_exception_handler() callback threw %s while reporting from '%s'",
          ZSTR_VAL(EG(exception)->ce->name), origin);
      zend_clear_exception();
    } else {
      ok = true;
    }
  }
  zval_ptr_dtor(args.data());
  zval_ptr_dtor(&args[1]);
  return ok;
}
}  // namespace

// Apply the exception policy to EG(exception) - see error.h for the two modes.
bool report_pending_exception(const char *origin_c) {
  if (EG(exception) == nullptr) return false;
  const char *origin = origin_c != nullptr ? origin_c : "";

  zval exception;
  ZVAL_OBJ_COPY(&exception, EG(exception));
  zend_clear_exception();  // Zend would refuse to run the handler otherwise

  const bool reported = has_exception_handler() && call_handler(&exception, origin);

  if (GTK4_G(exception_mode) == ExceptionMode::Rethrow) {
    if (in_unregistered_nested_loop()) {
      park_exception(&exception, origin);  // see error.h: pending would not propagate yet
    } else {
      // Hand the object back to the engine (takes our reference) and unwind the
      // C side as far as we can: stop the loops so run() returns to PHP.
      zend_throw_exception_object(&exception);
    }
    quit_running_loops();
    return true;
  }
  if (!reported) log_uncaught(&exception, origin);
  zval_ptr_dtor(&exception);
  return true;
}

}  // namespace phpgtk
