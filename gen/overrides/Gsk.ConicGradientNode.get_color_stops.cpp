/**
 * public function get_color_stops(): array
 * The colour stops, as the `[float $offset, GdkRGBA $color]` pairs the constructor took.
 *
 * @return list<array{float, GdkRGBA}>
 */
ZEND_METHOD(Gtk4_GskConicGradientNode, get_color_stops) {
  ZEND_PARSE_PARAMETERS_NONE();
  auto *self = static_cast<GskRenderNode *>(fundamental_self(execute_data));
  if (self == nullptr) RETURN_THROWS();
  gsize n = 0;
  const GskColorStop *cs = gsk_conic_gradient_node_get_color_stops(self, &n);
  color_stops_to_php(cs, n, return_value);
}
