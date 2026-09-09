// GTask's "does it have a result yet?" - GLib keeps `result_set` private, and the two ways a
// result arrives need different answers: a task GLib created and handed back through a
// *_finish() callback is `completed` by then, while one PHP built and resolved synchronously is
// not (completed only turns true when the callback is dispatched). So the PHP-side return_*()
// methods leave a mark, and propagate_*() accepts either.
namespace {

// qdata key on a GTask whose result was set through this binding.
GQuark php_task_result_quark() {
  static GQuark q = 0;
  if (q == 0) q = g_quark_from_static_string("php-gtk4-task-result-set");
  return q;
}

// Whether asking for the result is meaningful; GLib CRITICALs and invents a zero otherwise.
bool task_has_result(GTask *task) {
  return g_task_get_completed(task) != FALSE
         || g_object_get_qdata(G_OBJECT(task), php_task_result_quark()) != nullptr;
}

// Called by every bound return_*(): from here on the task has an answer.
void mark_task_result(GTask *task) {
  // NOLINTNEXTLINE(performance-no-int-to-ptr) GINT_TO_POINTER() is how GLib tags qdata
  g_object_set_qdata(G_OBJECT(task), php_task_result_quark(), GINT_TO_POINTER(1));
}

}  // namespace
