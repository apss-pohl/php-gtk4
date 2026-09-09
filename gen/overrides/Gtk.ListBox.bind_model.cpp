/**
 * public function bind_model(?GListModel $model, ?callable $create_widget_func): void
 * Fill the box from $model, one row per item: `function (GObject $item): GtkWidget` builds the
 * row. The box follows the model from then on - items added or removed there add and remove
 * rows here - and a null $model unbinds it, taking the callable with it.
 */
ZEND_METHOD(Gtk4_GtkListBox, bind_model) {
  zval *model = nullptr;
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(model, class_for_gtype(G_TYPE_LIST_MODEL))
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkListBox *self = PHPGTK_SELF(GtkListBox, GTK_TYPE_LIST_BOX);
  GObject *model_o = nullptr;
  if (model != nullptr) {
    model_o = unwrap(model, G_TYPE_LIST_MODEL);
    if (model_o == nullptr) RETURN_THROWS();
  }
  // A model without a create function would leave GTK dereferencing NULL for every item; a
  // create function without a model is worse than useless, because GTK returns before it stores
  // the destroy notify (gtkflowbox.c / gtklistbox.c) and the callable is leaked, never called.
  if (model_o != nullptr && !ZEND_FCI_INITIALIZED(fci)) {
    zend_argument_value_error(2, "must be a callable when a model is given");
    RETURN_THROWS();
  }
  if (model_o == nullptr && ZEND_FCI_INITIALIZED(fci)) {
    zend_argument_value_error(2, "must be null when no model is given");
    RETURN_THROWS();
  }
  if (!ZEND_FCI_INITIALIZED(fci)) {
    gtk_list_box_bind_model(self, nullptr, nullptr, nullptr, nullptr);
  } else {
    Callback *cb = callback_new(&fci.function_name, "GtkListBox::bind_model");
    teardown_track_notified(cb, G_OBJECT(self), list_box_create_widget_func_clear);
    gtk_list_box_bind_model(self, model_o != nullptr ? G_LIST_MODEL(model_o) : nullptr,
                            list_box_create_widget_func, cb, list_box_create_widget_func_free);
  }
  callback_drain();  // the previous func's notify ran inside bind_model()
}
