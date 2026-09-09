/**
 * public function __construct(GrapheneRect $bounds, GraphenePoint $center, float $hradius, float $vradius, float $start, float $end, array $color_stops)
 * A radial gradient around $center over $bounds: an ellipse of $hradius by $vradius, painted
 * from $start to $end (fractions of the radii, 0.0 to 1.0), through $color_stops - at least two
 * `[float $offset, GdkRGBA $color]` pairs with offsets ascending from 0.0 to 1.0.
 */
ZEND_METHOD(Gtk4_GskRadialGradientNode, __construct) {
  zval *bounds;
  zval *center;
  double hradius = 0;
  double vradius = 0;
  double start = 0;
  double end = 0;
  zval *stops;
  ZEND_PARSE_PARAMETERS_START(7, 7)
  Z_PARAM_OBJECT_OF_CLASS(bounds, boxed_class_for_type(GRAPHENE_TYPE_RECT)->ce)
  Z_PARAM_OBJECT_OF_CLASS(center, boxed_class_for_type(GRAPHENE_TYPE_POINT)->ce)
  Z_PARAM_DOUBLE(hradius)
  Z_PARAM_DOUBLE(vradius)
  Z_PARAM_DOUBLE(start)
  Z_PARAM_DOUBLE(end)
  Z_PARAM_ARRAY(stops)
  ZEND_PARSE_PARAMETERS_END();
  // gsk_radial_gradient_node_new()'s own preconditions, as ValueErrors
  if (!phpgtk::check_domain_above(hradius, 0.0, 3)) RETURN_THROWS();
  if (!phpgtk::check_domain_above(vradius, 0.0, 4)) RETURN_THROWS();
  if (!phpgtk::check_domain_double(start, 0.0, HUGE_VAL, 5)) RETURN_THROWS();
  if (!phpgtk::check_domain_double(end, 0.0, HUGE_VAL, 6)) RETURN_THROWS();
  std::vector<GskColorStop> cs;
  if (!color_stops_from_php(stops, 7, cs)) RETURN_THROWS();
  GskRenderNode *self = gsk_radial_gradient_node_new(
      static_cast<graphene_rect_t *>(unwrap_boxed(bounds, GRAPHENE_TYPE_RECT)),
      static_cast<graphene_point_t *>(unwrap_boxed(center, GRAPHENE_TYPE_POINT)),
      static_cast<float>(hradius), static_cast<float>(vradius), static_cast<float>(start),
      static_cast<float>(end), cs.data(), cs.size());
  fundamental_adopt(fundamental_from_zval(ZEND_THIS), GSK_TYPE_RADIAL_GRADIENT_NODE, self);
}
