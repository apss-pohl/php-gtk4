/**
 * public function set_icon_list(array $surfaces): void
 * Sets a list of icons for the surface.
 *
 * One of these will be used to represent the surface in iconic form. The icon may be shown in
 * window lists or task bars. Which icon size is shown depends on the window manager. The window
 * manager can scale the icon but setting several size icons can give better image quality.
 * GIR's `GList` of textures is a PHP list of {@see GdkTexture} here (different sizes of the
 * same icon); an empty list unsets the icons. GTK 4 dropped `gtk_window_set_icon()`, so this
 * and {@see GtkWindow::set_icon_name()} are what remain for a window's own icon.
 */
ZEND_METHOD(Gtk4_GdkToplevel, set_icon_list) {
  HashTable *surfaces = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_ARRAY_HT(surfaces)
  ZEND_PARSE_PARAMETERS_END();
  GdkToplevel *self = PHPGTK_SELF(GdkToplevel, GDK_TYPE_TOPLEVEL);
  GList *list = nullptr;
  zval *entry = nullptr;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
  ZEND_HASH_FOREACH_VAL(surfaces, entry) {
    GObject *texture = Z_TYPE_P(entry) == IS_OBJECT ? unwrap(entry, GDK_TYPE_TEXTURE) : nullptr;
    if (texture == nullptr) {
      g_list_free(list);
      if (!EG(exception)) zend_argument_type_error(1, "must be a list of GdkTexture instances");
      RETURN_THROWS();
    }
    list = g_list_prepend(list, texture);
  }
  ZEND_HASH_FOREACH_END();
  list = g_list_reverse(list);
  gdk_toplevel_set_icon_list(self, list);  // GDK copies the list and refs the textures
  g_list_free(list);
}
