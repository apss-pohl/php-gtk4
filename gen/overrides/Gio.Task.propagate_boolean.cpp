/**
 * public function propagate_boolean(): bool
 * The task's result, or a Gtk4\GError when it failed.
 *
 * A task has no result until something sets one; asking early is a GLib CRITICAL
 * (`task->result_set`) followed by a made-up zero, which is indistinguishable from a real
 * result. See the prelude for how "has a result" is decided.
 */
ZEND_METHOD(Gtk4_GTask, propagate_boolean) {
  ZEND_PARSE_PARAMETERS_NONE();
  GTask *self = PHPGTK_SELF(GTask, G_TYPE_TASK);
  if (!task_has_result(self)) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\GTask::propagate_boolean(): the task has no result yet", 0);
    RETURN_THROWS();
  }
  GError *error = nullptr;
  const gboolean ok = g_task_propagate_boolean(self, &error);
  if (ok == FALSE) {
    throw_gerror(error);
    RETURN_THROWS();
  }
  RETURN_TRUE;
}
