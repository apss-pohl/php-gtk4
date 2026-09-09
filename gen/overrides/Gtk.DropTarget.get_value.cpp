/**
 * public function get_value(): mixed
 * The value being dropped, or null outside a drop.
 *
 * GIR answers with a GValue; the marshaller turns it into the PHP value the source offered.
 */
ZEND_METHOD(Gtk4_GtkDropTarget, get_value) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkDropTarget *self = PHPGTK_SELF(GtkDropTarget, GTK_TYPE_DROP_TARGET);
  const GValue *gv = gtk_drop_target_get_value(self);
  if (gv == nullptr || !G_IS_VALUE(gv)) RETURN_NULL();
  to_php(gv, return_value);
}
