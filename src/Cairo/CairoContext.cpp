// Gtk4\CairoContext: a cairo_t handle on the fundamental registry
// (cairo-gobject's CAIRO_GOBJECT_TYPE_CONTEXT, cairo_reference/cairo_destroy).
#include "php_gtk4.h"

#include <cairo-gobject.h>

#include "core/boxed.h"
#include "classes.h"
#include "Cairo/CairoContext.h"
#include "core/fundamental.h"

using namespace phpgtk;

namespace {

// cairo_reference() as a RefFn.
gpointer cr_ref(gpointer p) {
  return cairo_reference(static_cast<cairo_t *>(p));
}

// cairo_destroy() as an UnrefFn.
void cr_unref(gpointer p) {
  cairo_destroy(static_cast<cairo_t *>(p));
}

}  // namespace

namespace phpgtk {

// MINIT: put cairo_t on the fundamental registry (marshal's boxed arm falls back to it).
void register_CairoContext(zend_class_entry *ce) {
  register_fundamental(FundamentalClass{
      .type = CAIRO_GOBJECT_TYPE_CONTEXT, .ce = ce, .ref = cr_ref, .unref = cr_unref});
}

// C -> PHP: a handle holding its own reference on `cr`.
void wrap_cairo(cairo_t *cr, zval *rv) {
  wrap_fundamental(CAIRO_GOBJECT_TYPE_CONTEXT, cr, rv);
}

}  // namespace phpgtk

// The cairo_t behind $this.
#define SELF_CR cairo_t *cr = PHPGTK_FUNDAMENTAL_SELF(cairo_t)

// Methods taking N doubles and returning nothing share one parser.
#define CAIRO_DOUBLE_METHOD(name, n, call) \
  ZEND_METHOD(Gtk4_CairoContext, name) {   \
    double a[n] = {};                      \
    ZEND_PARSE_PARAMETERS_START(n, n)      \
    for (double &d : a) {                  \
      Z_PARAM_DOUBLE(d)                    \
    }                                      \
    ZEND_PARSE_PARAMETERS_END();           \
    SELF_CR;                               \
    call;                                  \
  }

/**
 * Gtk4\CairoContext::set_source_rgb(float $red, float $green, float $blue): void
 */
CAIRO_DOUBLE_METHOD(set_source_rgb, 3, cairo_set_source_rgb(cr, a[0], a[1], a[2]))

/**
 * Gtk4\CairoContext::set_source_rgba(float $red, float $green, float $blue, float $alpha): void
 */
CAIRO_DOUBLE_METHOD(set_source_rgba, 4, cairo_set_source_rgba(cr, a[0], a[1], a[2], a[3]))

/**
 * Gtk4\CairoContext::set_source_color(GdkRGBA $color): void
 *
 * Source colour from a `GdkRGBA`.
 */
ZEND_METHOD(Gtk4_CairoContext, set_source_color) {
  zval *color;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(color, boxed_class_for_type(GDK_TYPE_RGBA)->ce)
  ZEND_PARSE_PARAMETERS_END();
  SELF_CR;
  auto *rgba = static_cast<GdkRGBA *>(unwrap_boxed(color, GDK_TYPE_RGBA));
  if (rgba == nullptr) RETURN_THROWS();
  gdk_cairo_set_source_rgba(cr, rgba);
}

/**
 * Gtk4\CairoContext::set_line_width(float $width): void
 */
CAIRO_DOUBLE_METHOD(set_line_width, 1, cairo_set_line_width(cr, a[0]))

/**
 * Gtk4\CairoContext::move_to(float $x, float $y): void
 */
CAIRO_DOUBLE_METHOD(move_to, 2, cairo_move_to(cr, a[0], a[1]))

/**
 * Gtk4\CairoContext::line_to(float $x, float $y): void
 */
CAIRO_DOUBLE_METHOD(line_to, 2, cairo_line_to(cr, a[0], a[1]))

/**
 * Gtk4\CairoContext::rectangle(float $x, float $y, float $width, float $height): void
 */
CAIRO_DOUBLE_METHOD(rectangle, 4, cairo_rectangle(cr, a[0], a[1], a[2], a[3]))

/**
 * Gtk4\CairoContext::arc(float $xc, float $yc, float $radius, float $angle1, float $angle2): void
 */
CAIRO_DOUBLE_METHOD(arc, 5, cairo_arc(cr, a[0], a[1], a[2], a[3], a[4]))

/**
 * Gtk4\CairoContext::translate(float $tx, float $ty): void
 */
CAIRO_DOUBLE_METHOD(translate, 2, cairo_translate(cr, a[0], a[1]))

/**
 * Gtk4\CairoContext::scale(float $sx, float $sy): void
 */
CAIRO_DOUBLE_METHOD(scale, 2, cairo_scale(cr, a[0], a[1]))

/**
 * Gtk4\CairoContext::rotate(float $angle): void
 */
CAIRO_DOUBLE_METHOD(rotate, 1, cairo_rotate(cr, a[0]))

/**
 * Gtk4\CairoContext::set_font_size(float $size): void
 */
CAIRO_DOUBLE_METHOD(set_font_size, 1, cairo_set_font_size(cr, a[0]))

// Methods without parameters share one parser.
#define CAIRO_VOID_METHOD(name, fn)      \
  ZEND_METHOD(Gtk4_CairoContext, name) { \
    ZEND_PARSE_PARAMETERS_NONE();        \
    SELF_CR;                             \
    fn(cr);                              \
  }

/**
 * Gtk4\CairoContext::close_path(): void
 */
CAIRO_VOID_METHOD(close_path, cairo_close_path)

/**
 * Gtk4\CairoContext::fill(): void
 */
CAIRO_VOID_METHOD(fill, cairo_fill)

/**
 * Gtk4\CairoContext::fill_preserve(): void
 */
CAIRO_VOID_METHOD(fill_preserve, cairo_fill_preserve)

/**
 * Gtk4\CairoContext::stroke(): void
 */
CAIRO_VOID_METHOD(stroke, cairo_stroke)

/**
 * Gtk4\CairoContext::stroke_preserve(): void
 */
CAIRO_VOID_METHOD(stroke_preserve, cairo_stroke_preserve)

/**
 * Gtk4\CairoContext::paint(): void
 */
CAIRO_VOID_METHOD(paint, cairo_paint)

/**
 * Gtk4\CairoContext::save(): void
 */
CAIRO_VOID_METHOD(save, cairo_save)

/**
 * Gtk4\CairoContext::restore(): void
 */
CAIRO_VOID_METHOD(restore, cairo_restore)

/**
 * Gtk4\CairoContext::show_text(string $text): void
 *
 * Draw the text at the current point with the toy font API.
 */
ZEND_METHOD(Gtk4_CairoContext, show_text) {
  zend_string *text;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(text)
  ZEND_PARSE_PARAMETERS_END();
  SELF_CR;
  cairo_show_text(cr, ZSTR_VAL(text));
}
