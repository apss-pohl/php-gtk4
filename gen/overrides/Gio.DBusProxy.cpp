// Typed calls: a proxy with interface info knows each method's in-argument signatures, so a
// PHP list converts to exactly the tuple the remote side declared instead of by inference.
#include "core/callback.h"
#include "core/variant.h"

#include <array>
#include <string>

namespace {

// The "(...)" tuple type of a method's in-args from the proxy's interface info, or nullptr
// (no info, or the method is not in it: inference then).
GVariantType *proxy_in_type(GDBusProxy *proxy, const char *method_name) {
  GDBusInterfaceInfo *info = g_dbus_proxy_get_interface_info(proxy);
  if (info == nullptr) return nullptr;
  GDBusMethodInfo *method = g_dbus_interface_info_lookup_method(info, method_name);
  if (method == nullptr) return nullptr;
  std::string sig = "(";
  for (GDBusArgInfo **arg = method->in_args; arg != nullptr && *arg != nullptr; arg++) {
    sig += (*arg)->signature;
  }
  sig += ")";
  return g_variant_type_new(sig.c_str());
}

// The body of a proxy call: the list converted to the introspected tuple, else inferred.
GVariant *proxy_body(GDBusProxy *proxy, const char *method_name, zval *parameters, uint32_t arg_num) {
  GVariantType *type = proxy_in_type(proxy, method_name);
  GVariant *body = php_to_variant_tuple(parameters, type, arg_num);
  if (type != nullptr) g_variant_type_free(type);
  if (body != nullptr) g_variant_ref_sink(body);
  return body;
}

// GAsyncReadyCallback of call(): (GDBusProxy, GAsyncResult) -> void, one-shot.
void proxy_call_ready(GObject *source_object, GAsyncResult *res, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  std::array<zval, 2> args{};
  wrap(source_object, args.data());
  wrap(res != nullptr ? G_OBJECT(res) : nullptr, &args[1]);
  zval ret;
  callback_invoke(cb, 2, args.data(), &ret);
  zval_ptr_dtor(&ret);
  for (zval &a : args) zval_ptr_dtor(&a);
  callback_free(cb);
  callback_drain();
}

}  // namespace
