/**
 * public function get_options(): array
 * The key/value options the loader attached (an "orientation" from EXIF, "x-dpi"/"y-dpi", the
 * ICC profile) as a map of option name to string value; empty when there are none.
 *
 * @return array<string, string>
 */
ZEND_METHOD(Gtk4_GdkPixbuf, get_options) {
  ZEND_PARSE_PARAMETERS_NONE();
  GdkPixbuf *self = PHPGTK_SELF(GdkPixbuf, GDK_TYPE_PIXBUF);
  array_init(return_value);
  GHashTable *table = gdk_pixbuf_get_options(self);
  if (table == nullptr) return;
  GHashTableIter iter;
  gpointer key = nullptr;
  gpointer value = nullptr;
  g_hash_table_iter_init(&iter, table);
  while (g_hash_table_iter_next(&iter, &key, &value)) {
    add_assoc_string(return_value, static_cast<const char *>(key), static_cast<const char *>(value));
  }
  g_hash_table_unref(table);
}
