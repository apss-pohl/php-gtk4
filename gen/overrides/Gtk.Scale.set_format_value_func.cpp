/**
 * public function set_format_value_func(?callable $func): void
 * The callable that renders the value label: `function (GtkScale $scale, float $value): string`;
 * null goes back to GTK's own formatting (`digits` decimals).
 */
ZEND_METHOD(Gtk4_GtkScale, set_format_value_func) {
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkScale *scale = PHPGTK_SELF(GtkScale, GTK_TYPE_SCALE);
  install_format_value_func(scale, &fci, "GtkScale::set_format_value_func");
}
