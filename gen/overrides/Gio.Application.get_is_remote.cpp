/**
 * public function get_is_remote(): bool
 * Whether this process is the remote end of an already-running primary instance.
 *
 * Only decided by registration; GLib CRITICALs when asked before that and answers false, which
 * is indistinguishable from "this is the primary instance".
 */
ZEND_METHOD(Gtk4_GApplication, get_is_remote) {
  ZEND_PARSE_PARAMETERS_NONE();
  GApplication *self = PHPGTK_SELF(GApplication, G_TYPE_APPLICATION);
  if (g_application_get_is_registered(self) == FALSE) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\GApplication::get_is_remote(): the application is not registered "
                         "yet, so there is no primary instance to be remote from",
                         0);
    RETURN_THROWS();
  }
  RETURN_BOOL(g_application_get_is_remote(self));
}
