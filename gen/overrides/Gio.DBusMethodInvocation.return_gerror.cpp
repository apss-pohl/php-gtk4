/**
 * public function return_gerror(GError $error): void
 * Like g_dbus_method_invocation_return_error() but takes a #GError instead of the error domain,
 * error code and message.
 *
 * The exception's domain, code and message become the D-Bus error. An invocation answers once:
 * a second reply of any kind is a `LogicException`.
 */
ZEND_METHOD(Gtk4_GDBusMethodInvocation, return_gerror) {
  zval *error;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(error, ce_GError)
  ZEND_PARSE_PARAMETERS_END();
  GDBusMethodInvocation *self = PHPGTK_SELF(GDBusMethodInvocation, G_TYPE_DBUS_METHOD_INVOCATION);
  if (!invocation_open(self, "return_gerror")) RETURN_THROWS();
  GError *gerror = gerror_from_php(error);
  if (gerror == nullptr) RETURN_THROWS();
  invocation_answered(self);
  g_dbus_method_invocation_return_gerror(self, gerror);
  g_error_free(gerror);
}
