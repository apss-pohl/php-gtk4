// Gtk4\GLib: static helpers around the default main context (idle/timeout sources).
#include "php_gtk4.h"
#include "core/callback.h"
#include "core/error.h"
#include "core/globals.h"
#include "core/teardown.h"

using namespace phpgtk;

// GDestroyNotify for sources: untrack, then release the callable.
static void source_free(gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  teardown_untrack_source(cb->source_id);
  callback_free(cb);
}

// GSourceFunc trampoline: callback(): bool -> G_SOURCE_CONTINUE / G_SOURCE_REMOVE.
static gboolean source_dispatch(gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  zval retval;
  const bool ok = callback_invoke(cb, 0, nullptr, &retval);
  gboolean keep = G_SOURCE_REMOVE;
  if (ok && !Z_ISUNDEF(retval)) keep = zend_is_true(&retval) ? G_SOURCE_CONTINUE : G_SOURCE_REMOVE;
  zval_ptr_dtor(&retval);
  return keep;
}

/**
 * static Gtk4\GLib::idle_add(callable $callback): int
 */
ZEND_METHOD(Gtk4_GLib, idle_add) {
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_FUNC(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  Callback *cb = callback_new(&fci.function_name, "GLib::idle_add");
  guint id = g_idle_add_full(G_PRIORITY_DEFAULT_IDLE, source_dispatch, cb, source_free);
  cb->source_id = id;
  teardown_track_source(id);
  RETURN_LONG(id);
}

/**
 * static Gtk4\GLib::timeout_add(int $interval_ms, callable $callback): int
 */
ZEND_METHOD(Gtk4_GLib, timeout_add) {
  zend_long interval;
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_LONG(interval)
  Z_PARAM_FUNC(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  if (interval < 0) {
    zend_argument_value_error(1, "must be greater than or equal to 0");
    RETURN_THROWS();
  }
  Callback *cb = callback_new(&fci.function_name, "GLib::timeout_add");
  guint id = g_timeout_add_full(G_PRIORITY_DEFAULT, static_cast<guint>(interval), source_dispatch,
                                cb, source_free);
  cb->source_id = id;
  teardown_track_source(id);
  RETURN_LONG(id);
}

/**
 * static Gtk4\GLib::source_remove(int $source_id): bool
 *
 * Remove an idle/timeout source; false if it was already gone.
 */
ZEND_METHOD(Gtk4_GLib, source_remove) {
  zend_long id;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_LONG(id)
  ZEND_PARSE_PARAMETERS_END();
  if (id <= 0) RETURN_FALSE;
  GSource *source = g_main_context_find_source_by_id(nullptr, static_cast<guint>(id));
  if (source == nullptr) RETURN_FALSE;
  g_source_destroy(source);
  callback_drain();
  RETURN_TRUE;
}

/**
 * static Gtk4\GLib::main_context_iteration(bool $may_block = false): bool
 *
 * Run one iteration of the default main context (g_main_context_iteration): dispatch what is
 * ready, optionally blocking until something is. Returns true if any source was dispatched. Lets a
 * script pump events without handing control to run(); in {@see ExceptionMode::Rethrow} a
 * Throwable raised by a dispatched callback propagates from this call.
 */
ZEND_METHOD(Gtk4_GLib, main_context_iteration) {
  bool may_block = false;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_BOOL(may_block)
  ZEND_PARSE_PARAMETERS_END();
  if (!assert_gui_thread("GLib::main_context_iteration()")) RETURN_THROWS();
  const gboolean dispatched = g_main_context_iteration(nullptr, may_block ? TRUE : FALSE);
  callback_drain();
  // Rethrow mode: this call is a boundary back to PHP (see core/error.h).
  if (rethrow_parked_exception() || EG(exception) != nullptr) RETURN_THROWS();
  RETURN_BOOL(dispatched);
}
