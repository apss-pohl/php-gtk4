/**
 * public function allocate(int $width, int $height, int $baseline = -1, int $x = 0, int $y = 0): void
 * Assign the widget its size and position inside its parent's allocation.
 *
 * What a layout manager's `vfunc_allocate()` calls for each child. GIR's last parameter is a
 * `GskTransform *` placing the child relative to the parent; GSK is unbound, so the position is
 * taken as $x/$y here and turned into the translation every layout manager wants (no transform
 * at all when both are 0, which is what GTK's own containers pass for a child at the origin).
 */
ZEND_METHOD(Gtk4_GtkWidget, allocate) {
  zend_long width = 0;
  zend_long height = 0;
  zend_long baseline = -1;
  zend_long x = 0;
  zend_long y = 0;
  ZEND_PARSE_PARAMETERS_START(2, 5)
  Z_PARAM_LONG(width)
  Z_PARAM_LONG(height)
  Z_PARAM_OPTIONAL
  Z_PARAM_LONG(baseline)
  Z_PARAM_LONG(x)
  Z_PARAM_LONG(y)
  ZEND_PARSE_PARAMETERS_END();
  if (!check_range<int>(width, 1) || !check_range<int>(height, 2) ||
      !check_range<int>(baseline, 3) || !check_range<int>(x, 4) || !check_range<int>(y, 5)) {
    RETURN_THROWS();
  }
  GtkWidget *self = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  GskTransform *at = nullptr;
  if (x != 0 || y != 0) {
    const graphene_point_t p = {.x = static_cast<float>(x), .y = static_cast<float>(y)};
    at = gsk_transform_translate(nullptr, &p);  // consumed by gtk_widget_allocate()
  }
  gtk_widget_allocate(self, static_cast<int>(width), static_cast<int>(height),
                      static_cast<int>(baseline), at);
}
