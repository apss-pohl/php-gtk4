/**
 * public function selection_changed(int $position, int $n_items): void
 * Helper function for implementations of `GtkSelectionModel`.
 *
 * The range has to lie inside the model: GTK asserts
 * `position + n_items <= g_list_model_get_n_items()` and drops the notification otherwise, so a
 * range computed one item too long silently fails to repaint. Refuse it instead - and check the
 * sum in 64-bit, before the cast to guint that would wrap it.
 */
ZEND_METHOD(Gtk4_GtkSelectionModel, selection_changed) {
  zend_long position;
  zend_long n_items;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_LONG(position)
  Z_PARAM_LONG(n_items)
  ZEND_PARSE_PARAMETERS_END();
  GtkSelectionModel *self = PHPGTK_SELF(GtkSelectionModel, GTK_TYPE_SELECTION_MODEL);
  if (!phpgtk::check_range<guint>(position, 1)) RETURN_THROWS();
  if (!phpgtk::check_range<guint>(n_items, 2)) RETURN_THROWS();
  auto in_model = static_cast<zend_long>(g_list_model_get_n_items(G_LIST_MODEL(self)));
  if (position + n_items > in_model) {
    zend_argument_value_error(
        2, "must not reach past the end of the model (" ZEND_LONG_FMT " item(s)), " ZEND_LONG_FMT
           " item(s) from " ZEND_LONG_FMT " given",
        in_model, n_items, position);
    RETURN_THROWS();
  }
  gtk_selection_model_selection_changed(self, static_cast<guint>(position),
                                        static_cast<guint>(n_items));
}
