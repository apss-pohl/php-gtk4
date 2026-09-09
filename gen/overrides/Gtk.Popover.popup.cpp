/**
 * public function popup(): void
 * Pop the popover up.
 *
 * A popover needs a parent widget: without one GTK asks GDK for a popup surface whose
 * parent is NULL and crashes there ("gdk_surface_new_popup: assertion 'GDK_IS_SURFACE
 * (parent)' failed", then SIGSEGV). Set the parent first - GtkMenuButton and
 * GtkWidget::set_parent() both do it.
 */
ZEND_METHOD(Gtk4_GtkPopover, popup) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkPopover *self = PHPGTK_SELF(GtkPopover, GTK_TYPE_POPOVER);
  if (gtk_widget_get_parent(GTK_WIDGET(self)) == nullptr) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\GtkPopover::popup(): the popover has no parent widget yet",
                         0);
    RETURN_THROWS();
  }
  gtk_popover_popup(self);
}
