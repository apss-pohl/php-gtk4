#include "paramspec.h"

#include "fundamental.h"
#include "marshal.h"

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
  if (!to_php_supported(spec->value_type)) RETURN_NULL();
  to_php(g_param_spec_get_default_value(spec), return_value);
}

namespace phpgtk {

// g_param_spec_ref/unref with the registry's gpointer signature.
static gpointer spec_ref(gpointer p) {
  return g_param_spec_ref(static_cast<GParamSpec *>(p));
}
// Unref counterpart for the registry.
static void spec_unref(gpointer p) {
  g_param_spec_unref(static_cast<GParamSpec *>(p));
}

// MINIT: GParamSpec is the first fundamental handle type.
void register_GParamSpec(zend_class_entry *ce) {
  register_fundamental(
      FundamentalClass{.type = G_TYPE_PARAM, .ce = ce, .ref = spec_ref, .unref = spec_unref});
}

// C -> PHP through the fundamental registry.
void wrap_param_spec(GParamSpec *spec, zval *rv) {
  wrap_fundamental(G_TYPE_PARAM, spec, rv);
}

}  // namespace phpgtk
