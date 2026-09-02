/**
 * public function propagate_int(): int
 * The task's result, or a Gtk4\GError when it failed.
 *
 * A task has no result until something sets one; asking early is a GLib CRITICAL
 * (`task->result_set`) followed by a made-up zero, which is indistinguishable from a real
 * result. See the prelude for how "has a result" is decided.
 */
ZEND_METHOD(Gtk4_GTask, propagate_int) {
  ZEND_PARSE_PARAMETERS_NONE();
  GTask *self = PHPGTK_SELF(GTask, G_TYPE_TASK);
  if (!task_has_result(self)) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\GTask::propagate_int(): the task has no result yet", 0);
    RETURN_THROWS();
  }
  GError *error = nullptr;
  const gssize value = g_task_propagate_int(self, &error);
  if (value == -1 && error != nullptr) {
    throw_gerror(error);
    RETURN_THROWS();
  }
  RETURN_LONG(static_cast<zend_long>(value));
}
