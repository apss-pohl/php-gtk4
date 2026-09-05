/**
 * public function return_error(GError $error): void
 * Fail the task with $error and schedule its callback.
 *
 * Marks the task as having an answer so propagate_*() can tell it apart from a fresh one
 * (see the prelude).
 */
ZEND_METHOD(Gtk4_GTask, return_error) {
  zval *error;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(error, ce_GError)
  ZEND_PARSE_PARAMETERS_END();
  GTask *self = PHPGTK_SELF(GTask, G_TYPE_TASK);
  if (task_has_result(self)) {  // GLib asserts '!task->ever_returned' and drops the second result
    zend_throw_exception_ex(spl_ce_LogicException, 0, "%s(): the task already has a result",
                            ZSTR_VAL(EX(func)->common.function_name));
    RETURN_THROWS();
  }
  GError *error_e = gerror_from_php(error);
  g_task_return_error(self, error_e);  // takes ownership
  mark_task_result(self);
}
