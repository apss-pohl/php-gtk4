/**
 * public static function bus_get_sync(GBusType $bus_type, ?GCancellable $cancellable = null): GDBusConnection
 * The shared connection to the session or system bus (`g_bus_get_sync()`).
 *
 * GLib keeps one connection per bus type and hands out the same instance every time, so this
 * is where a program gets its bus: {@see GtkApplication::get_dbus_connection()} answers only
 * once the application is registered on D-Bus, which `GApplicationFlags::NON_UNIQUE` never does.
 * Connecting is synchronous and can take a moment on a cold bus.
 *
 * @throws GError If the bus cannot be reached (no session bus, `DBUS_SESSION_BUS_ADDRESS` unset)
 */
ZEND_METHOD(Gtk4_GDBusConnection, bus_get_sync) {
  zval *bus_type;
  zval *cancellable = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 2)
  Z_PARAM_OBJECT_OF_CLASS(bus_type, enum_class_for_type(G_TYPE_BUS_TYPE))
  Z_PARAM_OPTIONAL
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(cancellable, class_for_gtype(G_TYPE_CANCELLABLE))
  ZEND_PARSE_PARAMETERS_END();
  gint bus_type_v = 0;
  if (!enum_from_php(bus_type, G_TYPE_BUS_TYPE, &bus_type_v)) RETURN_THROWS();
  GObject *cancellable_o = nullptr;
  if (cancellable != nullptr) {
    cancellable_o = unwrap(cancellable, G_TYPE_CANCELLABLE);
    if (cancellable_o == nullptr) RETURN_THROWS();
  }
  GError *error = nullptr;
  GDBusConnection *conn =
      g_bus_get_sync(static_cast<GBusType>(bus_type_v),
                     cancellable_o != nullptr ? G_CANCELLABLE(cancellable_o) : nullptr, &error);
  if (conn == nullptr) {
    throw_gerror(error);
    RETURN_THROWS();
  }
  wrap(G_OBJECT(conn), return_value);
  g_object_unref(conn);  // the handle took its own reference
}
