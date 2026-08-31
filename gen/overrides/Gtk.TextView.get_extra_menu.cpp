/**
 * public function get_extra_menu(): ?GMenuModel
 * Gets the menu model added to the context menu, or `null` if none has been set. (GTK's
 * documentation says "or NULL" but the 4.14 GIR annotation misses the nullable; declared
 * here so the type stays true.)
 */
ZEND_METHOD(Gtk4_GtkTextView, get_extra_menu) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkTextView *self = PHPGTK_SELF(GtkTextView, GTK_TYPE_TEXT_VIEW);
  GMenuModel *phpgtk_ret = gtk_text_view_get_extra_menu(self);
  wrap(phpgtk_ret != nullptr ? G_OBJECT(phpgtk_ret) : nullptr, return_value);
}
