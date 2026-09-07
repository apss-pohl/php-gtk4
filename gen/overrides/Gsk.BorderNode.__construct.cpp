/**
 * public function __construct(GskRoundedRect $outline, array $widths, array $colors)
 * A border drawn inside $outline: $widths are the four widths (top, right, bottom, left) as
 * floats, $colors the four GdkRGBA colours in the same order.
 *
 * GIR takes two fixed-size C arrays; PHP lists of exactly four are the same thing.
 */
ZEND_METHOD(Gtk4_GskBorderNode, __construct) {
  zval *outline;
  zval *widths;
  zval *colors;
  ZEND_PARSE_PARAMETERS_START(3, 3)
  Z_PARAM_OBJECT_OF_CLASS(outline, boxed_class_for_type(PHPGTK_TYPE_GSK_ROUNDED_RECT)->ce)
  Z_PARAM_ARRAY(widths)
  Z_PARAM_ARRAY(colors)
  ZEND_PARSE_PARAMETERS_END();
  std::array<float, 4> border_width{};
  std::array<GdkRGBA, 4> border_color{};
  if (!border_from_php(widths, 2, colors, 3, border_width, border_color)) RETURN_THROWS();
  GskRenderNode *self = gsk_border_node_new(
      static_cast<const GskRoundedRect *>(unwrap_boxed(outline, PHPGTK_TYPE_GSK_ROUNDED_RECT)),
      border_width.data(), border_color.data());
  fundamental_adopt(fundamental_from_zval(ZEND_THIS), GSK_TYPE_BORDER_NODE, self);
}
