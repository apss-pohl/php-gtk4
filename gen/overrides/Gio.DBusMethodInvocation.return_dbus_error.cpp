/**
 * public function return_dbus_error(string $error_name, string $error_message): void
 * Finishes handling a D-Bus method call by returning an error.
 *
 * `$error_name` is a valid D-Bus error name, `org.example.Failed`. An invocation answers once:
 * a second reply of any kind is a `LogicException`.
 */
ZEND_METHOD(Gtk4_GDBusMethodInvocation, return_dbus_error) {
  zend_string *error_name;
  zend_string *error_message;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_STR(error_name)
  Z_PARAM_STR(error_message)
  ZEND_PARSE_PARAMETERS_END();
  GDBusMethodInvocation *self = PHPGTK_SELF(GDBusMethodInvocation, G_TYPE_DBUS_METHOD_INVOCATION);
  if (!phpgtk::check_utf8(error_name, 1)) RETURN_THROWS();
  if (!g_dbus_is_name(ZSTR_VAL(error_name))) {
    zend_argument_value_error(1, "must be a D-Bus error name, \"%s\" given", ZSTR_VAL(error_name));
    RETURN_THROWS();
  }
  if (!phpgtk::check_utf8(error_message, 2)) RETURN_THROWS();
  if (!invocation_open(self, "return_dbus_error")) RETURN_THROWS();
  invocation_answered(self);
  g_dbus_method_invocation_return_dbus_error(self, ZSTR_VAL(error_name), ZSTR_VAL(error_message));
}
