// cairo_surface_t -> Gtk4\CairoSurface (registered via classes.h); implemented in CairoSurface.cpp.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {
void wrap_cairo_surface(cairo_surface_t *surface, zval *rv);
}  // namespace phpgtk
