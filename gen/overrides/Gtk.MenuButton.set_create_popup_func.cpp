/**
 * public function set_create_popup_func(?callable $func): void
 * A callable run right before the popup is shown - `function (GtkMenuButton $button): void` -
 * so the popover or menu model can be built lazily; null removes it.
 */
ZEND_METHOD(Gtk4_GtkMenuButton, set_create_popup_func) {
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkMenuButton *button = PHPGTK_SELF(GtkMenuButton, GTK_TYPE_MENU_BUTTON);
  install_create_popup_func(button, &fci, "GtkMenuButton::set_create_popup_func");
}
