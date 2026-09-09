/**
 * public function set_filter_func(?callable $match_func): void
 * Replace the callback (null = everything matches) and notify users.
 */
ZEND_METHOD(Gtk4_GtkCustomFilter, set_filter_func) {
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkCustomFilter *filter = PHPGTK_SELF(GtkCustomFilter, GTK_TYPE_CUSTOM_FILTER);
  install_match_func(filter, &fci, "GtkCustomFilter::set_filter_func");
}
