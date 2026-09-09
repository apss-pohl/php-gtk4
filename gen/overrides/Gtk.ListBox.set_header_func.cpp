/**
 * public function set_header_func(?callable $update_header): void
 * Give rows headers: `function (GtkListBoxRow $row, ?GtkListBoxRow $before): void` calls {@see GtkListBoxRow::set_header()} on $row, and $before is the row above it (null for the first). Null removes every header.
 */
ZEND_METHOD(Gtk4_GtkListBox, set_header_func) {
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkListBox *self = PHPGTK_SELF(GtkListBox, GTK_TYPE_LIST_BOX);
  if (!ZEND_FCI_INITIALIZED(fci)) {
    gtk_list_box_set_header_func(self, nullptr, nullptr, nullptr);
  } else {
    Callback *cb = callback_new(&fci.function_name, "GtkListBox::set_header_func");
    teardown_track_notified(cb, G_OBJECT(self), list_box_header_func_clear);
    gtk_list_box_set_header_func(self, list_box_header_func, cb, list_box_header_func_free);
  }
  callback_drain();  // the previous func's notify ran inside the setter
}
