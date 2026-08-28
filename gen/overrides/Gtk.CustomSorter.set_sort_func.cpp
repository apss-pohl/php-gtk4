/**
 * public function set_sort_func(?callable $compare): void
 * Replace the callback (null = keep original order) and notify users.
 */
ZEND_METHOD(Gtk4_GtkCustomSorter, set_sort_func) {
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkCustomSorter *sorter = PHPGTK_SELF(GtkCustomSorter, GTK_TYPE_CUSTOM_SORTER);
  install_compare_func(sorter, &fci, "GtkCustomSorter::set_sort_func");
}
