/**
 * public function __construct(string $item_type = GObject::class)
 * A GListModel backed by an array; items must be instances of $item_type (a PHP class name of
 * a registered GObject class, e.g. PhpValue::class).
 */
ZEND_METHOD(Gtk4_GListStore, __construct) {
  zend_string *type = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_STR(type)
  ZEND_PARSE_PARAMETERS_END();
  GType t = G_TYPE_OBJECT;
  if (type != nullptr) {
    t = item_type_from_string(type);
    if (t == 0) RETURN_THROWS();
  }
  attach_new(object_from_zval(ZEND_THIS), G_OBJECT(g_list_store_new(t)));
}
