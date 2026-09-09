// Prelude for Gtk4\GActionGroup: the registration precondition every query shares.

namespace {

// A GApplication only owns its actions once it is registered (from `startup` on). Every query
// below it - g_application_query_action() and g_application_change_action_state() - is a
// g_return_if_fail() until then, so GLib CRITICALs and answers a default that reads like "no such
// action" rather than "ask me later". Refuse the call instead: the handle is in the wrong state,
// which is a LogicException by the vocabulary in CLAUDE.md. Every other action group answers at
// any time, so the check is scoped to GApplication.
bool action_group_is_queryable(GActionGroup *self, const char *method) {
  // NOLINTNEXTLINE(bugprone-assignment-in-if-condition) G_IS_APPLICATION() macro expansion
  if (G_IS_APPLICATION(self) && g_application_get_is_registered(G_APPLICATION(self)) == FALSE) {
    zend_throw_exception_ex(spl_ce_LogicException, 0,
                            "Gtk4\\GActionGroup::%s(): the application is not registered yet - "
                            "its actions exist from the `startup` signal on",
                            method);
    return false;
  }
  return true;
}

}  // namespace
