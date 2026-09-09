/**
 * public function get_gtypes(): array
 * The types in this set, named as elsewhere ("string", a registered class name).
 *
 * @return list<string>
 */
ZEND_METHOD(Gtk4_GdkContentFormats, get_gtypes) {
  ZEND_PARSE_PARAMETERS_NONE();
  auto *self = static_cast<GdkContentFormats *>(boxed_from_zval(ZEND_THIS)->data);
  gsize count = 0;
  const GType *types = gdk_content_formats_get_gtypes(self, &count);
  array_init_size(return_value, static_cast<uint32_t>(count));
  for (gsize i = 0; types != nullptr && i < count; i++) {
    add_next_index_str(return_value, php_name_for_gtype(types[i]));
  }
}
