/**
 * public function grab_focus_without_selecting(): bool
 * Take the keyboard focus without selecting the text, or false when the widget cannot.
 *
 * Focus belongs to a toplevel: GTK hands it to the widget's root, and a widget that is not in a
 * window yet has none - `gtk_root_set_focus(NULL)` CRITICALs and the call answers false anyway.
 * `grab_focus()` itself checks first and simply returns false, so this matches it.
 */
ZEND_METHOD(Gtk4_GtkEntry, grab_focus_without_selecting) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkEntry *self = PHPGTK_SELF(GtkEntry, GTK_TYPE_ENTRY);
  if (gtk_widget_get_root(GTK_WIDGET(self)) == nullptr) RETURN_FALSE;
  RETURN_BOOL(gtk_entry_grab_focus_without_selecting(self));
}
