/**
 * public function set_filter_func(?callable $filter_func): void
 * Keep only the children `function (GtkFlowBoxChild $child): bool` answers true for; null shows every child again.
 */
ZEND_METHOD(Gtk4_GtkFlowBox, set_filter_func) {
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkFlowBox *self = PHPGTK_SELF(GtkFlowBox, GTK_TYPE_FLOW_BOX);
  if (!ZEND_FCI_INITIALIZED(fci)) {
    gtk_flow_box_set_filter_func(self, nullptr, nullptr, nullptr);
  } else {
    Callback *cb = callback_new(&fci.function_name, "GtkFlowBox::set_filter_func");
    teardown_track_notified(cb, G_OBJECT(self), flow_box_filter_func_clear);
    gtk_flow_box_set_filter_func(self, flow_box_filter_func, cb, flow_box_filter_func_free);
  }
  callback_drain();  // the previous func's notify ran inside the setter
}
