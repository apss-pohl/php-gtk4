// Gtk4\GtkDrawingArea: the first hand-written "notified"-scope C callback
// (GtkDrawingAreaDrawFunc + GDestroyNotify) - the template for the generator.
#include "php_gtk4.h"
#include "core/cairo.h"
#include "core/callback.h"
#include "core/object.h"
#include "core/teardown.h"

using namespace phpgtk;

namespace {

// GtkDrawingAreaDrawFunc trampoline: (GtkDrawingArea, CairoContext, int, int) -> void.
void draw_func(GtkDrawingArea *area, cairo_t *cr, int width, int height, gpointer data) {
  auto *cb = static_cast<Callback *>(data);
  zval args[4];
  wrap(G_OBJECT(area), &args[0]);
  wrap_cairo(cr, &args[1]);
  ZVAL_LONG(&args[2], width);
  ZVAL_LONG(&args[3], height);
  zval retval;
  callback_invoke(cb, 4, args, &retval);
  zval_ptr_dtor(&retval);
  for (zval &a : args) zval_ptr_dtor(&a);
}

// GDestroyNotify: GTK dropped the draw func (replaced, widget finalized or RSHUTDOWN).
void draw_func_free(gpointer data) {
  teardown_untrack_notified(data);
  callback_free(data);
}

// teardown clear hook: make the area release its PHP callable.
void draw_func_clear(GObject *owner) {
  gtk_drawing_area_set_draw_func(GTK_DRAWING_AREA(owner), nullptr, nullptr, nullptr);
}

}  // namespace

/**
 * Gtk4\GtkDrawingArea::__construct()
 */
ZEND_METHOD(Gtk4_GtkDrawingArea, __construct) {
  ZEND_PARSE_PARAMETERS_NONE();
  attach_new(object_from_zval(ZEND_THIS), G_OBJECT(gtk_drawing_area_new()));
}

/**
 * Gtk4\GtkDrawingArea::set_draw_func(?callable $drawFunc): void
 *
 * Install (or with null, remove) the draw function: `function (GtkDrawingArea $area, CairoContext
 * $cr, int $width, int $height): void`. Kept until replaced or the widget dies.
 */
ZEND_METHOD(Gtk4_GtkDrawingArea, set_draw_func) {
  zend_fcall_info fci;
  zend_fcall_info_cache fcc;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_FUNC_OR_NULL(fci, fcc)
  ZEND_PARSE_PARAMETERS_END();
  GtkDrawingArea *area = PHPGTK_SELF(GtkDrawingArea, GTK_TYPE_DRAWING_AREA);
  if (!ZEND_FCI_INITIALIZED(fci)) {
    gtk_drawing_area_set_draw_func(area, nullptr, nullptr, nullptr);
  } else {
    Callback *cb = callback_new(&fci.function_name, "GtkDrawingArea::set_draw_func");
    teardown_track_notified(cb, G_OBJECT(area), draw_func_clear);
    gtk_drawing_area_set_draw_func(area, draw_func, cb, draw_func_free);
  }
  callback_drain();  // the previous draw func's notify ran inside the setter
}

/**
 * Gtk4\GtkDrawingArea::set_content_width(int $width): void
 */
ZEND_METHOD(Gtk4_GtkDrawingArea, set_content_width) {
  zend_long width;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_LONG(width)
  ZEND_PARSE_PARAMETERS_END();
  if (width < 0) {
    zend_argument_value_error(1, "must be greater than or equal to 0");
    RETURN_THROWS();
  }
  GtkDrawingArea *area = PHPGTK_SELF(GtkDrawingArea, GTK_TYPE_DRAWING_AREA);
  gtk_drawing_area_set_content_width(area, static_cast<int>(width));
}

/**
 * Gtk4\GtkDrawingArea::get_content_width(): int
 */
ZEND_METHOD(Gtk4_GtkDrawingArea, get_content_width) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkDrawingArea *area = PHPGTK_SELF(GtkDrawingArea, GTK_TYPE_DRAWING_AREA);
  RETURN_LONG(gtk_drawing_area_get_content_width(area));
}

/**
 * Gtk4\GtkDrawingArea::set_content_height(int $height): void
 */
ZEND_METHOD(Gtk4_GtkDrawingArea, set_content_height) {
  zend_long height;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_LONG(height)
  ZEND_PARSE_PARAMETERS_END();
  if (height < 0) {
    zend_argument_value_error(1, "must be greater than or equal to 0");
    RETURN_THROWS();
  }
  GtkDrawingArea *area = PHPGTK_SELF(GtkDrawingArea, GTK_TYPE_DRAWING_AREA);
  gtk_drawing_area_set_content_height(area, static_cast<int>(height));
}

/**
 * Gtk4\GtkDrawingArea::get_content_height(): int
 */
ZEND_METHOD(Gtk4_GtkDrawingArea, get_content_height) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkDrawingArea *area = PHPGTK_SELF(GtkDrawingArea, GTK_TYPE_DRAWING_AREA);
  RETURN_LONG(gtk_drawing_area_get_content_height(area));
}
