/**
 * public function get_formats(): ?GdkContentFormats
 * The formats this target accepts, or null when it accepts everything.
 *
 * GTK's GIR annotates this `transfer full`, but `gtk_drop_target_async_get_formats()` returns
 * `self->formats` borrowed (its sibling `gtk_drop_target_get_formats()` is annotated correctly).
 * Taking the annotation at its word unrefs GTK's own formats: the first call freed them and the
 * second read freed memory. So this one does not free what it wrapped.
 */
ZEND_METHOD(Gtk4_GtkDropTargetAsync, get_formats) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkDropTargetAsync *self = PHPGTK_SELF(GtkDropTargetAsync, GTK_TYPE_DROP_TARGET_ASYNC);
  wrap_boxed(GDK_TYPE_CONTENT_FORMATS, gtk_drop_target_async_get_formats(self), return_value);
}
