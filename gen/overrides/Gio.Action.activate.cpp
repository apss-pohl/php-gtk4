/**
 * public function activate(mixed $parameter = null): void
 * Activate the action; $parameter is converted to the declared parameter type (ValueError when
 * a required parameter is missing or one is given to a parameterless action, TypeError when the
 * value does not fit the type). Emits `activate`.
 */
ZEND_METHOD(Gtk4_GAction, activate) {
  zval *param = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_ZVAL(param)
  ZEND_PARSE_PARAMETERS_END();
  GAction *a = PHPGTK_SELF(GAction, G_TYPE_ACTION);
  const GVariantType *t = g_action_get_parameter_type(a);
  GVariant *v = nullptr;
  if (t != nullptr) {
    const bool null_given = param == nullptr || Z_TYPE_P(param) == IS_NULL;
    if (null_given && !g_variant_type_is_maybe(t)) {
      zend_argument_value_error(1, "is required: action '%s' takes a parameter of type %s",
                                g_action_get_name(a), g_variant_type_peek_string(t));
      RETURN_THROWS();
    }
    zval null_zv;
    ZVAL_NULL(&null_zv);
    v = php_to_variant(null_given ? &null_zv : param, t);
    if (v == nullptr) RETURN_THROWS();
    g_variant_ref_sink(v);
  } else if (param != nullptr && Z_TYPE_P(param) != IS_NULL) {
    zend_argument_value_error(1, "must be null: action '%s' takes no parameter",
                              g_action_get_name(a));
    RETURN_THROWS();
  }
  g_action_activate(a, v);
  if (v != nullptr) g_variant_unref(v);
  if (EG(exception) != nullptr) RETURN_THROWS();
}
