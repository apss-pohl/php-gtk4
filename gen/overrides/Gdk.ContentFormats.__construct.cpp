/**
 * public function __construct(array $mime_types = [])
 * The set of MIME types a drag or a clipboard offers.
 *
 * GIR's constructor takes a C array; a PHP list of strings is the same thing. An empty set is
 * what GTK's own `gdk_content_formats_new(NULL, 0)` builds, and union_*() grows it.
 */
ZEND_METHOD(Gtk4_GdkContentFormats, __construct) {
  HashTable *mime_types = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_ARRAY_HT(mime_types)
  ZEND_PARSE_PARAMETERS_END();

  std::vector<const char *> types;
  if (mime_types != nullptr) {
    types.reserve(zend_hash_num_elements(mime_types));
    zval *entry = nullptr;
    // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
    ZEND_HASH_FOREACH_VAL(mime_types, entry) {
      if (Z_TYPE_P(entry) != IS_STRING) {
        zend_argument_type_error(1, "must be a list of MIME type strings");
        RETURN_THROWS();
      }
      if (!check_utf8(Z_STR_P(entry), 1)) RETURN_THROWS();
      types.push_back(Z_STRVAL_P(entry));
    }
    ZEND_HASH_FOREACH_END();
  }
  gpointer obj = gdk_content_formats_new(types.empty() ? nullptr : types.data(),
                                         static_cast<guint>(types.size()));
  if (obj == nullptr) {
    zend_throw_error(nullptr, "%s(): GTK refused to create the value (see the CRITICAL above)",
                     ZSTR_VAL(EX(func)->common.function_name));
    RETURN_THROWS();
  }
  boxed_adopt(boxed_from_zval(ZEND_THIS), GDK_TYPE_CONTENT_FORMATS, obj);
}
