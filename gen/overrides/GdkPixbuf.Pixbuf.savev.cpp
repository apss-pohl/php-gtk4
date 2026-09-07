/**
 * public function savev(string $filename, string $type, array $options = []): bool
 * Writes the image to $filename encoded as $type ("png", "jpeg", ...) with the encoder's
 * $options (a map of option name to string value, "quality" => "90" for JPEG). A failure is a
 * GError; the boolean is GTK's own (always true when it returns).
 *
 * GIR takes the options as two parallel arrays; PHP passes a map.
 */
ZEND_METHOD(Gtk4_GdkPixbuf, savev) {
  zend_string *filename;
  zend_string *type;
  zval *options = nullptr;
  ZEND_PARSE_PARAMETERS_START(2, 3)
  Z_PARAM_PATH_STR(filename)
  Z_PARAM_STR(type)
  Z_PARAM_OPTIONAL
  Z_PARAM_ARRAY(options)
  ZEND_PARSE_PARAMETERS_END();
  GdkPixbuf *self = PHPGTK_SELF(GdkPixbuf, GDK_TYPE_PIXBUF);
  if (!phpgtk::check_utf8(type, 2)) RETURN_THROWS();
  std::vector<char *> keys;
  std::vector<char *> values;
  if (options != nullptr && !options_from_php(options, 3, keys, values)) RETURN_THROWS();
  zend_string *path = phpgtk::absolute_filename(filename, 1);
  if (path == nullptr) RETURN_THROWS();
  GError *error = nullptr;
  const gboolean ok = gdk_pixbuf_savev(self, ZSTR_VAL(path), ZSTR_VAL(type),
                                       options != nullptr ? keys.data() : nullptr,
                                       options != nullptr ? values.data() : nullptr, &error);
  zend_string_release(path);
  if (!ok) {
    throw_gerror(error);
    RETURN_THROWS();
  }
  RETURN_TRUE;
}
