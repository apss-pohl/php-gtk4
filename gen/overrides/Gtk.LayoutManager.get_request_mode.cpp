/**
 * public function get_request_mode(): GtkSizeRequestMode
 * Retrieves the request mode of $manager.
 *
 * GTK reads the mode off the widget the manager was set on: without one it is a
 * `g_return_val_if_fail` that tells PHP nothing and answers with the first enum value.
 */
ZEND_METHOD(Gtk4_GtkLayoutManager, get_request_mode) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkLayoutManager *self = PHPGTK_SELF(GtkLayoutManager, GTK_TYPE_LAYOUT_MANAGER);
  if (gtk_layout_manager_get_widget(self) == nullptr) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\GtkLayoutManager::get_request_mode(): the manager is not set on "
                         "a widget yet",
                         0);
    RETURN_THROWS();
  }
  enum_to_php(GTK_TYPE_SIZE_REQUEST_MODE, gtk_layout_manager_get_request_mode(self), return_value);
}
