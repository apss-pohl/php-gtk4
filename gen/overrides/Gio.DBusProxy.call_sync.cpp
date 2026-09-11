/**
 * public function call_sync(string $method_name, ?array $parameters = null, int $flags = 0, int $timeout_msec = -1, ?GCancellable $cancellable = null): array
 * Synchronously invokes the method_name method on proxy.
 *
 * `$parameters` is the method's arguments as a list. With interface info on the proxy (given
 * to the constructor, or {@see set_interface_info()}) each argument converts to the type the
 * method declares; without, by inference (bool, int as `i`, float, string, list of strings,
 * associative array as `a{sv}`). The reply tuple comes back as a list, `[]` for none.
 *
 * @throws GError The remote error, or the transport's
 */
ZEND_METHOD(Gtk4_GDBusProxy, call_sync) {
  zend_string *method_name;
  zval *parameters = nullptr;
  zend_long flags = 0;
  zend_long timeout_msec = -1;
  zval *cancellable = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 5)
  Z_PARAM_STR(method_name)
  Z_PARAM_OPTIONAL
  Z_PARAM_ARRAY_OR_NULL(parameters)
  Z_PARAM_LONG(flags)
  Z_PARAM_LONG(timeout_msec)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(cancellable, class_for_gtype(G_TYPE_CANCELLABLE))
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
  GError *error = nullptr;
  GVariant *reply = g_dbus_proxy_call_sync(
      self, ZSTR_VAL(method_name), body, static_cast<GDBusCallFlags>(flags),
      static_cast<gint>(timeout_msec),
      cancellable_o != nullptr ? G_CANCELLABLE(cancellable_o) : nullptr, &error);
  g_variant_unref(body);
  if (reply == nullptr) {
    throw_gerror(error);
    RETURN_THROWS();
  }
  variant_to_php(reply, return_value);
  g_variant_unref(reply);
}
