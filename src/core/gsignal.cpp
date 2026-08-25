#include "gsignal.h"
#include "error.h"
#include "marshal.h"
#include "object.h"

namespace phpgtk {

struct PhpClosure {
  GClosure closure;  // must be first
  zval callable;     // keeps the handler alive
  zval user_args;    // IS_ARRAY (packed) or IS_UNDEF
  zend_string *signal_name;
};

static void closure_finalize(gpointer, GClosure *c) {
  auto *pc = reinterpret_cast<PhpClosure *>(c);
  zval_ptr_dtor(&pc->callable);
  if (!Z_ISUNDEF(pc->user_args)) zval_ptr_dtor(&pc->user_args);
  zend_string_release(pc->signal_name);
}

static void closure_marshal(GClosure *c, GValue *return_value, guint n_params, const GValue *params,
                            gpointer, gpointer) {
  auto *pc = reinterpret_cast<PhpClosure *>(c);
  const char *origin = ZSTR_VAL(pc->signal_name);

  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  if (zend_fcall_info_init(&pc->callable, 0, &fci, &fcc, nullptr, nullptr) != SUCCESS) {
    g_critical("php-gtk4: handler for '%s' is no longer callable", origin);
    return;
  }

  uint32_t n_user = Z_ISUNDEF(pc->user_args) ? 0 : zend_hash_num_elements(Z_ARRVAL(pc->user_args));
  uint32_t argc = n_params + n_user;
  auto *args = static_cast<zval *>(safe_emalloc(argc, sizeof(zval), 0));
  uint32_t filled = 0;
  for (guint i = 0; i < n_params; i++) {
    to_php(&params[i], &args[i]);
    filled++;
    if (EG(exception) != nullptr) break;  // unsupported type: reported below
  }
  if (EG(exception) == nullptr) {
    if (n_user > 0) {
      zval *ua;
      // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
      ZEND_HASH_FOREACH_VAL(Z_ARRVAL(pc->user_args), ua) {
        ZVAL_COPY(&args[filled], ua);
        filled++;
      }
      ZEND_HASH_FOREACH_END();
    }

    zval retval;
    ZVAL_UNDEF(&retval);
    fci.retval = &retval;
    fci.params = args;
    fci.param_count = argc;
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
  // nothing may stay pending or unwind into GLib.
  report_pending_exception(origin);
}

void signal_connect_method(INTERNAL_FUNCTION_PARAMETERS, bool after) {
  zend_string *signal;
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  zval *user_args = nullptr;
  uint32_t n_user = 0;

  ZEND_PARSE_PARAMETERS_START(2, -1)
  Z_PARAM_STR(signal)
  Z_PARAM_FUNC(fci, fcc)
  Z_PARAM_VARIADIC('*', user_args, n_user)
  ZEND_PARSE_PARAMETERS_END();

  GObject *obj = self_object(execute_data, G_TYPE_OBJECT, after ? "connect_after" : "connect");
  if (obj == nullptr) RETURN_THROWS();

  guint signal_id = 0;
  GQuark detail = 0;
  if (!g_signal_parse_name(ZSTR_VAL(signal), G_OBJECT_TYPE(obj), &signal_id, &detail, TRUE)) {
    zend_value_error("unknown signal '%s' on %s", ZSTR_VAL(signal), G_OBJECT_TYPE_NAME(obj));
    RETURN_THROWS();
  }

  auto *pc = reinterpret_cast<PhpClosure *>(g_closure_new_simple(sizeof(PhpClosure), nullptr));
  ZVAL_COPY(&pc->callable, &fci.function_name);
  if (n_user > 0) {
    array_init_size(&pc->user_args, n_user);
    for (uint32_t i = 0; i < n_user; i++) {
      Z_TRY_ADDREF(user_args[i]);
      zend_hash_next_index_insert(Z_ARRVAL(pc->user_args), &user_args[i]);
    }
  } else {
    ZVAL_UNDEF(&pc->user_args);
  }
  pc->signal_name = zend_string_copy(signal);

  g_closure_add_finalize_notifier(&pc->closure, nullptr, closure_finalize);
  g_closure_set_marshal(&pc->closure, closure_marshal);
  gulong id = g_signal_connect_closure_by_id(obj, signal_id, detail, &pc->closure, after);
  RETURN_LONG(static_cast<zend_long>(id));
}

}  // namespace phpgtk
