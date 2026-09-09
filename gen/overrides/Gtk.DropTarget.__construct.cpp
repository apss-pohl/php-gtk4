/**
 * public function __construct(string $type, int $actions)
 * A drop target accepting $type, offering $actions (a GdkDragAction mask).
 *
 * GIR's constructor takes a GType, which PHP has no spelling for; the type is named the way a
 * payload's is elsewhere - "string"/"int"/"float"/"bool" or a registered class name
 * (core/marshal, gtype_from_php_name). Without it a drop target accepts nothing at all.
 */
ZEND_METHOD(Gtk4_GtkDropTarget, __construct) {
  zend_string *type;
  zend_long actions;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_STR(type)
  Z_PARAM_LONG(actions)
  ZEND_PARSE_PARAMETERS_END();
  const GType t = gtype_from_php_name(type, 1);
  if (t == 0) RETURN_THROWS();
  if (!check_flags(GDK_TYPE_DRAG_ACTION, actions, 2)) RETURN_THROWS();

  GObject *obj = subtype_new(ZEND_THIS, "actions", static_cast<GdkDragAction>(actions), nullptr);
  if (obj == nullptr) {
    if (EG(exception) != nullptr) RETURN_THROWS();
    obj = G_OBJECT(gtk_drop_target_new(t, static_cast<GdkDragAction>(actions)));
  } else {
    GType only = t;
    gtk_drop_target_set_gtypes(GTK_DROP_TARGET(obj), &only, 1);  // a subtype misses the ctor arg
  }
  attach_new(object_from_zval(ZEND_THIS), obj);
}
