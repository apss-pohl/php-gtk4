#include "gsignal.h"
#include "error.h"
#include "diagnostics.h"
#include "marshal.h"
#include "object.h"
#include "teardown.h"

#include <array>
#include <vector>

namespace phpgtk {

struct PhpClosure {
  GClosure closure;  // must be first
  zval callable;     // keeps the handler alive
  zend_string *signal_name;
  zend_fcall_info_cache fcc;  // the callable resolved once (php_closure_new), see there
  bool resolved;
};

namespace {

// GClosure finalize notifier: untrack, release the callable and the signal name.
void closure_finalize(gpointer, GClosure *c) {
  auto *pc = reinterpret_cast<PhpClosure *>(c);
  teardown_untrack_closure(c);
  zval_ptr_dtor(&pc->callable);
  zend_string_release(pc->signal_name);
}

// The handler's arguments: on the stack for the signals there are (GTK's widest takes five),
// on the heap for anything wider.
constexpr guint inline_args = 8;

// GClosure marshaller: GValue params -> zvals, call the handler, convert its return value.
void closure_marshal(GClosure *c, GValue *return_value, guint n_params, const GValue *params,
                     gpointer, gpointer) {
  auto *pc = reinterpret_cast<PhpClosure *>(c);
  const char *origin = ZSTR_VAL(pc->signal_name);

  // Rethrow mode: an earlier callback of this emission already threw; Zend
  // would not run this one anyway, and we must not touch the pending state.
  if (EG(exception) != nullptr) return;

  zend_fcall_info_cache per_call;
  if (!pc->resolved) {  // a __call trampoline (php_closure_new): resolve it for this call only
    zend_fcall_info fci;
    if (zend_fcall_info_init(&pc->callable, 0, &fci, &per_call, nullptr, nullptr) != SUCCESS) {
      diagnostic("php-gtk4: handler for '%s' is no longer callable", origin);
      return;
    }
  }
  zend_fcall_info_cache *fcc = pc->resolved ? &pc->fcc : &per_call;

  std::array<zval, inline_args> inline_storage{};
  zval *args = n_params <= inline_args
                   ? inline_storage.data()
                   : static_cast<zval *>(safe_emalloc(n_params, sizeof(zval), 0));
  uint32_t filled = 0;
  for (guint i = 0; i < n_params; i++) {
    to_php(&params[i], &args[i]);
    filled++;
    if (EG(exception) != nullptr) break;  // unsupported type: reported below
  }
  if (EG(exception) == nullptr) {
    zval retval;
    ZVAL_UNDEF(&retval);
    zend_call_known_fcc(fcc, &retval, n_params, args, nullptr);

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
  if (args != inline_storage.data()) efree(args);

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
  // Resolve the callable now, not per emission. The cache points at what `callable` already
  // keeps alive (a closure, its bound object, a method's class) for exactly as long as the
  // closure lives, so it takes no references of its own: zend_fcc_addref() would add one Zend
  // cannot see - a handler bound to an object that owns the emitter would then never be
  // collected. A __call trampoline is per-call scratch and is resolved at each emission instead.
  zend_fcall_info fci;
  pc->fcc = empty_fcall_info_cache;
  pc->resolved = zend_fcall_info_init(callable, 0, &fci, &pc->fcc, nullptr, nullptr) == SUCCESS &&
                 (pc->fcc.function_handler->common.fn_flags & ZEND_ACC_CALL_VIA_TRAMPOLINE) == 0;
  g_closure_add_finalize_notifier(&pc->closure, nullptr, closure_finalize);
  g_closure_set_marshal(&pc->closure, closure_marshal);
  return &pc->closure;
}

// RSHUTDOWN: the callable goes now, the GClosure shell whenever its last holder drops it.
void php_closure_release(GClosure *closure) {
  auto *pc = reinterpret_cast<PhpClosure *>(closure);
  if (Z_ISUNDEF(pc->callable)) return;
  zval callable;
  ZVAL_COPY_VALUE(&callable, &pc->callable);
  ZVAL_UNDEF(&pc->callable);
  zval_ptr_dtor(&callable);
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

  // A signal whose class handler takes (text, length) - GtkTextBuffer::insert-text,
  // GtkEditable::insert-text - reads `length` bytes of `text` without looking: the methods bound
  // that pair (check_text_len) and so does emit(), or a length past the string was a heap
  // over-read inside GTK's own handler.
  for (uint32_t i = 1; i < argc; i++) {
    const GType prev = G_TYPE_FUNDAMENTAL(query.param_types[i - 1] & ~G_SIGNAL_TYPE_STATIC_SCOPE);
    const GType cur = G_TYPE_FUNDAMENTAL(query.param_types[i] & ~G_SIGNAL_TYPE_STATIC_SCOPE);
    const bool int_after_string =
        prev == G_TYPE_STRING && (cur == G_TYPE_INT || cur == G_TYPE_LONG || cur == G_TYPE_INT64);
    if (!int_after_string || Z_TYPE(args[i - 1]) != IS_STRING || Z_TYPE(args[i]) != IS_LONG)
      continue;
    const auto size = static_cast<zend_long>(Z_STRLEN(args[i - 1]));
    const zend_long len = Z_LVAL(args[i]);
    if (len == -1 || (len >= 0 && len <= size)) continue;
    zend_argument_value_error(i + 2,
                              "must be -1 or between 0 and the byte length of the string before "
                              "it (" ZEND_LONG_FMT ")",
                              size);
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

// Body of GObject::list_signals(): g_signal_list_ids() over the type chain and the interfaces.
void signal_list_method(INTERNAL_FUNCTION_PARAMETERS) {
  ZEND_PARSE_PARAMETERS_NONE();
  GObject *obj = phpgtk::self_object(execute_data, G_TYPE_OBJECT, "list_signals");
  if (obj == nullptr) RETURN_THROWS();
  std::vector<GType> types;
  for (GType t = G_OBJECT_TYPE(obj); t != 0; t = g_type_parent(t)) types.push_back(t);
  guint n_ifaces = 0;
  GType *ifaces = g_type_interfaces(G_OBJECT_TYPE(obj), &n_ifaces);
  for (guint i = 0; i < n_ifaces; i++) types.push_back(ifaces[i]);
  g_free(ifaces);
  array_init(return_value);
  for (const GType t : types) {
    // g_signal_list_ids() wants the type's class (or default vtable) loaded; the instance's own
    // chain is, an interface's default vtable may not be yet
    const bool iface = G_TYPE_IS_INTERFACE(t) == TRUE;
    gpointer klass = iface ? g_type_default_interface_ref(t) : g_type_class_ref(t);
    guint n_ids = 0;
    guint *ids = g_signal_list_ids(t, &n_ids);
    for (guint i = 0; i < n_ids; i++) {
      GSignalQuery q;
      g_signal_query(ids[i], &q);
      zval entry;
      array_init(&entry);
      zval params;
      array_init(&params);
      for (guint p = 0; p < q.n_params; p++) {
        add_next_index_string(&params, g_type_name(q.param_types[p] & ~G_SIGNAL_TYPE_STATIC_SCOPE));
      }
      add_assoc_zval(&entry, "params", &params);
      const GType ret = q.return_type & ~G_SIGNAL_TYPE_STATIC_SCOPE;
      if (ret == G_TYPE_NONE) {
        add_assoc_null(&entry, "return");
      } else {
        add_assoc_string(&entry, "return", g_type_name(ret));
      }
      add_assoc_bool(&entry, "action", (q.signal_flags & G_SIGNAL_ACTION) != 0);
      add_assoc_bool(&entry, "detailed", (q.signal_flags & G_SIGNAL_DETAILED) != 0);
      add_assoc_zval(return_value, q.signal_name, &entry);
    }
    g_free(ids);
    if (iface) {
      g_type_default_interface_unref(klass);
    } else {
      g_type_class_unref(klass);
    }
  }
}

}  // namespace phpgtk
