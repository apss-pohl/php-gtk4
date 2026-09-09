/**
 * public function offset(float $d_x, float $d_y): GrapheneRect
 * Move the rectangle by $d_x and $d_y.
 *
 * graphene's plain form writes into the rectangle it is given and hands the same one back;
 * a boxed handle here is a *value* (clone and compare by value), so a method that silently
 * rewrote the receiver would break that. This is the `offset_r()` form, which leaves the
 * receiver alone - and is why `offset_r()` itself is not bound separately.
 */
ZEND_METHOD(Gtk4_GrapheneRect, offset) {
  double d_x;
  double d_y;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_DOUBLE(d_x)
  Z_PARAM_DOUBLE(d_y)
  ZEND_PARSE_PARAMETERS_END();
  auto *self = static_cast<graphene_rect_t *>(boxed_from_zval(ZEND_THIS)->data);
  graphene_rect_t out;
  graphene_rect_offset_r(self, static_cast<float>(d_x), static_cast<float>(d_y), &out);
  wrap_boxed(GRAPHENE_TYPE_RECT, &out, return_value);
}
