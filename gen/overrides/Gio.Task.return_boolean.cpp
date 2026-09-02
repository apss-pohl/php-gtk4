/**
 * public function return_boolean(bool $result): void
 * Set the task's result and schedule its callback.
 *
 * Marks the task as having an answer so propagate_boolean() can tell it apart from a fresh one
 * (see the prelude).
 */
ZEND_METHOD(Gtk4_GTask, return_boolean) {
  bool result = false;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_BOOL(result)
  ZEND_PARSE_PARAMETERS_END();
  GTask *self = PHPGTK_SELF(GTask, G_TYPE_TASK);
  g_task_return_boolean(self, static_cast<gboolean>(result));
  mark_task_result(self);
}
