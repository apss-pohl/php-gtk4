#include "gerror.h"

namespace phpgtk {

zend_class_entry *ce_GError = nullptr;

// Exception object from a GError: message, GLib code, domain (dynamic-free: declared property).
void gerror_to_php(const GError *error, zval *rv) {
  object_init_ex(rv, ce_GError);
  zend_update_property_string(zend_ce_exception, Z_OBJ_P(rv), ZEND_STRL("message"),
                              error->message != nullptr ? error->message : "");
  zend_update_property_long(zend_ce_exception, Z_OBJ_P(rv), ZEND_STRL("code"), error->code);
  zend_update_property_string(ce_GError, Z_OBJ_P(rv), ZEND_STRL("domain"),
                              g_quark_to_string(error->domain));
}

// Throw a GError as Gtk4\GError and free it.
void throw_gerror(GError *error) {
  zval ex;
  gerror_to_php(error, &ex);
  g_error_free(error);
  zend_throw_exception_object(&ex);
}

}  // namespace phpgtk

/**
 * Gtk4\GError::getDomain(): string
 *
 * Error domain (quark name), e.g. "g-io-error-quark", "gdk-texture-error-quark".
 */
ZEND_METHOD(Gtk4_GError, getDomain) {
  ZEND_PARSE_PARAMETERS_NONE();
  zval rv;
  zval *d =
      zend_read_property(phpgtk::ce_GError, Z_OBJ_P(ZEND_THIS), ZEND_STRL("domain"), true, &rv);
  if (d == nullptr || Z_TYPE_P(d) != IS_STRING) RETURN_EMPTY_STRING();
  RETURN_COPY(d);
}
