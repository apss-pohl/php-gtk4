/**
 * public static function get_file_info(string $filename): ?array
 * The format, width and height of an image file, read from its header without decoding it -
 * `[GdkPixbufFormat $format, int $width, int $height]` - or null when no loader recognises it.
 *
 * @return array{GdkPixbufFormat, int, int}|null
 */
ZEND_METHOD(Gtk4_GdkPixbuf, get_file_info) {
  zend_string *filename;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_PATH_STR(filename)
  ZEND_PARSE_PARAMETERS_END();
  zend_string *path = phpgtk::absolute_filename(filename, 1);
  if (path == nullptr) RETURN_THROWS();
  gint width = 0;
  gint height = 0;
  GdkPixbufFormat *format = gdk_pixbuf_get_file_info(ZSTR_VAL(path), &width, &height);
  zend_string_release(path);
  if (format == nullptr) RETURN_NULL();
  array_init_size(return_value, 3);
  zval boxed;
  wrap_boxed(gdk_pixbuf_format_get_type(), format, &boxed);  // GdkPixbuf owns the format; a copy
  add_next_index_zval(return_value, &boxed);
  add_next_index_long(return_value, width);
  add_next_index_long(return_value, height);
}
