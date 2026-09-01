// Gtk4\CairoSurface: a cairo_surface_t handle on the fundamental registry
// (cairo-gobject's CAIRO_GOBJECT_TYPE_SURFACE, cairo_surface_reference/cairo_surface_destroy).
#include "php_gtk4.h"

#include <cairo-gobject.h>

#include "classes.h"
#include "Cairo/CairoSurface.h"
#include "core/fundamental.h"

using namespace phpgtk;

namespace {

// cairo_surface_reference() as a RefFn.
gpointer surface_ref(gpointer p) {
  return cairo_surface_reference(static_cast<cairo_surface_t *>(p));
}

// cairo_surface_destroy() as an UnrefFn.
void surface_unref(gpointer p) {
  cairo_surface_destroy(static_cast<cairo_surface_t *>(p));
}

}  // namespace

namespace phpgtk {

// MINIT: put cairo_surface_t on the fundamental registry.
void register_CairoSurface(zend_class_entry *ce) {
  register_fundamental(FundamentalClass{
      .type = CAIRO_GOBJECT_TYPE_SURFACE, .ce = ce, .ref = surface_ref, .unref = surface_unref});
}

// C -> PHP: a handle holding its own reference on `surface`.
void wrap_cairo_surface(cairo_surface_t *surface, zval *rv) {
  wrap_fundamental(CAIRO_GOBJECT_TYPE_SURFACE, surface, rv);
}

}  // namespace phpgtk

// The cairo_surface_t behind $this.
#define SELF_SURFACE cairo_surface_t *surface = PHPGTK_FUNDAMENTAL_SELF(cairo_surface_t)

/**
 * Gtk4\CairoSurface::get_width(): int
 *
 * The width in pixels. Only an image surface has one.
 */
ZEND_METHOD(Gtk4_CairoSurface, get_width) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_SURFACE;
  if (cairo_surface_get_type(surface) != CAIRO_SURFACE_TYPE_IMAGE) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\CairoSurface::get_width(): not an image surface", 0);
    RETURN_THROWS();
  }
  RETURN_LONG(cairo_image_surface_get_width(surface));
}

/**
 * Gtk4\CairoSurface::get_height(): int
 *
 * The height in pixels. Only an image surface has one.
 */
ZEND_METHOD(Gtk4_CairoSurface, get_height) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_SURFACE;
  if (cairo_surface_get_type(surface) != CAIRO_SURFACE_TYPE_IMAGE) {
    zend_throw_exception(spl_ce_LogicException,
                         "Gtk4\\CairoSurface::get_height(): not an image surface", 0);
    RETURN_THROWS();
  }
  RETURN_LONG(cairo_image_surface_get_height(surface));
}

/**
 * Gtk4\CairoSurface::write_to_png(string $filename): void
 *
 * Write the surface out as a PNG file.
 */
ZEND_METHOD(Gtk4_CairoSurface, write_to_png) {
  zend_string *filename = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(filename)
  ZEND_PARSE_PARAMETERS_END();
  SELF_SURFACE;
  // A filename is bytes, not UTF-8 - only the embedded NUL is fatal here (cairo takes a char *).
  if (ZSTR_LEN(filename) != strlen(ZSTR_VAL(filename))) {
    zend_argument_value_error(1, "must not contain any null bytes");
    RETURN_THROWS();
  }
  const cairo_status_t status = cairo_surface_write_to_png(surface, ZSTR_VAL(filename));
  if (status != CAIRO_STATUS_SUCCESS) {
    zend_throw_error(nullptr, "Gtk4\\CairoSurface::write_to_png(): %s",
                     cairo_status_to_string(status));
    RETURN_THROWS();
  }
}
