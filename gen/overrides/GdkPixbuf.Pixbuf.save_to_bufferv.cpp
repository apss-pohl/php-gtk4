/**
 * public function save_to_bufferv(string $type, array $options = []): string
 * The image encoded as $type ("png", "jpeg", "tiff", "bmp", ...; get_formats() lists what is
 * writable) with the encoder's $options (a map of option name to string value, "quality" =>
 * "90" for JPEG), as the file's bytes.
 *
 * GIR fills a caller-provided buffer and takes the options as two parallel arrays; PHP gets the
 * bytes back and passes a map.
 */
ZEND_METHOD(Gtk4_GdkPixbuf, save_to_bufferv) {
  zend_string *type;
  zval *options = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 2)
  Z_PARAM_STR(type)
  Z_PARAM_OPTIONAL
  Z_PARAM_ARRAY(options)
  ZEND_PARSE_PARAMETERS_END();
  GdkPixbuf *self = PHPGTK_SELF(GdkPixbuf, GDK_TYPE_PIXBUF);
  if (!phpgtk::check_utf8(type, 1)) RETURN_THROWS();
  std::vector<char *> keys;
  std::vector<char *> values;
  if (options != nullptr && !options_from_php(options, 2, keys, values)) RETURN_THROWS();
  gchar *buffer = nullptr;
  gsize length = 0;
  GError *error = nullptr;
  const gboolean ok = gdk_pixbuf_save_to_bufferv(self, &buffer, &length, ZSTR_VAL(type),
                                                 options != nullptr ? keys.data() : nullptr,
                                                 options != nullptr ? values.data() : nullptr, &error);
  if (!ok) {
    throw_gerror(error);
    RETURN_THROWS();
  }
  RETVAL_STRINGL(buffer, length);
  g_free(buffer);
}
