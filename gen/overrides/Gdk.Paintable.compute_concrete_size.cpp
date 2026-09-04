/**
 * public function compute_concrete_size(float $specified_width, float $specified_height, float $default_width, float $default_height): array
 * Applies the sizing algorithm outlined in the CSS Image spec to the given $paintable.
 *
 * GDK states the algorithm's own domain as four `g_return_if_fail()`s - a specified size is
 * either 0 ("not specified") or positive, and a default size has to be positive - and answers
 * `[0.0, 0.0]` when one is violated. That is a value the script can build from arithmetic, so
 * the range is checked here rather than left to GTK's precondition.
 */
ZEND_METHOD(Gtk4_GdkPaintable, compute_concrete_size) {
  double specified_width;
  double specified_height;
  double default_width;
  double default_height;
  ZEND_PARSE_PARAMETERS_START(4, 4)
  Z_PARAM_DOUBLE(specified_width)
  Z_PARAM_DOUBLE(specified_height)
  Z_PARAM_DOUBLE(default_width)
  Z_PARAM_DOUBLE(default_height)
  ZEND_PARSE_PARAMETERS_END();
  GdkPaintable *self = PHPGTK_SELF(GdkPaintable, GDK_TYPE_PAINTABLE);
  if (specified_width < 0) {
    zend_argument_value_error(1, "must be greater than or equal to 0");
    RETURN_THROWS();
  }
  if (specified_height < 0) {
    zend_argument_value_error(2, "must be greater than or equal to 0");
    RETURN_THROWS();
  }
  if (default_width <= 0) {
    zend_argument_value_error(3, "must be greater than 0");
    RETURN_THROWS();
  }
  if (default_height <= 0) {
    zend_argument_value_error(4, "must be greater than 0");
    RETURN_THROWS();
  }
  double concrete_width = 0;
  double concrete_height = 0;
  gdk_paintable_compute_concrete_size(self, specified_width, specified_height, default_width,
                                      default_height, &concrete_width, &concrete_height);
  array_init_size(return_value, 2);
  {
    zval item;
    ZVAL_DOUBLE(&item, concrete_width);
    add_next_index_zval(return_value, &item);
  }
  {
    zval item;
    ZVAL_DOUBLE(&item, concrete_height);
    add_next_index_zval(return_value, &item);
  }
}
