/**
 * public function get_children(): array
 * This box's children, in order (GTK has no such call: it walks first-child/next-sibling).
 *
 * @return list<GtkWidget>
 */
ZEND_METHOD(Gtk4_GtkBox, get_children) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkBox *self = PHPGTK_SELF(GtkBox, GTK_TYPE_BOX);
  array_init(return_value);
  for (GtkWidget *c = gtk_widget_get_first_child(GTK_WIDGET(self)); c != nullptr;
       c = gtk_widget_get_next_sibling(c)) {
    zval item;
    wrap(G_OBJECT(c), &item);
    add_next_index_zval(return_value, &item);
  }
}
