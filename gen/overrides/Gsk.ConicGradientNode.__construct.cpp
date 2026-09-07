/**
 * public function __construct(GrapheneRect $bounds, GraphenePoint $center, float $rotation, array $color_stops)
 * A conic (angular) gradient around $center over $bounds, starting $rotation degrees from the
 * top and going clockwise, through $color_stops - at least two `[float $offset, GdkRGBA $color]`
 * pairs with offsets ascending from 0.0 to 1.0.
 */
ZEND_METHOD(Gtk4_GskConicGradientNode, __construct) {
  zval *bounds;
  zval *center;
  double rotation = 0;
  zval *stops;
  ZEND_PARSE_PARAMETERS_START(4, 4)
  Z_PARAM_OBJECT_OF_CLASS(bounds, boxed_class_for_type(GRAPHENE_TYPE_RECT)->ce)
  Z_PARAM_OBJECT_OF_CLASS(center, boxed_class_for_type(GRAPHENE_TYPE_POINT)->ce)
  Z_PARAM_DOUBLE(rotation)
  Z_PARAM_ARRAY(stops)
  ZEND_PARSE_PARAMETERS_END();
  std::vector<GskColorStop> cs;
  if (!color_stops_from_php(stops, 4, cs)) RETURN_THROWS();
  GskRenderNode *self = gsk_conic_gradient_node_new(
      static_cast<graphene_rect_t *>(unwrap_boxed(bounds, GRAPHENE_TYPE_RECT)),
      static_cast<graphene_point_t *>(unwrap_boxed(center, GRAPHENE_TYPE_POINT)),
      static_cast<float>(rotation), cs.data(), cs.size());
  fundamental_adopt(fundamental_from_zval(ZEND_THIS), GSK_TYPE_CONIC_GRADIENT_NODE, self);
}
