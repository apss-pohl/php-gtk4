// Gtk4\GLib: static helpers around the default main context (idle/timeout sources).
#include "php_gtk4.h"
#include "core/callback.h"
#include "core/error.h"
#include "core/globals.h"
#include "core/mainloop.h"
#include "core/teardown.h"
#include "php_network.h"  // php_socket_t
#include "php_streams.h"

#include <array>

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

// An I/O watch: the callable plus the stream it watches (handed back to the callback).
struct IoWatch {
  Callback *cb;
  zval stream;
};

// GDestroyNotify for I/O watches: untrack, park the stream and the callable.
static void io_watch_free(gpointer data) {
  auto *w = static_cast<IoWatch *>(data);
  teardown_untrack_source(w->cb->source_id);
  callback_park(&w->stream);
  callback_free(w->cb);
  efree(w);
}

// GIOFunc trampoline: callback($stream, int $condition): bool -> keep / remove.
static gboolean io_watch_dispatch(GIOChannel *, GIOCondition condition, gpointer data) {
  auto *w = static_cast<IoWatch *>(data);
  std::array<zval, 2> args{};
  ZVAL_COPY(args.data(), &w->stream);
  ZVAL_LONG(&args[1], static_cast<zend_long>(condition));
  zval retval;
  const bool ok = callback_invoke(w->cb, 2, args.data(), &retval);
  gboolean keep = G_SOURCE_REMOVE;
  if (ok && !Z_ISUNDEF(retval)) keep = zend_is_true(&retval) ? G_SOURCE_CONTINUE : G_SOURCE_REMOVE;
  zval_ptr_dtor(&retval);
  zval_ptr_dtor(args.data());
  return keep;
}

/**
 * static Gtk4\GLib::io_add_watch(mixed $stream, int $condition, callable $callback): int
 *
 * Watch a socket stream on the default main context: `$callback($stream, int $condition)` runs
 * whenever one of the {@see GIOCondition} bits in $condition is met, and keeps the watch by
 * returning true. This is how a socket joins the GTK main loop instead of being polled between
 * manual iterations. $stream is a stream resource over a socket (`stream_socket_client()`,
 * `stream_socket_pair()`, or an ext-sockets `\Socket` through `socket_export_stream()`); the
 * descriptor stays PHP's - closing the stream ends the watch with a `HUP`/`NVAL` condition.
 */
ZEND_METHOD(Gtk4_GLib, io_add_watch) {
  zval *stream_zv;
  zend_long condition;
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(3, 3)
  Z_PARAM_ZVAL(stream_zv)
  Z_PARAM_LONG(condition)
  Z_PARAM_FUNC(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  if (Z_TYPE_P(stream_zv) != IS_RESOURCE) {
    zend_argument_type_error(1, "must be a stream resource, %s given",
                             zend_zval_value_name(stream_zv));
    RETURN_THROWS();
  }
  php_stream *stream = nullptr;
  php_stream_from_zval_no_verify(stream, stream_zv);
  if (stream == nullptr) {
    zend_argument_value_error(1, "is a closed stream");
    RETURN_THROWS();
  }
  if (condition <= 0 ||
      (condition & ~(G_IO_IN | G_IO_OUT | G_IO_PRI | G_IO_ERR | G_IO_HUP | G_IO_NVAL)) != 0) {
    zend_argument_value_error(2, "must be a combination of GIOCondition bits");
    RETURN_THROWS();
  }
  php_socket_t fd = 0;
  if (php_stream_cast(stream, PHP_STREAM_AS_SOCKETD | PHP_STREAM_CAST_INTERNAL,
                      reinterpret_cast<void **>(&fd), 0) != SUCCESS) {
    zend_argument_value_error(1, "must be a stream over a socket descriptor");
    RETURN_THROWS();
  }
  // The GIOChannel is a view on PHP's descriptor (close_on_unref stays off): PHP closes it.
  // The one platform switch outside pin_gtk_library(): a Windows SOCKET is not a C fd.
#ifdef G_OS_WIN32
  GIOChannel *channel = g_io_channel_win32_new_socket(static_cast<gint>(fd));
#else
  GIOChannel *channel = g_io_channel_unix_new(static_cast<gint>(fd));
#endif
  g_io_channel_set_close_on_unref(channel, FALSE);
  // NOLINTNEXTLINE(bugprone-implicit-widening-of-multiplication-result) Zend emalloc macro
  auto *w = static_cast<IoWatch *>(emalloc(sizeof(IoWatch)));
  w->cb = callback_new(&fci.function_name, "GLib::io_add_watch");
  ZVAL_COPY(&w->stream, stream_zv);
  GSource *source = g_io_create_watch(channel, static_cast<GIOCondition>(condition));
  g_io_channel_unref(channel);  // the source holds it
  g_source_set_callback(source, G_SOURCE_FUNC(io_watch_dispatch), w, io_watch_free);
  const guint id = g_source_attach(source, nullptr);
  g_source_unref(source);
  w->cb->source_id = id;
  teardown_track_source(id);
  RETURN_LONG(id);
}

/**
 * static Gtk4\GLib::source_remove(int $source_id): bool
 *
 * Remove an idle/timeout/I/O source; false if it was already gone.
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
