/**
 * public function set_draw_func(?callable $draw_func): void
 * Install (or with null, remove) the draw function: `function (GtkDrawingArea $area,
 * CairoContext $cr, int $width, int $height): void`. Kept until replaced or the widget dies.
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
