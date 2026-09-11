// D-Bus callables (signal subscriptions, exported objects): PHP callables GLib holds with a
// destroy notify it runs *from an idle* after unsubscribe/unregister - too late for RSHUTDOWN,
// so the teardown hook releases the callable itself (keyed, it knows the id) and the deferred
// notify only frees the struct, which is g_malloc'ed and never touches Zend.
#include "core/callback.h"
#include "core/teardown.h"
#include "core/variant.h"
#include "core/boxed.h"
#include "core/gerror.h"
#include "core/error.h"

#include <array>

namespace {

// One PHP callable GLib holds: the Callback (callback_invoke() reads callable/origin), the id
// the connection knows it by, and the connection while a weak ref watches it (a connection
// finalized before the request ends - GTestDBus::down() - takes its subscriptions with it,
// and the notify GLib then defers must not find the teardown entry pointing at a dead owner).
// g_new0'ed; dbus_callable_free() is its destroy notify.
struct DBusCallable {
  Callback cb;
  guint id;
  GObject *owner;
};

// An exported object's three handlers (any of them may be unset) and its interface info, which
// types the property values the handlers answer.
struct DBusExport {
  Callback method_call;
  Callback get_property;
  Callback set_property;
  GDBusInterfaceInfo *info;
  guint id;
  GObject *owner;
};

// A Callback in a g_malloc'ed struct: an ADDREF'd copy of the callable, or UNDEF for none.
void callback_init(Callback *cb, zend_fcall_info *fci, const char *origin) {
  if (ZEND_FCI_INITIALIZED(*fci)) {
    ZVAL_COPY(&cb->callable, &fci->function_name);
  } else {
    ZVAL_UNDEF(&cb->callable);
  }
  cb->origin = origin;
  cb->source_id = 0;
}

// Release a Callback's callable now (teardown, while Zend is up); the struct stays for GLib.
void callback_release(Callback *cb) {
  if (Z_ISUNDEF(cb->callable)) return;
  zval_ptr_dtor(&cb->callable);
  ZVAL_UNDEF(&cb->callable);
}

// Park a Callback's callable for callback_drain() (the request is alive: a notify GLib runs
// from its idle after the script unsubscribed/unregistered).
void callback_retire(Callback *cb) {
  if (Z_ISUNDEF(cb->callable)) return;
  callback_park(&cb->callable);
  ZVAL_UNDEF(&cb->callable);
}

// The connection of a subscription is gone (finalized with the request still running): the
// subscription went with it, so forget the teardown entry and retire the callable now; the
// deferred notify then only frees the struct.
void subscription_owner_gone(gpointer data, GObject * /*where_the_object_was*/) {
  auto *c = static_cast<DBusCallable *>(data);
  c->owner = nullptr;
  teardown_untrack_notified(c);
  callback_retire(&c->cb);
}

// Stop watching the owner (the entry is going away by another route).
void subscription_unwatch(DBusCallable *c) {
  if (c->owner == nullptr) return;
  g_object_weak_unref(c->owner, subscription_owner_gone, c);
  c->owner = nullptr;
}

// GDestroyNotify of a subscription: nothing to release when teardown or the owner's death did.
void dbus_callable_free(gpointer p) {
  auto *c = static_cast<DBusCallable *>(p);
  if (!Z_ISUNDEF(c->cb.callable)) {
    subscription_unwatch(c);
    teardown_untrack_notified(c);
    callback_retire(&c->cb);
  }
  g_free(c);
}

// teardown clear (keyed) of a subscription: drop the callable, then unsubscribe - the notify
// GLib defers to an idle finds nothing left to release.
void subscription_clear(GObject *owner, gpointer key) {
  auto *c = static_cast<DBusCallable *>(key);
  subscription_unwatch(c);
  callback_release(&c->cb);
  g_dbus_connection_signal_unsubscribe(G_DBUS_CONNECTION(owner), c->id);
}

// GDBusSignalCallback: (GDBusConnection, ?string sender, string path, string iface, string
// signal, list parameters) -> void.
void signal_trampoline(GDBusConnection *connection, const gchar *sender_name,
                       const gchar *object_path, const gchar *interface_name,
                       const gchar *signal_name, GVariant *parameters, gpointer user_data) {
  auto *c = static_cast<DBusCallable *>(user_data);
  std::array<zval, 6> args{};
  wrap(G_OBJECT(connection), args.data());
  if (sender_name != nullptr) {
    ZVAL_STRING(&args[1], sender_name);
  } else {
    ZVAL_NULL(&args[1]);
  }
  ZVAL_STRING(&args[2], object_path);
  ZVAL_STRING(&args[3], interface_name);
  ZVAL_STRING(&args[4], signal_name);
  variant_to_php(parameters, &args[5]);
  zval retval;
  callback_invoke(&c->cb, args.size(), args.data(), &retval);
  zval_ptr_dtor(&retval);
  for (zval &a : args) zval_ptr_dtor(&a);
}

// Whether any of the three handlers is still held.
bool export_live(const DBusExport *e) {
  return !Z_ISUNDEF(e->method_call.callable) || !Z_ISUNDEF(e->get_property.callable) ||
         !Z_ISUNDEF(e->set_property.callable);
}

// The connection of an exported object is gone: as subscription_owner_gone().
void export_owner_gone(gpointer data, GObject * /*where_the_object_was*/) {
  auto *e = static_cast<DBusExport *>(data);
  e->owner = nullptr;
  teardown_untrack_notified(e);
  callback_retire(&e->method_call);
  callback_retire(&e->get_property);
  callback_retire(&e->set_property);
}

// Stop watching the owner.
void export_unwatch(DBusExport *e) {
  if (e->owner == nullptr) return;
  g_object_weak_unref(e->owner, export_owner_gone, e);
  e->owner = nullptr;
}

// GDestroyNotify of an exported object (deferred to an idle by GLib, as above).
void dbus_export_free(gpointer p) {
  auto *e = static_cast<DBusExport *>(p);
  if (export_live(e)) {
    export_unwatch(e);
    teardown_untrack_notified(e);
    callback_retire(&e->method_call);
    callback_retire(&e->get_property);
    callback_retire(&e->set_property);
  }
  g_dbus_interface_info_unref(e->info);
  g_free(e);
}

// teardown clear (keyed) of an exported object.
void export_clear(GObject *owner, gpointer key) {
  auto *e = static_cast<DBusExport *>(key);
  export_unwatch(e);
  callback_release(&e->method_call);
  callback_release(&e->get_property);
  callback_release(&e->set_property);
  g_dbus_connection_unregister_object(G_DBUS_CONNECTION(owner), e->id);
}

// The four strings every vtable handler is told: connection, sender, path, interface.
void export_args(GDBusConnection *connection, const gchar *sender, const gchar *object_path,
                 const gchar *interface_name, zval *args) {
  wrap(G_OBJECT(connection), args);  // args[0]
  ZVAL_STRING(&args[1], sender);
  ZVAL_STRING(&args[2], object_path);
  ZVAL_STRING(&args[3], interface_name);
}

// GDBusInterfaceMethodCallFunc: (connection, sender, path, iface, method, list parameters,
// GDBusMethodInvocation) -> void. The handler answers through the invocation.
void method_call_trampoline(GDBusConnection *connection, const gchar *sender,
                            const gchar *object_path, const gchar *interface_name,
                            const gchar *method_name, GVariant *parameters,
                            GDBusMethodInvocation *invocation, gpointer user_data) {
  auto *e = static_cast<DBusExport *>(user_data);
  std::array<zval, 7> args{};
  export_args(connection, sender, object_path, interface_name, args.data());
  ZVAL_STRING(&args[4], method_name);
  variant_to_php(parameters, &args[5]);
  wrap(G_OBJECT(invocation), &args[6]);
  zval retval;
  callback_invoke(&e->method_call, args.size(), args.data(), &retval);
  zval_ptr_dtor(&retval);
  for (zval &a : args) zval_ptr_dtor(&a);
}

// GDBusInterfaceGetPropertyFunc: (connection, sender, path, iface, property) -> mixed, converted
// to the property's signature from the interface info (the type a D-Bus client expects, which a
// plain PHP value could not spell: 'u', 'o', 'a{sv}'). A throw or null is a D-Bus error reply.
GVariant *get_property_trampoline(GDBusConnection *connection, const gchar *sender,
                                  const gchar *object_path, const gchar *interface_name,
                                  const gchar *property_name, GError **error, gpointer user_data) {
  auto *e = static_cast<DBusExport *>(user_data);
  std::array<zval, 5> args{};
  export_args(connection, sender, object_path, interface_name, args.data());
  ZVAL_STRING(&args[4], property_name);
  zval retval;
  const bool ok = callback_invoke(&e->get_property, args.size(), args.data(), &retval);
  for (zval &a : args) zval_ptr_dtor(&a);
  GVariant *result = nullptr;
  if (ok && !Z_ISUNDEF(retval) && Z_TYPE(retval) != IS_NULL) {
    GDBusPropertyInfo *prop = g_dbus_interface_info_lookup_property(e->info, property_name);
    GVariantType *type = prop != nullptr ? g_variant_type_new(prop->signature) : nullptr;
    result = php_to_variant(&retval, type);
    if (type != nullptr) g_variant_type_free(type);
    if (result != nullptr) {
      g_variant_ref_sink(result);
    } else {
      // the conversion refused the value: a TypeError, reported like the handler's own throw
      report_pending_exception(e->get_property.origin);
    }
  }
  zval_ptr_dtor(&retval);
  if (result == nullptr) {
    g_set_error(error, G_DBUS_ERROR, G_DBUS_ERROR_FAILED, "%s answered nothing usable for %s",
                e->get_property.origin, property_name);
  }
  return result;
}

// GDBusInterfaceSetPropertyFunc: (connection, sender, path, iface, property, mixed value) -> bool.
gboolean set_property_trampoline(GDBusConnection *connection, const gchar *sender,
                                 const gchar *object_path, const gchar *interface_name,
                                 const gchar *property_name, GVariant *value, GError **error,
                                 gpointer user_data) {
  auto *e = static_cast<DBusExport *>(user_data);
  std::array<zval, 6> args{};
  export_args(connection, sender, object_path, interface_name, args.data());
  ZVAL_STRING(&args[4], property_name);
  variant_to_php(value, &args[5]);
  zval retval;
  const bool ok = callback_invoke(&e->set_property, args.size(), args.data(), &retval);
  for (zval &a : args) zval_ptr_dtor(&a);
  const gboolean accepted = ok && !Z_ISUNDEF(retval) && zend_is_true(&retval) ? TRUE : FALSE;
  zval_ptr_dtor(&retval);
  if (accepted == FALSE) {
    g_set_error(error, G_DBUS_ERROR, G_DBUS_ERROR_FAILED, "%s refused %s", e->set_property.origin,
                property_name);
  }
  return accepted;
}

// The vtable GLib copies at registration; a handler the script did not give is NULL, and GLib
// then answers Get/Set itself through method_call (org.freedesktop.DBus.Properties).
const GDBusInterfaceVTable export_vtable_all = {method_call_trampoline, get_property_trampoline,
                                                set_property_trampoline, {}};

// GAsyncReadyCallback of call(): (GDBusConnection, GAsyncResult) -> void, then the Callback is
// done (one-shot, as the generated async methods do it).
void dbus_call_ready(GObject *source_object, GAsyncResult *res, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  std::array<zval, 2> args{};
  wrap(source_object, args.data());
  wrap(res != nullptr ? G_OBJECT(res) : nullptr, &args[1]);
  zval ret;
  callback_invoke(cb, args.size(), args.data(), &ret);
  zval_ptr_dtor(&ret);
  for (zval &a : args) zval_ptr_dtor(&a);
  callback_free(cb);
  callback_drain();
}

// A D-Bus body from a PHP list and an optional tuple signature the caller spelled ("(su)").
GVariant *dbus_body(zval *parameters, zend_string *signature, uint32_t arg_num, uint32_t sig_arg) {
  GVariantType *type = nullptr;
  if (signature != nullptr) {
    if (!g_variant_type_string_is_valid(ZSTR_VAL(signature))) {
      zend_argument_value_error(sig_arg, "must be a valid GVariant type string, \"%s\" given",
                                ZSTR_VAL(signature));
      return nullptr;
    }
    type = g_variant_type_new(ZSTR_VAL(signature));
  }
  GVariant *body = php_to_variant_tuple(parameters, type, arg_num);
  if (type != nullptr) g_variant_type_free(type);
  if (body != nullptr) g_variant_ref_sink(body);
  return body;
}

}  // namespace
