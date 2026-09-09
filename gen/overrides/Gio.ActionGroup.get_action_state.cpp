/**
 * public function get_action_state(string $action_name): mixed
 * Queries the current state of the named action within $action_group.
 *
 * A GApplication only has its actions once it is registered (from `startup` on); before
 * that GLib CRITICALs and answers a default that reads like "no such action" rather than
 * "ask me later", so this refuses with a `LogicException` instead. Every other action
 * group answers at any time.
 */
ZEND_METHOD(Gtk4_GActionGroup, get_action_state) {
  zend_string *action_name;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(action_name)
  ZEND_PARSE_PARAMETERS_END();
  GActionGroup *self = PHPGTK_SELF(GActionGroup, G_TYPE_ACTION_GROUP);
  if (!phpgtk::check_utf8(action_name, 1)) RETURN_THROWS();
  if (!action_group_is_queryable(self, "get_action_state")) RETURN_THROWS();
  GVariant *phpgtk_ret = g_action_group_get_action_state(self, ZSTR_VAL(action_name));
  if (phpgtk_ret == nullptr) RETURN_NULL();
  variant_to_php(phpgtk_ret, return_value);
  g_variant_unref(phpgtk_ret);
}
