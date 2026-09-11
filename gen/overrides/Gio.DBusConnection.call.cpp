/**
 * public function call(?string $bus_name, string $object_path, string $interface_name, string $method_name, ?array $parameters = null, ?string $reply_type = null, int $flags = 0, int $timeout_msec = -1, ?GCancellable $cancellable = null, ?callable $callback = null, ?string $signature = null): void
 * Asynchronously invokes the method_name method on the interface_name D-Bus interface on the
 * remote object at object_path owned by bus_name.
 *
 * The arguments are as {@see call_sync()}'s: a list, typed by inference or by `$signature`.
 * `$callback` gets the connection and a {@see GAsyncResult} to hand to {@see call_finish()}.
 */
ZEND_METHOD(Gtk4_GDBusConnection, call) {
  zend_string *bus_name = nullptr;
  zend_string *object_path;
  zend_string *interface_name;
  zend_string *method_name;
  zval *parameters = nullptr;
  zend_string *reply_type = nullptr;
  zend_long flags = 0;
  zend_long timeout_msec = -1;
  zval *cancellable = nullptr;
  zend_fcall_info fci_callback = empty_fcall_info;
  zend_fcall_info_cache fcc_callback = empty_fcall_info_cache;
  zend_string *signature = nullptr;
  ZEND_PARSE_PARAMETERS_START(4, 11)
  Z_PARAM_STR_OR_NULL(bus_name)
  Z_PARAM_STR(object_path)
  Z_PARAM_STR(interface_name)
  Z_PARAM_STR(method_name)
  Z_PARAM_OPTIONAL
  Z_PARAM_ARRAY_OR_NULL(parameters)
  Z_PARAM_STR_OR_NULL(reply_type)
  Z_PARAM_LONG(flags)
  Z_PARAM_LONG(timeout_msec)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(cancellable, class_for_gtype(G_TYPE_CANCELLABLE))
  Z_PARAM_FUNC_OR_NULL(fci_callback, fcc_callback)
  Z_PARAM_STR_OR_NULL(signature)
  ZEND_PARSE_PARAMETERS_END();
  GDBusConnection *self = PHPGTK_SELF(GDBusConnection, G_TYPE_DBUS_CONNECTION);
  if (bus_name != nullptr && !phpgtk::check_utf8(bus_name, 1)) RETURN_THROWS();
  if (bus_name != nullptr && !g_dbus_is_name(ZSTR_VAL(bus_name))) {
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
  if (!phpgtk::check_utf8(method_name, 4)) RETURN_THROWS();
  if (!g_dbus_is_member_name(ZSTR_VAL(method_name))) {
    zend_argument_value_error(4, "must be a D-Bus member name");
    RETURN_THROWS();
  }
  GVariantType *reply_type_t = nullptr;
  if (reply_type != nullptr) {
    if (!g_variant_type_string_is_valid(ZSTR_VAL(reply_type))) {
      zend_argument_value_error(6, "must be a valid GVariant type string, \"%s\" given",
                                ZSTR_VAL(reply_type));
      RETURN_THROWS();
    }
    reply_type_t = g_variant_type_new(ZSTR_VAL(reply_type));
  }
  if (!phpgtk::check_flags(G_TYPE_DBUS_CALL_FLAGS, flags, 7)) RETURN_THROWS();
  if (!phpgtk::check_range<gint>(timeout_msec, 8)) RETURN_THROWS();
  GObject *cancellable_o = nullptr;
  if (cancellable != nullptr) {
    cancellable_o = unwrap(cancellable, G_TYPE_CANCELLABLE);
    if (cancellable_o == nullptr) RETURN_THROWS();
  }
  GVariant *body = dbus_body(parameters, signature, 5, 11);
  if (body == nullptr) {
    if (reply_type_t != nullptr) g_variant_type_free(reply_type_t);
    RETURN_THROWS();
  }
  Callback *cb_callback = ZEND_FCI_INITIALIZED(fci_callback)
                              ? callback_new(&fci_callback.function_name, "GDBusConnection::call")
                              : nullptr;
  g_dbus_connection_call(self, bus_name != nullptr ? ZSTR_VAL(bus_name) : nullptr,
                         ZSTR_VAL(object_path), ZSTR_VAL(interface_name), ZSTR_VAL(method_name),
                         body, reply_type_t, static_cast<GDBusCallFlags>(flags),
                         static_cast<gint>(timeout_msec),
                         cancellable_o != nullptr ? G_CANCELLABLE(cancellable_o) : nullptr,
                         cb_callback != nullptr ? dbus_call_ready : nullptr, cb_callback);
  g_variant_unref(body);
  if (reply_type_t != nullptr) g_variant_type_free(reply_type_t);
}
