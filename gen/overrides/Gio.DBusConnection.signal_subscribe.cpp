/**
 * public function signal_subscribe(?string $sender, ?string $interface_name, ?string $member, ?string $object_path, ?string $arg0, int $flags, callable $callback): int
 * Subscribes to signals on connection.
 *
 * Every null widens the match. `$callback` is `function (GDBusConnection $connection, ?string
 * $sender, string $object_path, string $interface_name, string $signal_name, array $parameters)`,
 * the parameters the signal's tuple as a list. The id is for {@see signal_unsubscribe()}; a
 * subscription still alive at request shutdown is removed then.
 */
ZEND_METHOD(Gtk4_GDBusConnection, signal_subscribe) {
  zend_string *sender = nullptr;
  zend_string *interface_name = nullptr;
  zend_string *member = nullptr;
  zend_string *object_path = nullptr;
  zend_string *arg0 = nullptr;
  zend_long flags;
  zend_fcall_info fci = {};
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(7, 7)
  Z_PARAM_STR_OR_NULL(sender)
  Z_PARAM_STR_OR_NULL(interface_name)
  Z_PARAM_STR_OR_NULL(member)
  Z_PARAM_STR_OR_NULL(object_path)
  Z_PARAM_STR_OR_NULL(arg0)
  Z_PARAM_LONG(flags)
  Z_PARAM_FUNC(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GDBusConnection *self = PHPGTK_SELF(GDBusConnection, G_TYPE_DBUS_CONNECTION);
  if (sender != nullptr && !phpgtk::check_utf8(sender, 1)) RETURN_THROWS();
  if (sender != nullptr && !g_dbus_is_name(ZSTR_VAL(sender))) {
    zend_argument_value_error(1, "must be a D-Bus name");
    RETURN_THROWS();
  }
  if (interface_name != nullptr && !phpgtk::check_utf8(interface_name, 2)) RETURN_THROWS();
  if (interface_name != nullptr && !g_dbus_is_interface_name(ZSTR_VAL(interface_name))) {
    zend_argument_value_error(2, "must be a D-Bus interface name");
    RETURN_THROWS();
  }
  if (member != nullptr && !phpgtk::check_utf8(member, 3)) RETURN_THROWS();
  if (member != nullptr && !g_dbus_is_member_name(ZSTR_VAL(member))) {
    zend_argument_value_error(3, "must be a D-Bus member name");
    RETURN_THROWS();
  }
  if (object_path != nullptr && !phpgtk::check_utf8(object_path, 4)) RETURN_THROWS();
  if (object_path != nullptr && !g_variant_is_object_path(ZSTR_VAL(object_path))) {
    zend_argument_value_error(4, "must be a D-Bus object path");
    RETURN_THROWS();
  }
  if (arg0 != nullptr && !phpgtk::check_utf8(arg0, 5)) RETURN_THROWS();
  if (!phpgtk::check_flags(G_TYPE_DBUS_SIGNAL_FLAGS, flags, 6)) RETURN_THROWS();
  auto *c = g_new0(DBusCallable, 1);
  callback_init(&c->cb, &fci, "GDBusConnection::signal_subscribe");
  c->id = g_dbus_connection_signal_subscribe(
      self, sender != nullptr ? ZSTR_VAL(sender) : nullptr,
      interface_name != nullptr ? ZSTR_VAL(interface_name) : nullptr,
      member != nullptr ? ZSTR_VAL(member) : nullptr,
      object_path != nullptr ? ZSTR_VAL(object_path) : nullptr,
      arg0 != nullptr ? ZSTR_VAL(arg0) : nullptr, static_cast<GDBusSignalFlags>(flags),
      signal_trampoline, c, dbus_callable_free);
  c->owner = G_OBJECT(self);
  g_object_weak_ref(c->owner, subscription_owner_gone, c);
  teardown_track_notified_keyed(c, G_OBJECT(self), subscription_clear);
  RETURN_LONG(c->id);
}
