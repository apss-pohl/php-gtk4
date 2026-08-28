// GtkDrawingAreaDrawFunc (notified scope) - the template for "notified" callbacks.
#include "Cairo/CairoContext.h"
#include "core/callback.h"
#include "core/teardown.h"

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
