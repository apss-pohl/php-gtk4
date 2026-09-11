/**
 * public function register_object(string $object_path, GDBusInterfaceInfo $interface_info, ?callable $method_call, ?callable $get_property = null, ?callable $set_property = null): int
 * Registers callbacks for exported objects at object_path with the D-Bus interface that is
 * described in interface_info.
 *
 * The three handlers are `function (GDBusConnection $connection, string $sender, string
 * $object_path, string $interface_name, ...)` continued by, for `$method_call`, `string
 * $method_name, array $parameters, GDBusMethodInvocation $invocation` (answer through the
 * invocation - {@see GDBusMethodInvocation::return_value()} types the reply from the method's
 * introspection); for `$get_property`, `string $property_name` returning the value, converted
 * to the property's signature from `$interface_info`; for `$set_property`, `string
 * $property_name, mixed $value` returning whether it was accepted. A handler left null makes
 * GLib answer that part itself (properties through `$method_call` as
 * `org.freedesktop.DBus.Properties` calls). The id is for {@see unregister_object()}; an object
 * still registered at request shutdown is unregistered then.
 *
 * @throws GError If the path is taken on this connection
 */
ZEND_METHOD(Gtk4_GDBusConnection, register_object) {
  zend_string *object_path;
  zval *info;
  zend_fcall_info fci_call = {};
  zend_fcall_info_cache fcc_call;
  zend_fcall_info fci_get = {};
  zend_fcall_info_cache fcc_get;
  zend_fcall_info fci_set = {};
  zend_fcall_info_cache fcc_set;
  ZEND_PARSE_PARAMETERS_START(3, 5)
  Z_PARAM_STR(object_path)
  Z_PARAM_OBJECT_OF_CLASS(info, boxed_class_for_type(G_TYPE_DBUS_INTERFACE_INFO)->ce)
  Z_PARAM_FUNC_OR_NULL(fci_call, fcc_call)
  Z_PARAM_OPTIONAL
  Z_PARAM_FUNC_OR_NULL(fci_get, fcc_get)
  Z_PARAM_FUNC_OR_NULL(fci_set, fcc_set)
  ZEND_PARSE_PARAMETERS_END();
  GDBusConnection *self = PHPGTK_SELF(GDBusConnection, G_TYPE_DBUS_CONNECTION);
  if (!phpgtk::check_utf8(object_path, 1)) RETURN_THROWS();
  if (!g_variant_is_object_path(ZSTR_VAL(object_path))) {
    zend_argument_value_error(1, "must be a D-Bus object path, \"%s\" given", ZSTR_VAL(object_path));
    RETURN_THROWS();
  }
  auto *iface = static_cast<GDBusInterfaceInfo *>(unwrap_boxed(info, G_TYPE_DBUS_INTERFACE_INFO));
  if (iface == nullptr) RETURN_THROWS();
  auto *e = g_new0(DBusExport, 1);
  callback_init(&e->method_call, &fci_call, "GDBusConnection::register_object ($method_call)");
  callback_init(&e->get_property, &fci_get, "GDBusConnection::register_object ($get_property)");
  callback_init(&e->set_property, &fci_set, "GDBusConnection::register_object ($set_property)");
  e->info = g_dbus_interface_info_ref(iface);
  GDBusInterfaceVTable vtable = export_vtable_all;
  if (Z_ISUNDEF(e->method_call.callable)) vtable.method_call = nullptr;
  if (Z_ISUNDEF(e->get_property.callable)) vtable.get_property = nullptr;
  if (Z_ISUNDEF(e->set_property.callable)) vtable.set_property = nullptr;
  GError *error = nullptr;
  e->id = g_dbus_connection_register_object(self, ZSTR_VAL(object_path), iface, &vtable, e,
                                            dbus_export_free, &error);
  if (e->id == 0) {
    // GLib did not take the struct (no notify runs): release it here
    callback_retire(&e->method_call);
    callback_retire(&e->get_property);
    callback_retire(&e->set_property);
    g_dbus_interface_info_unref(e->info);
    g_free(e);
    callback_drain();
    throw_gerror(error);
    RETURN_THROWS();
  }
  e->owner = G_OBJECT(self);
  g_object_weak_ref(e->owner, export_owner_gone, e);
  teardown_track_notified_keyed(e, G_OBJECT(self), export_clear);
  RETURN_LONG(e->id);
}
