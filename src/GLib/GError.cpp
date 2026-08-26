// Gtk4\GError: the exception class for GError ** APIs (built by core/gerror).
#include "php_gtk4.h"
#include "core/gerror.h"

/**
 * Gtk4\GError::getDomain(): string
 *
 * Error domain (quark name), e.g. "g-io-error-quark", "gdk-texture-error-quark". camelCase on
 * purpose: sits next to the inherited getCode()/getMessage().
 */
ZEND_METHOD(Gtk4_GError, getDomain) {
  ZEND_PARSE_PARAMETERS_NONE();
  zval rv;
  zval *d =
      zend_read_property(phpgtk::ce_GError, Z_OBJ_P(ZEND_THIS), ZEND_STRL("domain"), true, &rv);
  if (d == nullptr || Z_TYPE_P(d) != IS_STRING) RETURN_EMPTY_STRING();
  RETURN_COPY(d);
}
