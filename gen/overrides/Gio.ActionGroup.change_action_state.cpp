/**
 * public function change_action_state(string $action_name, mixed $value = null): void
 * Request for the state of the named action within $action_group to be changed to $value.
 *
 * A GApplication only has its actions once it is registered (from `startup` on); before
 * that GLib CRITICALs and answers a default that reads like "no such action" rather than
 * "ask me later", so this refuses with a `LogicException` instead. Every other action
 * group answers at any time.
 */
ZEND_METHOD(Gtk4_GActionGroup, change_action_state) {
  zend_string *action_name;
  zval *value = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 2)
  Z_PARAM_STR(action_name)
  Z_PARAM_OPTIONAL
  Z_PARAM_ZVAL(value)
  ZEND_PARSE_PARAMETERS_END();
  GActionGroup *self = PHPGTK_SELF(GActionGroup, G_TYPE_ACTION_GROUP);
  if (!phpgtk::check_utf8(action_name, 1)) RETURN_THROWS();
  if (!action_group_is_queryable(self, "change_action_state")) RETURN_THROWS();
  GVariant *value_v = nullptr;
  if (value != nullptr && Z_TYPE_P(value) != IS_NULL) {
    value_v = php_to_variant(value, nullptr);
    if (value_v == nullptr) RETURN_THROWS();
    g_variant_ref_sink(value_v);
  }
  g_action_group_change_action_state(self, ZSTR_VAL(action_name), value_v);
  if (value_v != nullptr) g_variant_unref(value_v);
}
