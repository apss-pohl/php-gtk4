/**
 * public function emit_signal(?string $destination_bus_name, string $object_path, string $interface_name, string $signal_name, ?array $parameters = null, ?string $signature = null): bool
 * Emits a signal.
 *
 * `$parameters` is the signal's arguments as a list (a D-Bus body is a tuple), typed by
 * inference or by `$signature` - see {@see call_sync()}. `$destination_bus_name` null
 * broadcasts.
 *
 * @throws GError If the message could not be sent
 */
ZEND_METHOD(Gtk4_GDBusConnection, emit_signal) {
  zend_string *destination_bus_name = nullptr;
  zend_string *object_path;
  zend_string *interface_name;
  zend_string *signal_name;
  zval *parameters = nullptr;
  zend_string *signature = nullptr;
  ZEND_PARSE_PARAMETERS_START(4, 6)
  Z_PARAM_STR_OR_NULL(destination_bus_name)
  Z_PARAM_STR(object_path)
  Z_PARAM_STR(interface_name)
  Z_PARAM_STR(signal_name)
  Z_PARAM_OPTIONAL
  Z_PARAM_ARRAY_OR_NULL(parameters)
  Z_PARAM_STR_OR_NULL(signature)
  ZEND_PARSE_PARAMETERS_END();
  GDBusConnection *self = PHPGTK_SELF(GDBusConnection, G_TYPE_DBUS_CONNECTION);
  if (destination_bus_name != nullptr && !phpgtk::check_utf8(destination_bus_name, 1)) RETURN_THROWS();
  if (destination_bus_name != nullptr && !g_dbus_is_name(ZSTR_VAL(destination_bus_name))) {
    zend_argument_value_error(1, "must be a D-Bus name");
    RETURN_THROWS();
  }
  if (!phpgtk::check_utf8(object_path, 2)) RETURN_THROWS();
  if (!g_variant_is_object_path(ZSTR_VAL(object_path))) {
    zend_argument_value_error(2, "must be a D-Bus object path");
    RETURN_THROWS();
  }
  if (!phpgtk::check_utf8(interface_name, 3)) RETURN_THROWS();
  if (!g_dbus_is_interface_name(ZSTR_VAL(interface_name))) {
    zend_argument_value_error(3, "must be a D-Bus interface name");
    RETURN_THROWS();
  }
  if (!phpgtk::check_utf8(signal_name, 4)) RETURN_THROWS();
  if (!g_dbus_is_member_name(ZSTR_VAL(signal_name))) {
    zend_argument_value_error(4, "must be a D-Bus member name");
    RETURN_THROWS();
  }
  GVariant *body = dbus_body(parameters, signature, 5, 6);
  if (body == nullptr) RETURN_THROWS();
  GError *error = nullptr;
  const gboolean ok = g_dbus_connection_emit_signal(
      self, destination_bus_name != nullptr ? ZSTR_VAL(destination_bus_name) : nullptr,
      ZSTR_VAL(object_path), ZSTR_VAL(interface_name), ZSTR_VAL(signal_name), body, &error);
  g_variant_unref(body);
  if (ok == FALSE) {
    throw_gerror(error);
    RETURN_THROWS();
  }
  RETURN_TRUE;
}
