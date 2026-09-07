/**
 * public function get_widths(): array
 * The four border widths - top, right, bottom, left - the constructor took.
 *
 * @return array{float, float, float, float}
 */
ZEND_METHOD(Gtk4_GskBorderNode, get_widths) {
  ZEND_PARSE_PARAMETERS_NONE();
  auto *self = static_cast<GskRenderNode *>(fundamental_self(execute_data));
  if (self == nullptr) RETURN_THROWS();
  const float *w = gsk_border_node_get_widths(self);
  array_init_size(return_value, 4);
  for (int i = 0; i < 4; i++) {
    // NOLINTNEXTLINE(cppcoreguidelines-pro-bounds-pointer-arithmetic) GSK's array of four floats
    add_next_index_double(return_value, w[i]);
  }
}
