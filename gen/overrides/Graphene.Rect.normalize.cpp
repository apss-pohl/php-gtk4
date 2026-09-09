/**
 * public function normalize(): GrapheneRect
 * The same rectangle with a non-negative width and height.
 *
 * graphene's plain form writes into the rectangle it is given and hands the same one back;
 * a boxed handle here is a *value* (clone and compare by value), so a method that silently
 * rewrote the receiver would break that. This is the `normalize_r()` form, which leaves the
 * receiver alone - and is why `normalize_r()` itself is not bound separately.
 */
ZEND_METHOD(Gtk4_GrapheneRect, normalize) {
  ZEND_PARSE_PARAMETERS_NONE();
  auto *self = static_cast<graphene_rect_t *>(boxed_from_zval(ZEND_THIS)->data);
  graphene_rect_t out;
  graphene_rect_normalize_r(self, &out);
  wrap_boxed(GRAPHENE_TYPE_RECT, &out, return_value);
}
