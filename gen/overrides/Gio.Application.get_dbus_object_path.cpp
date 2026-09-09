/**
 * public function get_dbus_object_path(): ?string
 * The D-Bus object path the application exports its actions on, or null when it has none.
 *
 * GLib fills it in while registering, and CRITICALs when asked before that. Registration
 * happens on `run()` / `register()`, so "not registered yet" is a state, not an absence.
 */
ZEND_METHOD(Gtk4_GApplication, get_dbus_object_path) {
  ZEND_PARSE_PARAMETERS_NONE();
  GApplication *self = PHPGTK_SELF(GApplication, G_TYPE_APPLICATION);
  if (g_application_get_is_registered(self) == FALSE) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\GApplication::get_dbus_object_path(): the application is not "
                         "registered yet",
                         0);
    RETURN_THROWS();
  }
  PHPGTK_RETURN_STRING_OR_NULL(g_application_get_dbus_object_path(self));
}
