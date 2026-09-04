#include "gsignal.h"
#include "error.h"
#include "diagnostics.h"
#include "marshal.h"
#include "object.h"
#include "teardown.h"

namespace phpgtk {

struct PhpClosure {
  GClosure closure;  // must be first
  zval callable;     // keeps the handler alive
  zend_string *signal_name;
};

namespace {

// GClosure finalize notifier: untrack, release the callable and the signal name.
void closure_finalize(gpointer, GClosure *c) {
  auto *pc = reinterpret_cast<PhpClosure *>(c);
  teardown_untrack_closure(c);
  zval_ptr_dtor(&pc->callable);
  zend_string_release(pc->signal_name);
}

// GClosure marshaller: GValue params -> zvals, call the handler, convert its return value.
void closure_marshal(GClosure *c, GValue *return_value, guint n_params, const GValue *params,
                     gpointer, gpointer) {
  auto *pc = reinterpret_cast<PhpClosure *>(c);
  const char *origin = ZSTR_VAL(pc->signal_name);

  // Rethrow mode: an earlier callback of this emission already threw; Zend
  // would not run this one anyway, and we must not touch the pending state.
  if (EG(exception) != nullptr) return;

  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  if (zend_fcall_info_init(&pc->callable, 0, &fci, &fcc, nullptr, nullptr) != SUCCESS) {
    diagnostic("php-gtk4: handler for '%s' is no longer callable", origin);
    return;
  }

  auto *args = static_cast<zval *>(safe_emalloc(n_params, sizeof(zval), 0));
  uint32_t filled = 0;
  for (guint i = 0; i < n_params; i++) {
    to_php(&params[i], &args[i]);
    filled++;
    if (EG(exception) != nullptr) break;  // unsupported type: reported below
  }
  if (EG(exception) == nullptr) {
    zval retval;
    ZVAL_UNDEF(&retval);
    fci.retval = &retval;
    fci.params = args;
    fci.param_count = n_params;
    zend_call_function(&fci, &fcc);

    if (EG(exception) == nullptr && return_value != nullptr && !Z_ISUNDEF(retval) &&
        G_VALUE_TYPE(return_value) != G_TYPE_INVALID) {
      GValue tmp = G_VALUE_INIT;
      if (to_gvalue(&retval, G_VALUE_TYPE(return_value), &tmp)) {
        g_value_copy(&tmp, return_value);
        g_value_unset(&tmp);
      }
    }
    zval_ptr_dtor(&retval);
  }
  for (uint32_t i = 0; i < filled; i++) zval_ptr_dtor(&args[i]);
  efree(args);

  // Whatever happened above - marshalling, the handler, the return conversion -
  // goes through the exception policy; nothing unwinds into GLib.
  report_pending_exception(origin);
}
}  // namespace

// A floating GClosure around a PHP callable, marshalled like a signal handler. connect() sinks it
// into the instance; GtkBuilder's scope hands it to GTK, which does the same.
GClosure *php_closure_new(zval *callable, zend_string *origin) {
  auto *pc = reinterpret_cast<PhpClosure *>(g_closure_new_simple(sizeof(PhpClosure), nullptr));
  ZVAL_COPY(&pc->callable, callable);
  pc->signal_name = zend_string_copy(origin);
  g_closure_add_finalize_notifier(&pc->closure, nullptr, closure_finalize);
  g_closure_set_marshal(&pc->closure, closure_marshal);
  return &pc->closure;
}

// Shared body of GObject::connect() / connect_after().
void signal_connect_method(INTERNAL_FUNCTION_PARAMETERS, bool after) {
  zend_string *signal;
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;

  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_STR(signal)
  Z_PARAM_FUNC(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();

  GObject *obj = self_object(execute_data, G_TYPE_OBJECT, after ? "connect_after" : "connect");
  if (obj == nullptr) RETURN_THROWS();
  // A name that ends at a NUL would connect to a different, existing signal without saying so.
  if (!check_utf8(signal, 1)) RETURN_THROWS();

  guint signal_id = 0;
  GQuark detail = 0;
  if (!g_signal_parse_name(ZSTR_VAL(signal), G_OBJECT_TYPE(obj), &signal_id, &detail, TRUE)) {
    zend_argument_value_error(1, "unknown signal '%s' on %s", ZSTR_VAL(signal),
                              G_OBJECT_TYPE_NAME(obj));
    RETURN_THROWS();
  }

  GClosure *closure = php_closure_new(&fci.function_name, signal);
  gulong id = g_signal_connect_closure_by_id(obj, signal_id, detail, closure, after);
  if (id == 0) {  // GLib refused (it warned): the closure is ours to drop, nothing to track
    g_closure_sink(g_closure_ref(closure));
    g_closure_unref(closure);
    zend_argument_value_error(1, "could not connect to signal '%s' on %s", ZSTR_VAL(signal),
                              G_OBJECT_TYPE_NAME(obj));
    RETURN_THROWS();
  }
  teardown_track_closure(closure, obj, id);
  RETURN_LONG(static_cast<zend_long>(id));
}

// Body of GObject::emit(): validates the signal, converts the arguments, g_signal_emitv().
void signal_emit_method(INTERNAL_FUNCTION_PARAMETERS) {
  zend_string *signal;
  zval *args = nullptr;
  uint32_t argc = 0;
  ZEND_PARSE_PARAMETERS_START(1, -1)
  Z_PARAM_STR(signal)
  Z_PARAM_VARIADIC('*', args, argc)
  ZEND_PARSE_PARAMETERS_END();

  GObject *obj = phpgtk::self_object(execute_data, G_TYPE_OBJECT, "emit");
  if (obj == nullptr) RETURN_THROWS();
  if (!check_utf8(signal, 1)) RETURN_THROWS();

  guint signal_id = 0;
  GQuark detail = 0;
  if (!g_signal_parse_name(ZSTR_VAL(signal), G_OBJECT_TYPE(obj), &signal_id, &detail, TRUE)) {
    zend_argument_value_error(1, "unknown signal '%s' on %s", ZSTR_VAL(signal),
                              G_OBJECT_TYPE_NAME(obj));
    RETURN_THROWS();
  }
  GSignalQuery query;
  g_signal_query(signal_id, &query);
  if (query.n_params != argc) {
    zend_argument_count_error("signal '%s' takes %u argument(s), %u given", ZSTR_VAL(signal),
                              query.n_params, argc);
    RETURN_THROWS();
  }

  // instance + params
  auto *values = static_cast<GValue *>(safe_emalloc(argc + 1, sizeof(GValue), 0));
  // NOLINTNEXTLINE(readability-math-missing-parentheses) G_VALUE_INIT expands to a brace list
  for (uint32_t i = 0; i <= argc; i++) values[i] = G_VALUE_INIT;
  g_value_init(&values[0], G_OBJECT_TYPE(obj));
  g_value_set_object(&values[0], obj);
  bool ok = true;
  for (uint32_t i = 0; i < argc && ok; i++) {
    ok = phpgtk::to_gvalue(&args[i], query.param_types[i] & ~G_SIGNAL_TYPE_STATIC_SCOPE,
                           &values[i + 1]);
  }
  if (ok) {
    GValue ret = G_VALUE_INIT;
    const GType rtype = query.return_type & ~G_SIGNAL_TYPE_STATIC_SCOPE;
    if (rtype != G_TYPE_NONE) g_value_init(&ret, rtype);
    g_signal_emitv(values, signal_id, detail, rtype != G_TYPE_NONE ? &ret : nullptr);
    if (rtype != G_TYPE_NONE) {
      if (EG(exception) == nullptr) phpgtk::to_php(&ret, return_value);
      g_value_unset(&ret);
    }
  }
  for (uint32_t i = 0; i <= argc; i++) {
    if (G_IS_VALUE(&values[i])) g_value_unset(&values[i]);
  }
  efree(values);
  if (EG(exception) != nullptr) RETURN_THROWS();  // Rethrow mode, or a conversion error
}

}  // namespace phpgtk
