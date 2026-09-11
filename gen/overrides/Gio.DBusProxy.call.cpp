/**
 * public function call(string $method_name, ?array $parameters = null, int $flags = 0, int $timeout_msec = -1, ?GCancellable $cancellable = null, ?callable $callback = null): void
 * Asynchronously invokes the method_name method on proxy.
 *
 * The arguments are as {@see call_sync()}'s. `$callback` gets the proxy and a
 * {@see GAsyncResult} to hand to {@see call_finish()}.
 */
ZEND_METHOD(Gtk4_GDBusProxy, call) {
  zend_string *method_name;
  zval *parameters = nullptr;
  zend_long flags = 0;
  zend_long timeout_msec = -1;
  zval *cancellable = nullptr;
  zend_fcall_info fci_callback = empty_fcall_info;
  zend_fcall_info_cache fcc_callback = empty_fcall_info_cache;
  ZEND_PARSE_PARAMETERS_START(1, 6)
  Z_PARAM_STR(method_name)
  Z_PARAM_OPTIONAL
  Z_PARAM_ARRAY_OR_NULL(parameters)
  Z_PARAM_LONG(flags)
  Z_PARAM_LONG(timeout_msec)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(cancellable, class_for_gtype(G_TYPE_CANCELLABLE))
  Z_PARAM_FUNC_OR_NULL(fci_callback, fcc_callback)
  ZEND_PARSE_PARAMETERS_END();
  GDBusProxy *self = PHPGTK_SELF(GDBusProxy, G_TYPE_DBUS_PROXY);
  if (!phpgtk::check_utf8(method_name, 1)) RETURN_THROWS();
  if (!phpgtk::check_flags(G_TYPE_DBUS_CALL_FLAGS, flags, 3)) RETURN_THROWS();
  if (!phpgtk::check_range<gint>(timeout_msec, 4)) RETURN_THROWS();
  GObject *cancellable_o = nullptr;
  if (cancellable != nullptr) {
    cancellable_o = unwrap(cancellable, G_TYPE_CANCELLABLE);
    if (cancellable_o == nullptr) RETURN_THROWS();
  }
  GVariant *body = proxy_body(self, ZSTR_VAL(method_name), parameters, 2);
  if (body == nullptr) RETURN_THROWS();
  Callback *cb_callback = ZEND_FCI_INITIALIZED(fci_callback)
                              ? callback_new(&fci_callback.function_name, "GDBusProxy::call")
                              : nullptr;
  g_dbus_proxy_call(self, ZSTR_VAL(method_name), body, static_cast<GDBusCallFlags>(flags),
                    static_cast<gint>(timeout_msec),
                    cancellable_o != nullptr ? G_CANCELLABLE(cancellable_o) : nullptr,
                    cb_callback != nullptr ? proxy_call_ready : nullptr, cb_callback);
  g_variant_unref(body);
}
