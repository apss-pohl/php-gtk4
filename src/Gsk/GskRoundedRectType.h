// Gtk4\GskRoundedRect: GSK's rounded rectangle is a plain struct without a GType of its own, so
// the binding registers one - a boxed type copying the struct - for the value handle and for the
// generated code that takes or returns one (GskRoundedClipNode, GskBorderNode, the shadow nodes,
// GtkSnapshot::push_rounded_clip()). gen/gir/config.php maps Gsk.RoundedRect onto this macro.
#pragma once
#include "php_gtk4.h"

namespace phpgtk {

// The boxed GType standing in for GskRoundedRect (registered on first use).
GType gsk_rounded_rect_php_type();

}  // namespace phpgtk

#define PHPGTK_TYPE_GSK_ROUNDED_RECT (phpgtk::gsk_rounded_rect_php_type())
