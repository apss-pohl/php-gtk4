/**
 * public function set_dash(array $dash): void
 * The dash pattern: alternating "on" and "off" lengths (a list of floats, none negative and
 * not all zero); an empty list draws a solid line.
 */
ZEND_METHOD(Gtk4_GskStroke, set_dash) {
  zval *dash;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_ARRAY(dash)
  ZEND_PARSE_PARAMETERS_END();
  GskStroke *self = PHPGTK_BOXED_SELF(GskStroke);
  std::vector<float> lengths;
  bool any = false;
  zval *item;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
  ZEND_HASH_FOREACH_VAL(Z_ARRVAL_P(dash), item) {
    if (Z_TYPE_P(item) != IS_DOUBLE && Z_TYPE_P(item) != IS_LONG) {
      zend_argument_type_error(1, "must be a list of float, %s found in it", zend_zval_value_name(item));
      RETURN_THROWS();
    }
    const double v = zval_get_double(item);
    if (v < 0) {
      zend_argument_value_error(1, "must not contain a negative length");
      RETURN_THROWS();
    }
    any = any || v > 0;
    lengths.push_back(static_cast<float>(v));
  }
  ZEND_HASH_FOREACH_END();
  if (!lengths.empty() && !any) {
    zend_argument_value_error(1, "must contain a length above zero (GSK refuses an all-zero dash)");
    RETURN_THROWS();
  }
  gsk_stroke_set_dash(self, lengths.empty() ? nullptr : lengths.data(), lengths.size());
}
