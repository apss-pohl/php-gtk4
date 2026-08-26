// cairo_t -> Gtk4\CairoContext (registered via classes.h).
#pragma once
#include "php_gtk4.h"

namespace phpgtk {
void wrap_cairo(cairo_t *cr, zval *rv);
}  // namespace phpgtk
