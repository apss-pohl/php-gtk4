// GError -> Gtk4\GError exception.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {
extern zend_class_entry *ce_GError;
// Throw error as a Gtk4\GError (taking ownership: g_error_free()d here); a nullptr (NULL
// result but no GError: a failed precondition) throws a plain Error instead.
// Returns nothing; callers RETURN_THROWS() afterwards.
void throw_gerror(GError *error);
// Build the exception object without throwing (for GValues that carry a GError).
void gerror_to_php(const GError *error, zval *rv);
// The other direction, for methods that take a GError *in* (GTask::return_error()): a new
// GError from a Gtk4\GError's message/code/domain (a PHP-constructed one without a domain
// gets "php-gtk4-error-quark"; GLib refuses a zero domain). Caller owns the result.
GError *gerror_from_php(zval *exception);
}  // namespace phpgtk
