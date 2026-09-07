/**
 * public function object_invoke_method(string $name, array $parameters = []): JSCValue
 * Invoke the method $name of this object with $parameters, a list of JSCValue; the result, or
 * `undefined` when the method returns nothing. A JavaScript exception does not throw here: it
 * lands in the context (`get_context()->get_exception()`).
 */
ZEND_METHOD(Gtk4_JSCValue, object_invoke_method) {
  zend_string *name;
  zval *parameters = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 2)
  Z_PARAM_STR(name)
  Z_PARAM_OPTIONAL
  Z_PARAM_ARRAY(parameters)
  ZEND_PARSE_PARAMETERS_END();
  JSCValue *self = PHPGTK_SELF(JSCValue, JSC_TYPE_VALUE);
  if (!phpgtk::check_utf8(name, 1)) RETURN_THROWS();
  std::vector<JSCValue *> args;
  if (parameters != nullptr && !jsc_values_from_list(parameters, 2, args)) RETURN_THROWS();
  JSCValue *phpgtk_ret =
      jsc_value_object_invoke_methodv(self, ZSTR_VAL(name), static_cast<guint>(args.size()), args.data());
  wrap(phpgtk_ret != nullptr ? G_OBJECT(phpgtk_ret) : nullptr, return_value);
  if (phpgtk_ret != nullptr) g_object_unref(phpgtk_ret);  // the handle took its own ref
}
