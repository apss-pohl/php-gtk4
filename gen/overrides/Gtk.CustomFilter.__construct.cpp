/**
 * public function __construct(?callable $match_func = null)
 * A filter driven by a PHP callable: `function (GObject $item): bool`; null matches everything.
 */
ZEND_METHOD(Gtk4_GtkCustomFilter, __construct) {
  zend_fcall_info fci = {};
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkCustomFilter *filter = gtk_custom_filter_new(nullptr, nullptr, nullptr);
  attach_new(object_from_zval(ZEND_THIS), G_OBJECT(filter));
  install_match_func(filter, &fci, "GtkCustomFilter::__construct");
}
