// GError -> Gtk4\GError exception.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {
extern zend_class_entry *ce_GError;
// Throw error as a Gtk4\GError (taking ownership: g_error_free()d here).
// Returns nothing; callers RETURN_THROWS() afterwards.
void throw_gerror(GError *error);
// Build the exception object without throwing (for GValues that carry a GError).
void gerror_to_php(const GError *error, zval *rv);
}  // namespace phpgtk
