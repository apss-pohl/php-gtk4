/**
 * public function return_int(int $result): void
 * Set the task's result and schedule its callback.
 *
 * Marks the task as having an answer so propagate_int() can tell it apart from a fresh one
 * (see the prelude).
 */
ZEND_METHOD(Gtk4_GTask, return_int) {
  zend_long result = 0;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_LONG(result)
  ZEND_PARSE_PARAMETERS_END();
  GTask *self = PHPGTK_SELF(GTask, G_TYPE_TASK);
  if (task_has_result(self)) {  // GLib asserts '!task->ever_returned' and drops the second result
    zend_throw_exception_ex(spl_ce_LogicException, 0, "%s(): the task already has a result",
                            ZSTR_VAL(EX(func)->common.function_name));
    RETURN_THROWS();
  }
  g_task_return_int(self, static_cast<gssize>(result));
  mark_task_result(self);
}
