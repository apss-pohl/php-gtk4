/**
 * public function set_theme_name(?string $theme_name): void
 * Set the icon theme this object looks names up in.
 *
 * GTK refuses it on the theme a display owns - `g_return_if_fail (!self->is_display_singleton)`,
 * a CRITICAL that changes nothing - because that one follows the desktop's setting. There is no
 * public predicate for it, but the singleton is by definition the theme its own display answers
 * with, so that is what this compares. A theme of one's own (`new GtkIconTheme()`) is free.
 */
ZEND_METHOD(Gtk4_GtkIconTheme, set_theme_name) {
  zend_string *theme_name = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR_OR_NULL(theme_name)
  ZEND_PARSE_PARAMETERS_END();
  GtkIconTheme *self = PHPGTK_SELF(GtkIconTheme, GTK_TYPE_ICON_THEME);
  if (theme_name != nullptr && !check_utf8(theme_name, 1)) RETURN_THROWS();
  GdkDisplay *display = gtk_icon_theme_get_display(self);
  if (display != nullptr && gtk_icon_theme_get_for_display(display) == self) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\GtkIconTheme::set_theme_name(): the theme of a display follows "
                         "the desktop setting; make one of your own to point it elsewhere",
                         0);
    RETURN_THROWS();
  }
  gtk_icon_theme_set_theme_name(self, theme_name == nullptr ? nullptr : ZSTR_VAL(theme_name));
}
