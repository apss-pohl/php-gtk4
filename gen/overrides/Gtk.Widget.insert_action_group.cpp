/**
 * public function insert_action_group(string $name, ?GActionGroup $group): void
 * Inserts $group into $widget.
 *
 * Passing `null` removes the group inserted under $name.
 */
ZEND_METHOD(Gtk4_GtkWidget, insert_action_group) {
  zend_string *name;
  zval *group = nullptr;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_STR(name)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(group, class_for_gtype(G_TYPE_ACTION_GROUP))
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *self = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  if (!phpgtk::check_utf8(name, 1)) RETURN_THROWS();
  GObject *group_o = nullptr;
  if (group != nullptr) {
    group_o = unwrap(group, G_TYPE_ACTION_GROUP);
    if (group_o == nullptr) RETURN_THROWS();
    // GTK reads the group's action list the moment it is inserted, and walks the result
    // without checking it: a group that answers NULL there is a SIGSEGV inside the action
    // muxer, not a warning. One group PHP can build does exactly that - an unregistered
    // GApplication (g_action_group_list_actions() is a g_return_val_if_fail until `startup`).
    // The handle is in the wrong state for the call: a LogicException. (A PHP class
    // implementing GActionGroup always answers: its list_actions slot is a thunk that yields
    // an empty list rather than NULL when PHP cannot be asked.)
    // NOLINTNEXTLINE(bugprone-assignment-in-if-condition) G_IS_APPLICATION() macro expansion
    if (G_IS_APPLICATION(group_o) && g_application_get_is_registered(G_APPLICATION(group_o)) == FALSE) {
      zend_throw_exception_ex(spl_ce_LogicException, 0,
                              "%s(): the application is not registered yet - its actions exist "
                              "from the `startup` signal on",
                              ZSTR_VAL(EX(func)->common.function_name));
      RETURN_THROWS();
    }
  }
  gtk_widget_insert_action_group(self, ZSTR_VAL(name),
                                 group_o != nullptr ? G_ACTION_GROUP(group_o) : nullptr);
  // for activate_action(): the type a `name.action` parameter converts to (class prelude)
  remember_inserted_group(self, ZSTR_VAL(name), group_o != nullptr ? G_ACTION_GROUP(group_o) : nullptr);
}
