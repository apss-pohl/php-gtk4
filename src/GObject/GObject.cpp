// Gtk4\GObject: the root class of every GObject handle (signals, property access by name).
#include "php_gtk4.h"
#include "core/gsignal.h"
#include "core/marshal.h"
#include "core/object.h"
#include "core/subtype.h"

using namespace phpgtk;

/**
 * Gtk4\GObject::__construct()
 *
 * A plain GObject - or, on a PHP subclass, an instance of that class' own GType (the way to
 * implement a GTK interface such as {@see GListModel} in PHP: `class M extends GObject implements
 * GListModel`).
 */
ZEND_METHOD(Gtk4_GObject, __construct) {
  ZEND_PARSE_PARAMETERS_NONE();
  GObject *obj = subtype_new(ZEND_THIS, nullptr);
  if (obj == nullptr) {
    if (EG(exception) != nullptr) RETURN_THROWS();
    obj = static_cast<GObject *>(g_object_new(G_TYPE_OBJECT, nullptr));
  }
  attach_new(object_from_zval(ZEND_THIS), obj);
}

/**
 * Gtk4\GObject::connect(string $signal, callable $handler): int
 *
 * Connect a handler to a signal (optionally detailed, e.g. "notify::title").
 */
ZEND_METHOD(Gtk4_GObject, connect) {
  signal_connect_method(INTERNAL_FUNCTION_PARAM_PASSTHRU, false);
}
/**
 * Gtk4\GObject::connect_after(string $signal, callable $handler): int
 *
 * Same as {@see connect()} but the handler runs after the default class handler.
 */
ZEND_METHOD(Gtk4_GObject, connect_after) {
  signal_connect_method(INTERNAL_FUNCTION_PARAM_PASSTHRU, true);
}

/**
 * Gtk4\GObject::emit(string $signal, mixed ...$args): mixed
 *
 * Emit a signal on this object with the given arguments (converted to the signal's parameter
 * types) and return the signal's return value, if any.
 */
ZEND_METHOD(Gtk4_GObject, emit) {
  signal_emit_method(INTERNAL_FUNCTION_PARAM_PASSTHRU);
}

/**
 * Gtk4\GObject::handler_disconnect(int $handler_id): void
 *
 * Disconnect a handler previously returned by connect(). No-op if already disconnected.
 */
ZEND_METHOD(Gtk4_GObject, handler_disconnect) {
  zend_long id;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_LONG(id)
  ZEND_PARSE_PARAMETERS_END();
  Object *self = object_from_zval(ZEND_THIS);
  if (self->obj == nullptr) return;  // nothing to disconnect from
  if (id > 0 && g_signal_handler_is_connected(self->obj, static_cast<gulong>(id))) {
    g_signal_handler_disconnect(self->obj, static_cast<gulong>(id));
  }
}

// Look up a GObject property by name or throw ValueError (nullptr returned).
static GParamSpec *require_property(GObject *obj, zend_string *name) {
  // A name that ends at a NUL would find a different property, or none, without saying why.
  if (!check_utf8(name, 1)) return nullptr;
  GParamSpec *spec = g_object_class_find_property(G_OBJECT_GET_CLASS(obj), ZSTR_VAL(name));
  if (spec == nullptr) {
    zend_argument_value_error(1, "no property '%s' on %s", ZSTR_VAL(name), G_OBJECT_TYPE_NAME(obj));
  }
  return spec;
}

/**
 * Gtk4\GObject::get_property(string $name): mixed
 *
 * Read a GObject property by name, converted to the matching PHP type.
 */
ZEND_METHOD(Gtk4_GObject, get_property) {
  zend_string *name;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(name)
  ZEND_PARSE_PARAMETERS_END();
  GObject *obj = PHPGTK_SELF(GObject, G_TYPE_OBJECT);
  GParamSpec *spec = require_property(obj, name);
  if (spec == nullptr) RETURN_THROWS();
  GValue v = G_VALUE_INIT;
  g_value_init(&v, spec->value_type);
  g_object_get_property(obj, spec->name, &v);
  to_php(&v, return_value);
  g_value_unset(&v);
}

/**
 * Gtk4\GObject::set_property(string $name, mixed $value): void
 *
 * Write a GObject property by name; the value is converted to the property's GType.
 */
ZEND_METHOD(Gtk4_GObject, set_property) {
  zend_string *name;
  zval *value;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_STR(name)
  Z_PARAM_ZVAL(value)
  ZEND_PARSE_PARAMETERS_END();
  GObject *obj = PHPGTK_SELF(GObject, G_TYPE_OBJECT);
  GParamSpec *spec = require_property(obj, name);
  if (spec == nullptr) RETURN_THROWS();
  GValue v = G_VALUE_INIT;
  if (!to_gvalue(value, spec->value_type, &v)) RETURN_THROWS();
  g_object_set_property(obj, spec->name, &v);
  g_value_unset(&v);
}
