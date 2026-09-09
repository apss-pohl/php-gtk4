/**
 * public function __construct(GListModel $root, bool $passthrough, bool $autoexpand, callable $create_func)
 * A tree over $root: `function (GObject $item): ?GListModel` answers with an item's children,
 * null for a leaf. $passthrough decides whether the model hands out the items themselves or
 * their {@see GtkTreeListRow}s; $autoexpand keeps every row expanded.
 */
ZEND_METHOD(Gtk4_GtkTreeListModel, __construct) {
  zval *root;
  bool passthrough = false;
  bool autoexpand = false;
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(4, 4)
  Z_PARAM_OBJECT_OF_CLASS(root, class_for_gtype(G_TYPE_LIST_MODEL))
  Z_PARAM_BOOL(passthrough)
  Z_PARAM_BOOL(autoexpand)
  Z_PARAM_FUNC(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GObject *root_o = unwrap(root, G_TYPE_LIST_MODEL);
  if (root_o == nullptr) RETURN_THROWS();
  g_object_ref(root_o);  // transfer full
  Callback *cb = callback_new(&fci.function_name, "GtkTreeListModel::__construct");
  GtkTreeListModel *model =
      gtk_tree_list_model_new(G_LIST_MODEL(root_o), passthrough ? TRUE : FALSE,
                              autoexpand ? TRUE : FALSE, create_model_func, cb,
                              create_model_func_free);
  g_object_set_data(G_OBJECT(model), CREATE_FUNC_KEY, cb);
  teardown_track_notified(cb, G_OBJECT(model), create_model_func_clear);
  attach_new(object_from_zval(ZEND_THIS), G_OBJECT(model));
  callback_drain();  // the create func may already have run (autoexpand)
}
