#include "gsignal.h"
#include "wrap.h"
#include "marshal.h"
#include "error.h"
#include <string>

namespace phpgtk {

struct PhpClosure {
  GClosure closure;  // must be first
  Php::Value callable;
  Php::Array user_args;
  std::string signal_name;
};

static void closure_finalize(gpointer, GClosure *c) {
  auto *pc = reinterpret_cast<PhpClosure *>(c);
  // Runs when the handler is disconnected or the instance dies - while PHP is
  // still up as long as shutdown disconnects handlers before Zend teardown.
  pc->callable.~Value();
  pc->user_args.~Array();
  pc->signal_name.~basic_string();
}

static void closure_marshal(GClosure *c, GValue *return_value, guint n_params, const GValue *params,
                            gpointer, gpointer) {
  auto *pc = reinterpret_cast<PhpClosure *>(c);

  std::string err;
  long code = 0;
  bool failed = false;

  try {
    Php::Value args;
    for (guint i = 0; i < n_params; i++) args[(int)i] = to_php(&params[i]);
    int n = (int)n_params;
    for (const auto &kv : pc->user_args) args[n++] = kv.second;

    Php::Value ret = Php::call("call_user_func_array", pc->callable, args);

    if (return_value != nullptr && G_VALUE_TYPE(return_value) != G_TYPE_INVALID) {
      GValue tmp = G_VALUE_INIT;
      to_gvalue(ret, G_VALUE_TYPE(return_value), &tmp);
      g_value_copy(&tmp, return_value);
      g_value_unset(&tmp);
    }
  } catch (Php::Throwable &t) {
    err = t.what();
    code = t.code();
    failed = true;
  }
  // Report only after the catch scope has been left (see error.h).
  if (failed) report_callback_exception(err, code, pc->signal_name.c_str());
}

Php::Value signal_connect(GObjectWrapper *self, Php::Parameters &params, bool after) {
  if (self->obj() == nullptr) throw Php::Exception("connect() on a dead GObject");
  if (params.size() < 2)
    throw Php::Exception("connect() expects (string $signal, callable $handler, ...)");
  std::string name = params[0];
  if (!Php::call("is_callable", params[1]).boolValue()) {
    throw Php::Exception("connect(): handler for '" + name + "' is not callable");
  }

  guint signal_id = 0;
  GQuark detail = 0;
  if (!g_signal_parse_name(name.c_str(), G_OBJECT_TYPE(self->obj()), &signal_id, &detail, TRUE)) {
    throw Php::Exception("unknown signal '" + name + "' on " + G_OBJECT_TYPE_NAME(self->obj()));
  }

  auto *pc = reinterpret_cast<PhpClosure *>(g_closure_new_simple(sizeof(PhpClosure), nullptr));
  new (&pc->callable) Php::Value(params[1]);
  new (&pc->user_args) Php::Array();
  new (&pc->signal_name) std::string(name);
  for (size_t i = 2; i < params.size(); i++) pc->user_args[(int)(i - 2)] = params[i];

  g_closure_add_finalize_notifier(&pc->closure, nullptr, closure_finalize);
  g_closure_set_marshal(&pc->closure, closure_marshal);

  gulong id = g_signal_connect_closure_by_id(self->obj(), signal_id, detail, &pc->closure, after);
  return (int64_t)id;
}

}  // namespace phpgtk
