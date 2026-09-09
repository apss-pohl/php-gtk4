/**
 * public function get_error(): void
 * Throws the GError behind a run() that answered GtkPrintOperationResult::Error (the `done`
 * signal reports the same result); a LogicException when the last run did not end in one.
 *
 * GTK asserts when there is no error to propagate, so the binding asks first.
 *
 * @throws GError the error of the failed operation
 */
ZEND_METHOD(Gtk4_GtkPrintOperation, get_error) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkPrintOperation *self = PHPGTK_SELF(GtkPrintOperation, GTK_TYPE_PRINT_OPERATION);
  const auto *remembered = static_cast<const gint *>(g_object_get_qdata(G_OBJECT(self), last_result_quark()));
  const gint last = remembered == nullptr ? -1 : *remembered;
  if (last != GTK_PRINT_OPERATION_RESULT_ERROR) {
    zend_throw_exception_ex(spl_ce_LogicException, 0,
                            "%s(): the last run() did not end in an error (result %s)",
                            ZSTR_VAL(EX(func)->common.function_name),
                            last < 0 ? "none - it never ran" : "not Error");
    RETURN_THROWS();
  }
  GError *error = nullptr;
  gtk_print_operation_get_error(self, &error);
  if (error != nullptr) {
    throw_gerror(error);
    RETURN_THROWS();
  }
}
