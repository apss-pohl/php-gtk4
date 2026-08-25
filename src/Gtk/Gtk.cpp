// Gtk4\GObject methods and the static Gtk4\Gtk class.
#include "php_gtk4.h"
#include "core/error.h"
#include "core/gsignal.h"
#include "core/marshal.h"
#include "core/object.h"

using namespace phpgtk;

// ---------------------------------------------------------------- GObject

ZEND_METHOD(Gtk4_GObject, connect) {
  signal_connect_method(INTERNAL_FUNCTION_PARAM_PASSTHRU, false);
}
ZEND_METHOD(Gtk4_GObject, connect_after) {
  signal_connect_method(INTERNAL_FUNCTION_PARAM_PASSTHRU, true);
}

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

static GParamSpec *require_property(GObject *obj, zend_string *name) {
  GParamSpec *spec = g_object_class_find_property(G_OBJECT_GET_CLASS(obj), ZSTR_VAL(name));
  if (spec == nullptr) {
    zend_value_error("no property '%s' on %s", ZSTR_VAL(name), G_OBJECT_TYPE_NAME(obj));
  }
  return spec;
}

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

// ---------------------------------------------------------------- Gtk (static)

static GMainLoop *main_loop = nullptr;
static bool quit_pending = false;  // main_quit() called before main()

ZEND_METHOD(Gtk4_Gtk, init) {
  ZEND_PARSE_PARAMETERS_NONE();
  RETURN_BOOL(gtk_init_check());
}

// GTK4 has no gtk_main(); run a GMainLoop on the default context.
ZEND_METHOD(Gtk4_Gtk, main) {
  ZEND_PARSE_PARAMETERS_NONE();
  if (main_loop != nullptr) {
    zend_throw_exception(spl_ce_LogicException, "Gtk::main() is already running", 0);
    RETURN_THROWS();
  }
  if (quit_pending) {
    quit_pending = false;
    return;
  }
  main_loop = g_main_loop_new(nullptr, FALSE);
  g_main_loop_run(main_loop);
  g_main_loop_unref(main_loop);
  main_loop = nullptr;
}

ZEND_METHOD(Gtk4_Gtk, main_quit) {
  ZEND_PARSE_PARAMETERS_NONE();
  if (main_loop != nullptr) {
    g_main_loop_quit(main_loop);
  } else {
    quit_pending = true;
  }
}

ZEND_METHOD(Gtk4_Gtk, set_exception_handler) {
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  set_exception_handler(ZEND_FCI_INITIALIZED(fci) ? &fci.function_name : nullptr);
}
