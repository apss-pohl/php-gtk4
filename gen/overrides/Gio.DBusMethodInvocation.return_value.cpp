/**
 * public function return_value(?array $parameters = null): void
 * Finishes handling a D-Bus method call by returning parameters.
 *
 * The reply as a list (a D-Bus body is a tuple; null or `[]` for a method without out-args),
 * converted to the out-argument signatures of the method's introspection when the invocation
 * carries it ({@see get_method_info()}), by inference otherwise. An invocation answers once:
 * a second reply of any kind is a `LogicException`.
 */
ZEND_METHOD(Gtk4_GDBusMethodInvocation, return_value) {
  zval *parameters = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_ARRAY_OR_NULL(parameters)
  ZEND_PARSE_PARAMETERS_END();
  GDBusMethodInvocation *self = PHPGTK_SELF(GDBusMethodInvocation, G_TYPE_DBUS_METHOD_INVOCATION);
  if (!invocation_open(self, "return_value")) RETURN_THROWS();
  GVariantType *type = nullptr;
  const GDBusMethodInfo *method = g_dbus_method_invocation_get_method_info(self);
  if (method != nullptr) {
    std::string sig = "(";
    for (GDBusArgInfo **arg = method->out_args; arg != nullptr && *arg != nullptr; arg++) {
      sig += (*arg)->signature;
    }
    sig += ")";
    type = g_variant_type_new(sig.c_str());
  }
  GVariant *body = php_to_variant_tuple(parameters, type, 1);
  if (type != nullptr) g_variant_type_free(type);
  if (body == nullptr) RETURN_THROWS();
  invocation_answered(self);
  g_dbus_method_invocation_return_value(self, body);  // consumes GLib's reference
}
