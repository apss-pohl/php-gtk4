// Gtk4\GObject methods, the static Gtk4\Gtk and Gtk4\GLib classes.
#include "php_gtk4.h"
#include "core/callback.h"
#include "core/classes.h"
#include "core/error.h"
#include "core/gsignal.h"
#include "core/marshal.h"
#include "core/object.h"
#include "core/teardown.h"

using namespace phpgtk;

// ---------------------------------------------------------------- GObject

/**
 * Gtk4\GObject::connect(string $signal, callable $handler): int
 *
 * Connect a handler to a signal (optionally detailed, e.g. "notify::title").
 */
ZEND_METHOD(Gtk4_GObject, connect) {
  signal_connect_method(INTERNAL_FUNCTION_PARAM_PASSTHRU, false);
}
/**
 * Gtk4\GObject::connect_after(string $signal, callable $handler): int
 *
 * Same as {@see connect()} but the handler runs after the default class handler.
 */
ZEND_METHOD(Gtk4_GObject, connect_after) {
  signal_connect_method(INTERNAL_FUNCTION_PARAM_PASSTHRU, true);
}

/**
 * Gtk4\GObject::emit(string $signal, mixed ...$args): mixed
 *
 * Emit a signal on this object with the given arguments (converted to the signal's parameter
 * types) and return the signal's return value, if any.
 */
ZEND_METHOD(Gtk4_GObject, emit) {
  signal_emit_method(INTERNAL_FUNCTION_PARAM_PASSTHRU);
}

/**
 * Gtk4\GObject::handler_disconnect(int $handlerId): void
 *
 * Disconnect a handler previously returned by connect(). No-op if already disconnected.
 */
ZEND_METHOD(Gtk4_GObject, handler_disconnect) {
  zend_long id;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_LONG(id)
  ZEND_PARSE_PARAMETERS_END();
  Object *self = object_from_zval(ZEND_THIS);
  if (self->obj == nullptr) return;  // nothing to disconnect from
  if (id > 0 && g_signal_handler_is_connected(self->obj, static_cast<gulong>(id))) {
    g_signal_handler_disconnect(self->obj, static_cast<gulong>(id));
  }
}

// Look up a GObject property by name or throw ValueError (nullptr returned).
static GParamSpec *require_property(GObject *obj, zend_string *name) {
  GParamSpec *spec = g_object_class_find_property(G_OBJECT_GET_CLASS(obj), ZSTR_VAL(name));
  if (spec == nullptr) {
    zend_value_error("no property '%s' on %s", ZSTR_VAL(name), G_OBJECT_TYPE_NAME(obj));
  }
  return spec;
}

/**
 * Gtk4\GObject::get_property(string $name): mixed
 *
 * Read a GObject property by name, converted to the matching PHP type.
 */
ZEND_METHOD(Gtk4_GObject, get_property) {
  zend_string *name;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(name)
  ZEND_PARSE_PARAMETERS_END();
  GObject *obj = PHPGTK_SELF(GObject, G_TYPE_OBJECT);
  GParamSpec *spec = require_property(obj, name);
  if (spec == nullptr) RETURN_THROWS();
  GValue v = G_VALUE_INIT;
  g_value_init(&v, spec->value_type);
  g_object_get_property(obj, spec->name, &v);
  to_php(&v, return_value);
  g_value_unset(&v);
}

/**
 * Gtk4\GObject::set_property(string $name, mixed $value): void
 *
 * Write a GObject property by name; the value is converted to the property's GType.
 */
ZEND_METHOD(Gtk4_GObject, set_property) {
  zend_string *name;
  zval *value;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_STR(name)
  Z_PARAM_ZVAL(value)
  ZEND_PARSE_PARAMETERS_END();
  GObject *obj = PHPGTK_SELF(GObject, G_TYPE_OBJECT);
  GParamSpec *spec = require_property(obj, name);
  if (spec == nullptr) RETURN_THROWS();
  GValue v = G_VALUE_INIT;
  if (!to_gvalue(value, spec->value_type, &v)) RETURN_THROWS();
  g_object_set_property(obj, spec->name, &v);
  g_value_unset(&v);
}

// ---------------------------------------------------------------- Gtk (static)

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

// ---------------------------------------------------------------- GLib (static)

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
 * static Gtk4\GLib::timeout_add(int $intervalMs, callable $callback): int
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
 * static Gtk4\GLib::source_remove(int $sourceId): bool
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
  RETURN_TRUE;
}
