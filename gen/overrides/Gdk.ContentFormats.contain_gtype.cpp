/**
 * public function contain_gtype(string $type): bool
 * Whether this set offers $type, named as elsewhere.
 */
ZEND_METHOD(Gtk4_GdkContentFormats, contain_gtype) {
  zend_string *type;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(type)
  ZEND_PARSE_PARAMETERS_END();
  auto *self = static_cast<GdkContentFormats *>(boxed_from_zval(ZEND_THIS)->data);
  const GType t = gtype_from_php_name(type, 1);
  if (t == 0) RETURN_THROWS();
  RETURN_BOOL(gdk_content_formats_contain_gtype(self, t));
}
