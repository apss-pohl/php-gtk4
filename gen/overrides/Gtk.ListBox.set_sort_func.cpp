/**
 * public function set_sort_func(?callable $sort_func): void
 * Order the rows by `function (GtkListBoxRow $row1, GtkListBoxRow $row2): int` - negative, zero or positive as usual; null leaves them in the order they were added.
 */
ZEND_METHOD(Gtk4_GtkListBox, set_sort_func) {
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkListBox *self = PHPGTK_SELF(GtkListBox, GTK_TYPE_LIST_BOX);
  if (!ZEND_FCI_INITIALIZED(fci)) {
    gtk_list_box_set_sort_func(self, nullptr, nullptr, nullptr);
  } else {
    Callback *cb = callback_new(&fci.function_name, "GtkListBox::set_sort_func");
    teardown_track_notified(cb, G_OBJECT(self), list_box_sort_func_clear);
    gtk_list_box_set_sort_func(self, list_box_sort_func, cb, list_box_sort_func_free);
  }
  callback_drain();  // the previous func's notify ran inside the setter
}
