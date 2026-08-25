#include "error.h"
#include <glib.h>

namespace phpgtk {

// Heap-allocated and never freed: a static Php::Value would be destroyed after
// Zend has shut down. Reassigning releases the previous callable while PHP is
// still alive.
static Php::Value &handler_slot() {
  static Php::Value *slot = new Php::Value();
  return *slot;
}

void set_exception_handler(const Php::Value &handler) {
  handler_slot() = handler;
}

// Inspects only the zval type - must not call into PHP.
bool has_exception_handler() {
  return !handler_slot().isNull();
}

void report_callback_exception(const std::string &message, long code, const char *context) {
  const char *origin = context ? context : "";
  bool reported = false;

  if (has_exception_handler()) {
    try {
      Php::call("call_user_func", handler_slot(), message, std::string(origin),
                static_cast<int64_t>(code));
      reported = true;
    } catch (...) {
      g_critical("php-gtk4: exception handler itself failed while reporting from '%s'", origin);
    }
  }
  if (!reported) {
    g_critical("php-gtk4: uncaught exception in '%s' handler: %s", origin, message.c_str());
  }
}

}  // namespace phpgtk
