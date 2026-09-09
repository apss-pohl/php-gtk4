/**
 * public function set_state(mixed $value): void
 * Set the state directly (emits `notify::state`, not `change-state`). The value is converted
 * to the action's state type; a stateless action throws LogicException.
 */
ZEND_METHOD(Gtk4_GSimpleAction, set_state) {
  zval *value;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_ZVAL(value)
  ZEND_PARSE_PARAMETERS_END();
  GSimpleAction *self = PHPGTK_SELF(GSimpleAction, G_TYPE_SIMPLE_ACTION);
  const GVariantType *t = g_action_get_state_type(G_ACTION(self));
  if (t == nullptr) {
    zend_throw_exception_ex(spl_ce_LogicException, 0,
                            "GSimpleAction::set_state(): action '%s' is stateless",
                            g_action_get_name(G_ACTION(self)));
    RETURN_THROWS();
  }
  GVariant *v = php_to_variant(value, t);
  if (v == nullptr) RETURN_THROWS();
  g_simple_action_set_state(self, v);
}
