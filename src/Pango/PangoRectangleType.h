// Gtk4\PangoRectangle: Pango's rectangle is a plain struct without a GType of its own, so the
// binding registers one - a boxed type copying the struct - for the value handle and for the
// generated code that fills one (PangoLayout's get_extents(), get_pixel_extents(),
// get_caret_pos(), get_cursor_pos() and index_to_pos(), which is the geometry a program needs to
// draw its own text). gen/gir/config.php maps Pango.Rectangle onto this macro.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

// The boxed GType standing in for PangoRectangle (registered on first use).
GType pango_rectangle_php_type();

}  // namespace phpgtk

#define PHPGTK_TYPE_PANGO_RECTANGLE (phpgtk::pango_rectangle_php_type())
