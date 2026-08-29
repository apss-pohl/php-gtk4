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

// Gtk4\GError -> new GError (message, code, domain; see gerror.h for the domain fallback).
GError *gerror_from_php(zval *exception) {
  zend_object *obj = Z_OBJ_P(exception);
  zval rv;
  zval *message = zend_read_property(zend_ce_exception, obj, ZEND_STRL("message"), true, &rv);
  zend_string *msg = zval_get_string(message);
  zval rv2;
  zval *code = zend_read_property(zend_ce_exception, obj, ZEND_STRL("code"), true, &rv2);
  const zend_long code_l = zval_get_long(code);
  zval rv3;
  zval *domain = zend_read_property(ce_GError, obj, ZEND_STRL("domain"), true, &rv3);
  zend_string *dom = zval_get_string(domain);
  const GQuark quark =
      g_quark_from_string(ZSTR_LEN(dom) > 0 ? ZSTR_VAL(dom) : "php-gtk4-error-quark");
  GError *error = g_error_new_literal(quark, static_cast<gint>(code_l), ZSTR_VAL(msg));
  zend_string_release(msg);
  zend_string_release(dom);
  return error;
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
