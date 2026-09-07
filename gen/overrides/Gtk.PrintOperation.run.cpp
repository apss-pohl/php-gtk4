/**
 * public function run(GtkPrintOperationAction $action, ?GtkWindow $parent): GtkPrintOperationResult
 * Runs the print operation: ACTION_PRINT_DIALOG shows the print dialog (over $parent), ACTION_PRINT
 * prints straight away with the current settings, ACTION_PREVIEW shows the preview, ACTION_EXPORT
 * writes the pages to the file set_export_filename() named - without any dialog. Blocks until the
 * operation is done unless set_allow_async(true) was called; a failure is a GError.
 *
 * An export without a file name is a LogicException here, where GTK would only complain.
 */
ZEND_METHOD(Gtk4_GtkPrintOperation, run) {
  zval *action;
  zval *parent = nullptr;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_OBJECT_OF_CLASS(action, enum_class_for_type(GTK_TYPE_PRINT_OPERATION_ACTION))
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(parent, class_for_gtype(GTK_TYPE_WINDOW))
  ZEND_PARSE_PARAMETERS_END();
  GtkPrintOperation *self = PHPGTK_SELF(GtkPrintOperation, GTK_TYPE_PRINT_OPERATION);
  gint action_v = 0;
  if (!enum_from_php(action, GTK_TYPE_PRINT_OPERATION_ACTION, &action_v)) RETURN_THROWS();
  gchar *export_filename = nullptr;  // no getter in GTK: the property
  if (action_v == GTK_PRINT_OPERATION_ACTION_EXPORT) {
    g_object_get(self, "export-filename", &export_filename, nullptr);
  }
  const bool export_without_file = action_v == GTK_PRINT_OPERATION_ACTION_EXPORT && export_filename == nullptr;
  g_free(export_filename);
  if (export_without_file) {
    zend_throw_exception_ex(spl_ce_LogicException, 0,
                            "%s(): ACTION_EXPORT needs set_export_filename() first",
                            ZSTR_VAL(EX(func)->common.function_name));
    RETURN_THROWS();
  }
  GObject *parent_o = nullptr;
  if (parent != nullptr) {
    parent_o = unwrap(parent, GTK_TYPE_WINDOW);
    if (parent_o == nullptr) RETURN_THROWS();
  }
  GError *error = nullptr;
  const GtkPrintOperationResult result = gtk_print_operation_run(
      self, static_cast<GtkPrintOperationAction>(action_v),
      parent_o != nullptr ? GTK_WINDOW(parent_o) : nullptr, &error);
  auto *remembered = g_new(gint, 1);
  *remembered = static_cast<gint>(result);
  g_object_set_qdata_full(G_OBJECT(self), last_result_quark(), remembered, g_free);
  if (error != nullptr) {
    throw_gerror(error);
    RETURN_THROWS();
  }
  enum_to_php(GTK_TYPE_PRINT_OPERATION_RESULT, static_cast<gint>(result), return_value);
}
