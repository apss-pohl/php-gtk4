// Gtk4\GParamSpec: read-only view of a property description (a fundamental handle).
#include "php_gtk4.h"
#include "core/fundamental.h"
#include "core/marshal.h"

using namespace phpgtk;

// The GParamSpec behind $this.
static GParamSpec *self_spec(zend_execute_data *execute_data) {
  return PHPGTK_FUNDAMENTAL_SELF(GParamSpec);
}

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
  PHPGTK_RETURN_STRING_OR_NULL(g_param_spec_get_nick(self_spec(execute_data)));
}

/**
 * Gtk4\GParamSpec::get_blurb(): ?string
 */
ZEND_METHOD(Gtk4_GParamSpec, get_blurb) {
  ZEND_PARSE_PARAMETERS_NONE();
  PHPGTK_RETURN_STRING_OR_NULL(g_param_spec_get_blurb(self_spec(execute_data)));
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
  if (!to_php_supported(spec->value_type)) RETURN_NULL();
  to_php(g_param_spec_get_default_value(spec), return_value);
}
