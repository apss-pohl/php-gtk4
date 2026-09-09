/**
 * public function get_pointing_to(): ?GdkRectangle
 * The rectangle the popover points at, or null when it points at its parent as a whole.
 *
 * GTK falls back to the bounds of the popover's parent widget when no rectangle was set, so
 * without a parent it computes the bounds of NULL and CRITICALs before answering. A popover
 * with no parent has nothing to point at, which is the same precondition `popup()` already
 * refuses.
 */
ZEND_METHOD(Gtk4_GtkPopover, get_pointing_to) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkPopover *self = PHPGTK_SELF(GtkPopover, GTK_TYPE_POPOVER);
  if (gtk_widget_get_parent(GTK_WIDGET(self)) == nullptr) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\GtkPopover::get_pointing_to(): the popover has no parent widget "
                         "to point at yet",
                         0);
    RETURN_THROWS();
  }
  GdkRectangle rect{};
  if (gtk_popover_get_pointing_to(self, &rect) == FALSE) RETURN_NULL();
  wrap_boxed(GDK_TYPE_RECTANGLE, &rect, return_value);
}
