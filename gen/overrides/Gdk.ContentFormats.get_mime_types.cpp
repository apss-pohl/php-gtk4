/**
 * public function get_mime_types(): array
 * The MIME types in this set.
 *
 * @return list<string>
 */
ZEND_METHOD(Gtk4_GdkContentFormats, get_mime_types) {
  ZEND_PARSE_PARAMETERS_NONE();
  auto *self = static_cast<GdkContentFormats *>(boxed_from_zval(ZEND_THIS)->data);
  gsize count = 0;
  const char *const *types = gdk_content_formats_get_mime_types(self, &count);
  array_init_size(return_value, static_cast<uint32_t>(count));
  for (gsize i = 0; types != nullptr && i < count; i++) {
    add_next_index_string(return_value, types[i]);
  }
}
