/**
 * public function get_dbus_connection(): ?GDBusConnection
 * The D-Bus connection the application registered on, or null when it has none.
 *
 * GLib fills it in while registering, and CRITICALs when asked before that. Registration
 * happens on `run()` / `register()`, so "not registered yet" is a state, not an absence - and
 * a `GApplicationFlags::NON_UNIQUE` application never registers on D-Bus at all: null then,
 * and {@see GDBusConnection::bus_get_sync()} is the way to the bus.
 */
ZEND_METHOD(Gtk4_GApplication, get_dbus_connection) {
  ZEND_PARSE_PARAMETERS_NONE();
  GApplication *self = PHPGTK_SELF(GApplication, G_TYPE_APPLICATION);
  if (g_application_get_is_registered(self) == FALSE) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\GApplication::get_dbus_connection(): the application is not "
                         "registered yet",
                         0);
    RETURN_THROWS();
  }
  wrap(G_OBJECT(g_application_get_dbus_connection(self)), return_value);
}
