/**
 * public function get_shadow(int $i): array
 * Shadow number $i (0 to get_n_shadows() - 1) as the `[GdkRGBA $color, float $dx, float $dy,
 * float $radius]` list the constructor took.
 *
 * @return array{GdkRGBA, float, float, float}
 */
ZEND_METHOD(Gtk4_GskShadowNode, get_shadow) {
  zend_long i = 0;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_LONG(i)
  ZEND_PARSE_PARAMETERS_END();
  auto *self = static_cast<GskRenderNode *>(fundamental_self(execute_data));
  if (self == nullptr) RETURN_THROWS();
  const gsize n = gsk_shadow_node_get_n_shadows(self);
  if (i < 0 || static_cast<gsize>(i) >= n) {
    zend_argument_value_error(1, "must be between 0 and %zu (the shadow count minus one), " ZEND_LONG_FMT " given",
                              n - 1, i);
    RETURN_THROWS();
  }
  shadow_to_php(gsk_shadow_node_get_shadow(self, static_cast<gsize>(i)), return_value);
}
