#include "error.h"

#include <array>

namespace phpgtk {

static zval handler;  // IS_UNDEF when none

void set_exception_handler(zval *h) {
  if (!Z_ISUNDEF(handler)) zval_ptr_dtor(&handler);
  if (h == nullptr || Z_TYPE_P(h) == IS_NULL) {
    ZVAL_UNDEF(&handler);
  } else {
    ZVAL_COPY(&handler, h);
  }
}

bool has_exception_handler() {
  return !Z_ISUNDEF(handler);
}

void exception_handler_shutdown() {
  set_exception_handler(nullptr);
}

static void log_uncaught(zval *exception, const char *origin) {
  zval rv;
  zval *msg =
      zend_read_property_ex(Z_OBJCE_P(exception), Z_OBJ_P(exception), ZSTR_KNOWN(ZEND_STR_MESSAGE),
                            /* silent */ true, &rv);
  g_critical("php-gtk4: uncaught %s in '%s' handler: %s", ZSTR_VAL(Z_OBJCE_P(exception)->name),
             origin, msg != nullptr && Z_TYPE_P(msg) == IS_STRING ? Z_STRVAL_P(msg) : "");
}

bool report_pending_exception(const char *origin_c) {
  if (EG(exception) == nullptr) return false;
  const char *origin = origin_c != nullptr ? origin_c : "";

  zval exception;
  ZVAL_OBJ_COPY(&exception, EG(exception));
  zend_clear_exception();  // Zend would refuse to run the handler otherwise

  bool reported = false;
  if (has_exception_handler()) {
    std::array<zval, 2> args{};
    ZVAL_COPY(args.data(), &exception);
    ZVAL_STRING(&args[1], origin);
    zval retval;
    ZVAL_UNDEF(&retval);
    zend_fcall_info fci;
    zend_fcall_info_cache fcc;
    if (zend_fcall_info_init(&handler, 0, &fci, &fcc, nullptr, nullptr) == SUCCESS) {
      fci.retval = &retval;
      fci.params = args.data();
      fci.param_count = 2;
      zend_call_function(&fci, &fcc);
      zval_ptr_dtor(&retval);
      if (EG(exception) != nullptr) {
        // The handler itself failed; it must not escape into GLib either.
        g_critical(
            "php-gtk4: Gtk::set_exception_handler() callback threw %s while reporting from '%s'",
            ZSTR_VAL(EG(exception)->ce->name), origin);
        zend_clear_exception();
      } else {
        reported = true;
      }
    }
    zval_ptr_dtor(args.data());
    zval_ptr_dtor(&args[1]);
  }
  if (!reported) log_uncaught(&exception, origin);
  zval_ptr_dtor(&exception);
  return true;
}

}  // namespace phpgtk
