/**
 * public function get_layout_child(GtkWidget $child): GtkLayoutChild
 * Retrieves a `GtkLayoutChild` instance for the `GtkLayoutManager`, creating one if necessary.
 *
 * GTK looks the manager up through the child's parent: a child with no parent is a
 * `g_return_val_if_fail` that answers NULL, which the declared return type does not allow.
 */
ZEND_METHOD(Gtk4_GtkLayoutManager, get_layout_child) {
  zval *zchild;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(zchild, class_for_gtype(GTK_TYPE_WIDGET))
  ZEND_PARSE_PARAMETERS_END();
  GtkLayoutManager *self = PHPGTK_SELF(GtkLayoutManager, GTK_TYPE_LAYOUT_MANAGER);
  GObject *child_o = unwrap(zchild, GTK_TYPE_WIDGET);
  if (child_o == nullptr) RETURN_THROWS();
  GtkWidget *child = GTK_WIDGET(child_o);
  GtkWidget *parent = gtk_widget_get_parent(child);
  if (parent == nullptr) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\GtkLayoutManager::get_layout_child(): the child has no parent, so "
                         "no layout manager holds it",
                         0);
    RETURN_THROWS();
  }
  // ... and it has to be *this* manager's child: GTK checks the parent's manager and CRITICALs
  // ("does not use the given layout manager of type ...") before answering NULL.
  if (gtk_widget_get_layout_manager(parent) != self) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\GtkLayoutManager::get_layout_child(): the child's parent is laid "
                         "out by a different manager",
                         0);
    RETURN_THROWS();
  }
  GtkLayoutChild *lc = gtk_layout_manager_get_layout_child(self, child);
  if (lc == nullptr) {
    zend_throw_error(nullptr, "Gtk4\\GtkLayoutManager::get_layout_child(): GTK returned no child");
    RETURN_THROWS();
  }
  wrap(G_OBJECT(lc), return_value);
}
