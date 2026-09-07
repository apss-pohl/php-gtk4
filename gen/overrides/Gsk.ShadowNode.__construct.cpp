/**
 * public function __construct(GskRenderNode $child, array $shadows)
 * $child drawn with drop shadows behind it: at least one `[GdkRGBA $color, float $dx, float $dy,
 * float $radius]` list, painted in order.
 */
ZEND_METHOD(Gtk4_GskShadowNode, __construct) {
  zval *child;
  zval *shadows;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_OBJECT_OF_CLASS(child, fundamental_class_for_type(GSK_TYPE_RENDER_NODE)->ce)
  Z_PARAM_ARRAY(shadows)
  ZEND_PARSE_PARAMETERS_END();
  gpointer child_f = unwrap_fundamental(child, GSK_TYPE_RENDER_NODE);
  if (child_f == nullptr) RETURN_THROWS();
  std::vector<GskShadow> list;
  if (!shadows_from_php(shadows, 2, list)) RETURN_THROWS();
  GskRenderNode *self =
      gsk_shadow_node_new(static_cast<GskRenderNode *>(child_f), list.data(), list.size());
  fundamental_adopt(fundamental_from_zval(ZEND_THIS), GSK_TYPE_SHADOW_NODE, self);
}
