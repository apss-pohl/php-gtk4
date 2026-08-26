// Gtk4\CairoContext registration and the cairo_t -> PHP conversion.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {
void register_CairoContext(zend_class_entry *ce);
void wrap_cairo(cairo_t *cr, zval *rv);
}  // namespace phpgtk
