/**
 * public function activate_action(string $action_name, mixed $parameter = null): void
 * Activate an action by name. $parameter is converted to the action's declared parameter type
 * (ValueError when the action is unknown or a required parameter is missing, TypeError when the
 * value does not fit the type).
 */
ZEND_METHOD(Gtk4_GActionGroup, activate_action) {
  zend_string *name;
  zval *param = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 2)
  Z_PARAM_STR(name)
  Z_PARAM_OPTIONAL
  Z_PARAM_ZVAL(param)
  ZEND_PARSE_PARAMETERS_END();
  GActionGroup *group = PHPGTK_SELF(GActionGroup, G_TYPE_ACTION_GROUP);
  if (!g_action_group_has_action(group, ZSTR_VAL(name))) {
    zend_value_error("no action '%s' on this %s", ZSTR_VAL(name), G_OBJECT_TYPE_NAME(group));
    RETURN_THROWS();
  }
  const GVariantType *t = g_action_group_get_action_parameter_type(group, ZSTR_VAL(name));
  GVariant *v = nullptr;
  if (t != nullptr) {
    const bool null_given = param == nullptr || Z_TYPE_P(param) == IS_NULL;
    if (null_given && !g_variant_type_is_maybe(t)) {
      zend_argument_value_error(2, "is required: action '%s' takes a parameter of type %s",
                                ZSTR_VAL(name), g_variant_type_peek_string(t));
      RETURN_THROWS();
    }
    zval null_zv;
    ZVAL_NULL(&null_zv);
    v = php_to_variant(null_given ? &null_zv : param, t);
    if (v == nullptr) RETURN_THROWS();
    g_variant_ref_sink(v);
  }
  g_action_group_activate_action(group, ZSTR_VAL(name), v);
  if (v != nullptr) g_variant_unref(v);
  if (EG(exception) != nullptr) RETURN_THROWS();
}
