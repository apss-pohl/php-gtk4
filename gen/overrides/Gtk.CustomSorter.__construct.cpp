/**
 * public function __construct(?callable $compare = null)
 * A sorter driven by a PHP callable: `function (GObject $a, GObject $b): int`; null keeps the
 * original order.
 */
ZEND_METHOD(Gtk4_GtkCustomSorter, __construct) {
  zend_fcall_info fci = {};
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkCustomSorter *sorter = gtk_custom_sorter_new(nullptr, nullptr, nullptr);
  attach_new(object_from_zval(ZEND_THIS), G_OBJECT(sorter));
  install_compare_func(sorter, &fci, "GtkCustomSorter::__construct");
}
