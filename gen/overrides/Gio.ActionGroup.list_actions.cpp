/**
 * public function list_actions(): array
 * The names of the actions in this group.
 *
 * A GApplication only has its actions once it is registered (from `startup` on); before that
 * GLib CRITICALs and answers an empty list, which reads like "no actions" rather than "ask me
 * later". Every other action group answers at any time.
 *
 * @return list<string>
 */
ZEND_METHOD(Gtk4_GActionGroup, list_actions) {
  ZEND_PARSE_PARAMETERS_NONE();
  GActionGroup *self = PHPGTK_SELF(GActionGroup, G_TYPE_ACTION_GROUP);
  // NOLINTNEXTLINE(bugprone-assignment-in-if-condition) G_IS_APPLICATION() macro expansion
  if (G_IS_APPLICATION(self) && g_application_get_is_registered(G_APPLICATION(self)) == FALSE) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\GActionGroup::list_actions(): the application is not registered "
                         "yet - its actions exist from the `startup` signal on",
                         0);
    RETURN_THROWS();
  }
  strv_to_php(g_action_group_list_actions(self), Transfer::Full, return_value);
}
