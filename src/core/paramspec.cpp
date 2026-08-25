#include "paramspec.h"

#include "classes.h"
#include "marshal.h"

namespace {

struct ParamSpecObject {
  GParamSpec *spec;
  zend_object std;
};

zend_object_handlers handlers;

// zend_object -> our embedding struct.
ParamSpecObject *from_zend(zend_object *o) {
  return reinterpret_cast<ParamSpecObject *>(reinterpret_cast<char *>(o) -
                                             XtOffsetOf(ParamSpecObject, std));
}

// The GParamSpec behind $this.
GParamSpec *self_spec(zend_execute_data *execute_data) {
  return from_zend(Z_OBJ_P(ZEND_THIS))->spec;
}

// create_object handler; the spec is set by wrap_param_spec().
zend_object *create_object(zend_class_entry *ce) {
  auto *self = static_cast<ParamSpecObject *>(zend_object_alloc(sizeof(ParamSpecObject), ce));
  self->spec = nullptr;
  zend_object_std_init(&self->std, ce);
  object_properties_init(&self->std, ce);
  self->std.handlers = &handlers;
  return &self->std;
}

// free_obj handler: drop our ref on the spec.
void free_obj(zend_object *o) {
  ParamSpecObject *self = from_zend(o);
  if (self->spec != nullptr) g_param_spec_unref(self->spec);
  zend_object_std_dtor(o);
}

}  // namespace

/**
 * Gtk4\GParamSpec::get_name(): string
 */
ZEND_METHOD(Gtk4_GParamSpec, get_name) {
  ZEND_PARSE_PARAMETERS_NONE();
  RETURN_STRING(g_param_spec_get_name(self_spec(execute_data)));
}

/**
 * Gtk4\GParamSpec::get_nick(): ?string
 */
ZEND_METHOD(Gtk4_GParamSpec, get_nick) {
  ZEND_PARSE_PARAMETERS_NONE();
  const gchar *s = g_param_spec_get_nick(self_spec(execute_data));
  if (s == nullptr) RETURN_NULL();
  RETURN_STRING(s);
}

/**
 * Gtk4\GParamSpec::get_blurb(): ?string
 */
ZEND_METHOD(Gtk4_GParamSpec, get_blurb) {
  ZEND_PARSE_PARAMETERS_NONE();
  const gchar *s = g_param_spec_get_blurb(self_spec(execute_data));
  if (s == nullptr) RETURN_NULL();
  RETURN_STRING(s);
}

/**
 * Gtk4\GParamSpec::get_value_type(): string
 *
 * GType name of the value, e.g. "gchararray", "gint", "GtkWindow".
 */
ZEND_METHOD(Gtk4_GParamSpec, get_value_type) {
  ZEND_PARSE_PARAMETERS_NONE();
  RETURN_STRING(g_type_name(self_spec(execute_data)->value_type));
}

/**
 * Gtk4\GParamSpec::get_flags(): int
 *
 * GParamFlags bitmask (READABLE = 1, WRITABLE = 2, CONSTRUCT_ONLY = 8, ...).
 */
ZEND_METHOD(Gtk4_GParamSpec, get_flags) {
  ZEND_PARSE_PARAMETERS_NONE();
  RETURN_LONG(static_cast<zend_long>(self_spec(execute_data)->flags));
}

/**
 * Gtk4\GParamSpec::is_readable(): bool
 */
ZEND_METHOD(Gtk4_GParamSpec, is_readable) {
  ZEND_PARSE_PARAMETERS_NONE();
  RETURN_BOOL((self_spec(execute_data)->flags & G_PARAM_READABLE) != 0);
}

/**
 * Gtk4\GParamSpec::is_writable(): bool
 */
ZEND_METHOD(Gtk4_GParamSpec, is_writable) {
  ZEND_PARSE_PARAMETERS_NONE();
  RETURN_BOOL((self_spec(execute_data)->flags & G_PARAM_WRITABLE) != 0);
}

/**
 * Gtk4\GParamSpec::get_default_value(): mixed
 *
 * The property's default, converted like get_property(); null if the type is unsupported.
 */
ZEND_METHOD(Gtk4_GParamSpec, get_default_value) {
  ZEND_PARSE_PARAMETERS_NONE();
  GParamSpec *spec = self_spec(execute_data);
  if (!phpgtk::to_php_supported(spec->value_type)) RETURN_NULL();
  phpgtk::to_php(g_param_spec_get_default_value(spec), return_value);
}

namespace phpgtk {

// MINIT: install the object handlers on the class entry.
void register_GParamSpec_handlers(zend_class_entry *ce) {
  memcpy(&handlers, &std_object_handlers, sizeof(zend_object_handlers));
  handlers.offset = XtOffsetOf(ParamSpecObject, std);
  handlers.free_obj = free_obj;
  handlers.clone_obj = nullptr;
  ce->create_object = create_object;
}

// C -> PHP: new handle holding a ref on `spec` (null for nullptr).
void wrap_param_spec(GParamSpec *spec, zval *rv) {
  if (spec == nullptr) {
    ZVAL_NULL(rv);
    return;
  }
  object_init_ex(rv, ce_GParamSpec);
  from_zend(Z_OBJ_P(rv))->spec = g_param_spec_ref(spec);
}

}  // namespace phpgtk
