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

// Throw a GError as Gtk4\GError and free it. A NULL result *without* a GError is a failed
// GTK precondition (g_return_val_if_fail): the Gtk-CRITICAL said what, throw an Error here
// instead of dereferencing nothing.
void throw_gerror(GError *error) {
  if (error == nullptr) {
    zend_throw_error(
        nullptr,
        "GTK returned no result and no error (a precondition failed, see the CRITICAL above)");
    return;
  }
  zval ex;
  gerror_to_php(error, &ex);
  g_error_free(error);
  zend_throw_exception_object(&ex);
}

}  // namespace phpgtk
