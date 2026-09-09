/**
 * public function has_action(string $action_name): bool
 * Checks if the named action exists within $action_group.
 *
 * A GApplication only has its actions once it is registered (from `startup` on); before
 * that GLib CRITICALs and answers a default that reads like "no such action" rather than
 * "ask me later", so this refuses with a `LogicException` instead. Every other action
 * group answers at any time.
 */
ZEND_METHOD(Gtk4_GActionGroup, has_action) {
  zend_string *action_name;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(action_name)
  ZEND_PARSE_PARAMETERS_END();
  GActionGroup *self = PHPGTK_SELF(GActionGroup, G_TYPE_ACTION_GROUP);
  if (!phpgtk::check_utf8(action_name, 1)) RETURN_THROWS();
  if (!action_group_is_queryable(self, "has_action")) RETURN_THROWS();
  RETURN_BOOL(g_action_group_has_action(self, ZSTR_VAL(action_name)));
}
