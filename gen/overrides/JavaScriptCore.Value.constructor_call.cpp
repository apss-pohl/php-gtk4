/**
 * public function constructor_call(array $parameters = []): JSCValue
 * Invoke `new` with this constructor and $parameters, a list of JSCValue; the object built. A
 * JavaScript exception does not throw here: it lands in the context
 * (`get_context()->get_exception()`).
 */
ZEND_METHOD(Gtk4_JSCValue, constructor_call) {
  zval *parameters = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_ARRAY(parameters)
  ZEND_PARSE_PARAMETERS_END();
  JSCValue *self = PHPGTK_SELF(JSCValue, JSC_TYPE_VALUE);
  std::vector<JSCValue *> args;
  if (parameters != nullptr && !jsc_values_from_list(parameters, 1, args)) RETURN_THROWS();
  JSCValue *phpgtk_ret = jsc_value_constructor_callv(self, static_cast<guint>(args.size()), args.data());
  wrap(phpgtk_ret != nullptr ? G_OBJECT(phpgtk_ret) : nullptr, return_value);
  if (phpgtk_ret != nullptr) g_object_unref(phpgtk_ret);  // the handle took its own ref
}
