// Gtk4\Gtk: static entry points (init, exception handler / mode).
#include "php_gtk4.h"
#include "core/error.h"
#include "core/globals.h"
#include "core/mainloop.h"
#include "core/object.h"

#include <cstring>
#include <string>

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
 *
 * What a Throwable escaping a handler does: `Log` (report, GTK continues) or `Rethrow` (stop
 * loops, propagate).
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
 *
 * The current mode; `Log` by default.
 */
ZEND_METHOD(Gtk4_Gtk, get_exception_mode) {
  ZEND_PARSE_PARAMETERS_NONE();
  const char *name = exception_mode() == ExceptionMode::Rethrow ? "Rethrow" : "Log";
  zend_object *c = zend_enum_get_case_cstr(ce_ExceptionMode, name);
  RETURN_OBJ_COPY(c);
}

/**
 * static Gtk4\Gtk::add_provider_for_display(GdkDisplay $display, GtkStyleProvider $provider, int
 * $priority = GtkStyleProviderPriority::APPLICATION): void
 *
 * Add a style provider (a {@see GtkCssProvider}) to every widget on $display
 * (gtk_style_context_add_provider_for_display - the function outlived the GtkStyleContext class it
 * is named after).
 */
ZEND_METHOD(Gtk4_Gtk, add_provider_for_display) {
  zval *display = nullptr;
  zval *provider = nullptr;
  zend_long priority = GTK_STYLE_PROVIDER_PRIORITY_APPLICATION;
  ZEND_PARSE_PARAMETERS_START(2, 3)
  Z_PARAM_OBJECT_OF_CLASS(display, class_for_gtype(GDK_TYPE_DISPLAY))
  Z_PARAM_OBJECT_OF_CLASS(provider, class_for_gtype(GTK_TYPE_STYLE_PROVIDER))
  Z_PARAM_OPTIONAL
  Z_PARAM_LONG(priority)
  ZEND_PARSE_PARAMETERS_END();
  if (priority < 0) {
    zend_argument_value_error(3, "must be greater than or equal to 0");
    RETURN_THROWS();
  }
  GObject *display_o = unwrap(display, GDK_TYPE_DISPLAY);
  if (display_o == nullptr) RETURN_THROWS();
  GObject *provider_o = unwrap(provider, GTK_TYPE_STYLE_PROVIDER);
  if (provider_o == nullptr) RETURN_THROWS();
  gtk_style_context_add_provider_for_display(GDK_DISPLAY(display_o), GTK_STYLE_PROVIDER(provider_o),
                                             static_cast<guint>(priority));
}

/**
 * static Gtk4\Gtk::remove_provider_for_display(GdkDisplay $display, GtkStyleProvider $provider):
 * void
 *
 * Detach a provider added with {@see add_provider_for_display()}; unknown providers are ignored.
 */
ZEND_METHOD(Gtk4_Gtk, remove_provider_for_display) {
  zval *display = nullptr;
  zval *provider = nullptr;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_OBJECT_OF_CLASS(display, class_for_gtype(GDK_TYPE_DISPLAY))
  Z_PARAM_OBJECT_OF_CLASS(provider, class_for_gtype(GTK_TYPE_STYLE_PROVIDER))
  ZEND_PARSE_PARAMETERS_END();
  GObject *display_o = unwrap(display, GDK_TYPE_DISPLAY);
  if (display_o == nullptr) RETURN_THROWS();
  GObject *provider_o = unwrap(provider, GTK_TYPE_STYLE_PROVIDER);
  if (provider_o == nullptr) RETURN_THROWS();
  gtk_style_context_remove_provider_for_display(GDK_DISPLAY(display_o),
                                                GTK_STYLE_PROVIDER(provider_o));
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

/**
 * static Gtk4\Gtk::testing_run_dispose(GObject $object): void
 *
 * Test builds only: run `g_object_run_dispose()` on $object from C while PHP still holds it, the
 * way GTK guts a widget that a C owner destroys. The handle turns *disposed*: method calls and
 * passing it as an argument throw an `Error` from then on.
 */
ZEND_METHOD(Gtk4_Gtk, testing_run_dispose) {
  zval *object;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(object, class_for_gtype(G_TYPE_OBJECT))
  ZEND_PARSE_PARAMETERS_END();
  GObject *obj = unwrap(object, G_TYPE_OBJECT);
  if (obj == nullptr) RETURN_THROWS();
  g_object_run_dispose(obj);
}

namespace {

// GLogWriterFunc: record CRITICAL and WARNING text so a test can assert on it, and still print
// it. GLib's own preconditions are how GTK reports "PHP handed me something I refuse", and they
// only ever went to stderr - 135 distinct ones had accumulated unnoticed. No PHP runs here: the
// text is buffered and read later, because a log can arrive inside any GTK frame.
GLogWriterOutput capture_writer(GLogLevelFlags level, const GLogField *fields, gsize n_fields,
                                gpointer) {
  // Only on the thread that owns GTK: this writer is process-wide, and under ZTS the module
  // globals below belong to one request on one thread. GTK logs from its own worker threads.
  if (on_gui_thread() && GTK4_G(capturing_logs) &&
      (static_cast<unsigned>(level) & (static_cast<unsigned>(G_LOG_LEVEL_CRITICAL) |
                                       static_cast<unsigned>(G_LOG_LEVEL_WARNING))) != 0U) {
    const char *domain = nullptr;
    const char *message = nullptr;
    for (gsize i = 0; i < n_fields; i++) {
      if (strcmp(fields[i].key, "GLIB_DOMAIN") == 0) {
        domain = static_cast<const char *>(fields[i].value);
      } else if (strcmp(fields[i].key, "MESSAGE") == 0) {
        message = static_cast<const char *>(fields[i].value);
      }
    }
    if (message != nullptr) {
      GTK4_G(captured_logs)
          .emplace_back(std::string(domain != nullptr ? domain : "GLib") + ": " + message);
    }
  }
  return g_log_writer_default(level, fields, n_fields, nullptr);
}

}  // namespace

/**
 * static Gtk4\Gtk::testing_capture_logs(bool $capture): void
 *
 * Test builds only: start or stop recording GLib CRITICAL/WARNING messages. They still reach
 * stderr; this only keeps a copy so a test can fail on one instead of letting it scroll past.
 */
ZEND_METHOD(Gtk4_Gtk, testing_capture_logs) {
  bool capture = false;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_BOOL(capture)
  ZEND_PARSE_PARAMETERS_END();
  // GLib allows exactly one writer per process, so install it once and let the flag decide
  // whether it records; a second g_log_set_writer_func() is a fatal GLib error.
  static bool installed = false;
  if (capture && !installed) {
    g_log_set_writer_func(capture_writer, nullptr, nullptr);
    installed = true;
  }
  GTK4_G(capturing_logs) = capture;
  GTK4_G(captured_logs).clear();
}

/**
 * static Gtk4\Gtk::testing_taken_logs(): array
 *
 * Test builds only: the GLib CRITICAL/WARNING messages recorded since the last call, and clear
 * them.
 */
ZEND_METHOD(Gtk4_Gtk, testing_taken_logs) {
  ZEND_PARSE_PARAMETERS_NONE();
  array_init(return_value);
  for (const std::string &line : GTK4_G(captured_logs)) {
    add_next_index_stringl(return_value, line.c_str(), line.size());
  }
  GTK4_G(captured_logs).clear();
}
#endif
