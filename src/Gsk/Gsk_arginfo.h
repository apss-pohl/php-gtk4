/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: babda0d7f13c1892703d80fcd3a7f29f5f567d5c */

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskBlendNode___construct, 0, 0, 3)
	ZEND_ARG_OBJ_INFO(0, bottom, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_OBJ_INFO(0, top, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_OBJ_INFO(0, blend_mode, Gtk4\\GskBlendMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskBlendNode_get_blend_mode, 0, 0, Gtk4\\GskBlendMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskBlendNode_get_bottom_child, 0, 0, Gtk4\\GskRenderNode, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskBlendNode_get_top_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskBlurNode___construct, 0, 0, 2)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_TYPE_INFO(0, radius, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskBlurNode_get_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskBlurNode_get_radius, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskBorderNode_get_colors, 0, 0, Gtk4\\GdkRGBA, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskBorderNode_get_outline, 0, 0, Gtk4\\GskRoundedRect, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskBorderNode___construct, 0, 0, 3)
	ZEND_ARG_OBJ_INFO(0, outline, Gtk4\\GskRoundedRect, 0)
	ZEND_ARG_TYPE_INFO(0, widths, IS_ARRAY, 0)
	ZEND_ARG_TYPE_INFO(0, colors, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskBorderNode_get_widths, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskCairoNode___construct, 0, 0, 1)
	ZEND_ARG_OBJ_INFO(0, bounds, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskCairoNode_get_draw_context, 0, 0, Gtk4\\CairoContext, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskCairoNode_get_surface, 0, 0, Gtk4\\CairoSurface, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskCairoRenderer___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskClipNode___construct, 0, 0, 2)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_OBJ_INFO(0, clip, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskClipNode_get_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskClipNode_get_clip, 0, 0, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskColorMatrixNode___construct arginfo_class_Gtk4_GskCairoRenderer___construct

#define arginfo_class_Gtk4_GskColorMatrixNode_get_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskColorNode___construct, 0, 0, 2)
	ZEND_ARG_OBJ_INFO(0, rgba, Gtk4\\GdkRGBA, 0)
	ZEND_ARG_OBJ_INFO(0, bounds, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskColorNode_get_color arginfo_class_Gtk4_GskBorderNode_get_colors

#define arginfo_class_Gtk4_GskConicGradientNode_get_angle arginfo_class_Gtk4_GskBlurNode_get_radius

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskConicGradientNode_get_center, 0, 0, Gtk4\\GraphenePoint, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskConicGradientNode_get_n_color_stops, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskConicGradientNode_get_rotation arginfo_class_Gtk4_GskBlurNode_get_radius

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskConicGradientNode___construct, 0, 0, 4)
	ZEND_ARG_OBJ_INFO(0, bounds, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_OBJ_INFO(0, center, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_TYPE_INFO(0, rotation, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, color_stops, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskConicGradientNode_get_color_stops arginfo_class_Gtk4_GskBorderNode_get_widths

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskContainerNode_get_child, 0, 1, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_TYPE_INFO(0, idx, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskContainerNode_get_n_children arginfo_class_Gtk4_GskConicGradientNode_get_n_color_stops

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskContainerNode___construct, 0, 0, 1)
	ZEND_ARG_TYPE_INFO(0, children, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskCrossFadeNode___construct, 0, 0, 3)
	ZEND_ARG_OBJ_INFO(0, start, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_OBJ_INFO(0, end, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_TYPE_INFO(0, progress, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskCrossFadeNode_get_end_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

#define arginfo_class_Gtk4_GskCrossFadeNode_get_progress arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskCrossFadeNode_get_start_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskDebugNode___construct, 0, 0, 2)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_TYPE_INFO(0, message, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskDebugNode_get_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskDebugNode_get_message, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskFillNode___construct, 0, 0, 3)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_OBJ_INFO(0, path, Gtk4\\GskPath, 0)
	ZEND_ARG_OBJ_INFO(0, fill_rule, Gtk4\\GskFillRule, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskFillNode_get_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskFillNode_get_fill_rule, 0, 0, Gtk4\\GskFillRule, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskFillNode_get_path, 0, 0, Gtk4\\GskPath, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskGLRenderer___construct arginfo_class_Gtk4_GskCairoRenderer___construct

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskInsetShadowNode___construct, 0, 0, 6)
	ZEND_ARG_OBJ_INFO(0, outline, Gtk4\\GskRoundedRect, 0)
	ZEND_ARG_OBJ_INFO(0, color, Gtk4\\GdkRGBA, 0)
	ZEND_ARG_TYPE_INFO(0, dx, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, dy, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, spread, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, blur_radius, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskInsetShadowNode_get_blur_radius arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskInsetShadowNode_get_color arginfo_class_Gtk4_GskBorderNode_get_colors

#define arginfo_class_Gtk4_GskInsetShadowNode_get_dx arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskInsetShadowNode_get_dy arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskInsetShadowNode_get_outline arginfo_class_Gtk4_GskBorderNode_get_outline

#define arginfo_class_Gtk4_GskInsetShadowNode_get_spread arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskLinearGradientNode_get_end arginfo_class_Gtk4_GskConicGradientNode_get_center

#define arginfo_class_Gtk4_GskLinearGradientNode_get_n_color_stops arginfo_class_Gtk4_GskConicGradientNode_get_n_color_stops

#define arginfo_class_Gtk4_GskLinearGradientNode_get_start arginfo_class_Gtk4_GskConicGradientNode_get_center

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskLinearGradientNode___construct, 0, 0, 4)
	ZEND_ARG_OBJ_INFO(0, bounds, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_OBJ_INFO(0, start, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_OBJ_INFO(0, end, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_TYPE_INFO(0, color_stops, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskLinearGradientNode_get_color_stops arginfo_class_Gtk4_GskBorderNode_get_widths

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskMaskNode___construct, 0, 0, 3)
	ZEND_ARG_OBJ_INFO(0, source, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_OBJ_INFO(0, mask, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_OBJ_INFO(0, mask_mode, Gtk4\\GskMaskMode, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskMaskNode_get_mask arginfo_class_Gtk4_GskBlendNode_get_bottom_child

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskMaskNode_get_mask_mode, 0, 0, Gtk4\\GskMaskMode, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskMaskNode_get_source arginfo_class_Gtk4_GskBlendNode_get_bottom_child

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskOpacityNode___construct, 0, 0, 2)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_TYPE_INFO(0, opacity, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskOpacityNode_get_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

#define arginfo_class_Gtk4_GskOpacityNode_get_opacity arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskOutsetShadowNode___construct arginfo_class_Gtk4_GskInsetShadowNode___construct

#define arginfo_class_Gtk4_GskOutsetShadowNode_get_blur_radius arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskOutsetShadowNode_get_color arginfo_class_Gtk4_GskBorderNode_get_colors

#define arginfo_class_Gtk4_GskOutsetShadowNode_get_dx arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskOutsetShadowNode_get_dy arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskOutsetShadowNode_get_outline arginfo_class_Gtk4_GskBorderNode_get_outline

#define arginfo_class_Gtk4_GskOutsetShadowNode_get_spread arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskPath___construct arginfo_class_Gtk4_GskCairoRenderer___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskPath_get_bounds, 0, 0, Gtk4\\GrapheneRect, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPath_get_closest_point, 0, 2, IS_ARRAY, 1)
	ZEND_ARG_OBJ_INFO(0, point, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_TYPE_INFO(0, threshold, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskPath_get_end_point, 0, 0, Gtk4\\GskPathPoint, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskPath_get_start_point arginfo_class_Gtk4_GskPath_get_end_point

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskPath_get_stroke_bounds, 0, 1, Gtk4\\GrapheneRect, 1)
	ZEND_ARG_OBJ_INFO(0, stroke, Gtk4\\GskStroke, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPath_in_fill, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, point, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_OBJ_INFO(0, fill_rule, Gtk4\\GskFillRule, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPath_is_closed, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskPath_is_empty arginfo_class_Gtk4_GskPath_is_closed

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPath_to_cairo, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, cr, Gtk4\\CairoContext, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskPath_to_string arginfo_class_Gtk4_GskDebugNode_get_message

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskPath_parse, 0, 1, Gtk4\\GskPath, 1)
	ZEND_ARG_TYPE_INFO(0, string, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskPathBuilder___construct arginfo_class_Gtk4_GskCairoRenderer___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathBuilder_add_circle, 0, 2, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, center, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_TYPE_INFO(0, radius, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathBuilder_add_path, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, path, Gtk4\\GskPath, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathBuilder_add_rect, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, rect, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskPathBuilder_add_reverse_path arginfo_class_Gtk4_GskPathBuilder_add_path

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathBuilder_add_rounded_rect, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, rect, Gtk4\\GskRoundedRect, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathBuilder_add_segment, 0, 3, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, path, Gtk4\\GskPath, 0)
	ZEND_ARG_OBJ_INFO(0, start, Gtk4\\GskPathPoint, 0)
	ZEND_ARG_OBJ_INFO(0, end, Gtk4\\GskPathPoint, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathBuilder_arc_to, 0, 4, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, x1, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y1, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, x2, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y2, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathBuilder_close, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathBuilder_conic_to, 0, 5, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, x1, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y1, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, x2, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y2, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, weight, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathBuilder_cubic_to, 0, 6, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, x1, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y1, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, x2, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y2, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, x3, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y3, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskPathBuilder_get_current_point arginfo_class_Gtk4_GskConicGradientNode_get_center

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathBuilder_html_arc_to, 0, 5, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, x1, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y1, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, x2, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y2, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, radius, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathBuilder_line_to, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskPathBuilder_move_to arginfo_class_Gtk4_GskPathBuilder_line_to

#define arginfo_class_Gtk4_GskPathBuilder_quad_to arginfo_class_Gtk4_GskPathBuilder_arc_to

#define arginfo_class_Gtk4_GskPathBuilder_rel_arc_to arginfo_class_Gtk4_GskPathBuilder_arc_to

#define arginfo_class_Gtk4_GskPathBuilder_rel_conic_to arginfo_class_Gtk4_GskPathBuilder_conic_to

#define arginfo_class_Gtk4_GskPathBuilder_rel_cubic_to arginfo_class_Gtk4_GskPathBuilder_cubic_to

#define arginfo_class_Gtk4_GskPathBuilder_rel_html_arc_to arginfo_class_Gtk4_GskPathBuilder_html_arc_to

#define arginfo_class_Gtk4_GskPathBuilder_rel_line_to arginfo_class_Gtk4_GskPathBuilder_line_to

#define arginfo_class_Gtk4_GskPathBuilder_rel_move_to arginfo_class_Gtk4_GskPathBuilder_line_to

#define arginfo_class_Gtk4_GskPathBuilder_rel_quad_to arginfo_class_Gtk4_GskPathBuilder_arc_to

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathBuilder_rel_svg_arc_to, 0, 7, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, rx, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, ry, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, x_axis_rotation, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, large_arc, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, positive_sweep, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskPathBuilder_svg_arc_to arginfo_class_Gtk4_GskPathBuilder_rel_svg_arc_to

#define arginfo_class_Gtk4_GskPathBuilder_to_path arginfo_class_Gtk4_GskFillNode_get_path

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskPathMeasure___construct, 0, 0, 1)
	ZEND_ARG_OBJ_INFO(0, path, Gtk4\\GskPath, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskPathMeasure_new_with_tolerance, 0, 2, Gtk4\\GskPathMeasure, 0)
	ZEND_ARG_OBJ_INFO(0, path, Gtk4\\GskPath, 0)
	ZEND_ARG_TYPE_INFO(0, tolerance, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskPathMeasure_get_length arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskPathMeasure_get_path arginfo_class_Gtk4_GskFillNode_get_path

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskPathMeasure_get_point, 0, 1, Gtk4\\GskPathPoint, 1)
	ZEND_ARG_TYPE_INFO(0, distance, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskPathMeasure_get_tolerance arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskPathPoint___construct arginfo_class_Gtk4_GskCairoRenderer___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathPoint_compare, 0, 1, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, point2, Gtk4\\GskPathPoint, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathPoint_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, point2, Gtk4\\GskPathPoint, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathPoint_get_distance, 0, 1, IS_DOUBLE, 0)
	ZEND_ARG_OBJ_INFO(0, measure, Gtk4\\GskPathMeasure, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskPathPoint_get_position, 0, 1, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_OBJ_INFO(0, path, Gtk4\\GskPath, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskPathPoint_get_rotation, 0, 2, IS_DOUBLE, 0)
	ZEND_ARG_OBJ_INFO(0, path, Gtk4\\GskPath, 0)
	ZEND_ARG_OBJ_INFO(0, direction, Gtk4\\GskPathDirection, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskRadialGradientNode_get_center arginfo_class_Gtk4_GskConicGradientNode_get_center

#define arginfo_class_Gtk4_GskRadialGradientNode_get_end arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskRadialGradientNode_get_hradius arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskRadialGradientNode_get_n_color_stops arginfo_class_Gtk4_GskConicGradientNode_get_n_color_stops

#define arginfo_class_Gtk4_GskRadialGradientNode_get_start arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskRadialGradientNode_get_vradius arginfo_class_Gtk4_GskBlurNode_get_radius

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskRadialGradientNode___construct, 0, 0, 7)
	ZEND_ARG_OBJ_INFO(0, bounds, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_OBJ_INFO(0, center, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_TYPE_INFO(0, hradius, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, vradius, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, start, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, end, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, color_stops, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskRadialGradientNode_get_color_stops arginfo_class_Gtk4_GskBorderNode_get_widths

#define arginfo_class_Gtk4_GskRenderNode___construct arginfo_class_Gtk4_GskCairoRenderer___construct

#define arginfo_class_Gtk4_GskRenderNode_draw arginfo_class_Gtk4_GskPath_to_cairo

#define arginfo_class_Gtk4_GskRenderNode_get_bounds arginfo_class_Gtk4_GskClipNode_get_clip

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskRenderNode_get_node_type, 0, 0, Gtk4\\GskRenderNodeType, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskRenderNode_serialize arginfo_class_Gtk4_GskDebugNode_get_message

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskRenderNode_write_to_file, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, filename, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskRenderNode_deserialize, 0, 1, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_TYPE_INFO(0, bytes, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskRenderer___construct arginfo_class_Gtk4_GskCairoRenderer___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskRenderer_new_for_surface, 0, 1, Gtk4\\GskRenderer, 0)
	ZEND_ARG_OBJ_INFO(0, surface, Gtk4\\GdkSurface, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskRenderer_get_surface, 0, 0, Gtk4\\GdkSurface, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskRenderer_is_realized arginfo_class_Gtk4_GskPath_is_closed

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskRenderer_realize, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, surface, Gtk4\\GdkSurface, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskRenderer_realize_for_display, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, display, Gtk4\\GdkDisplay, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskRenderer_render_texture, 0, 2, Gtk4\\GdkTexture, 0)
	ZEND_ARG_OBJ_INFO(0, root, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_OBJ_INFO(0, viewport, Gtk4\\GrapheneRect, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskRenderer_unrealize arginfo_class_Gtk4_GskPathBuilder_close

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskRepeatNode___construct, 0, 0, 2)
	ZEND_ARG_OBJ_INFO(0, bounds, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, child_bounds, Gtk4\\GrapheneRect, 1, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskRepeatNode_get_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

#define arginfo_class_Gtk4_GskRepeatNode_get_child_bounds arginfo_class_Gtk4_GskClipNode_get_clip

#define arginfo_class_Gtk4_GskRepeatingLinearGradientNode___construct arginfo_class_Gtk4_GskLinearGradientNode___construct

#define arginfo_class_Gtk4_GskRepeatingRadialGradientNode___construct arginfo_class_Gtk4_GskRadialGradientNode___construct

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskRoundedClipNode___construct, 0, 0, 2)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_OBJ_INFO(0, clip, Gtk4\\GskRoundedRect, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskRoundedClipNode_get_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

#define arginfo_class_Gtk4_GskRoundedClipNode_get_clip arginfo_class_Gtk4_GskBorderNode_get_outline

#define arginfo_class_Gtk4_GskShadowNode_get_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

#define arginfo_class_Gtk4_GskShadowNode_get_n_shadows arginfo_class_Gtk4_GskConicGradientNode_get_n_color_stops

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskShadowNode___construct, 0, 0, 2)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_TYPE_INFO(0, shadows, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskShadowNode_get_shadow, 0, 1, IS_ARRAY, 0)
	ZEND_ARG_TYPE_INFO(0, i, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskStroke___construct, 0, 0, 1)
	ZEND_ARG_TYPE_INFO(0, line_width, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskStroke_get_dash_offset arginfo_class_Gtk4_GskBlurNode_get_radius

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskStroke_get_line_cap, 0, 0, Gtk4\\GskLineCap, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskStroke_get_line_join, 0, 0, Gtk4\\GskLineJoin, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskStroke_get_line_width arginfo_class_Gtk4_GskBlurNode_get_radius

#define arginfo_class_Gtk4_GskStroke_get_miter_limit arginfo_class_Gtk4_GskBlurNode_get_radius

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskStroke_set_dash_offset, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, offset, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskStroke_set_line_cap, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, line_cap, Gtk4\\GskLineCap, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskStroke_set_line_join, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, line_join, Gtk4\\GskLineJoin, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskStroke_set_line_width, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, line_width, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskStroke_set_miter_limit, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, limit, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskStroke_to_cairo arginfo_class_Gtk4_GskPath_to_cairo

#define arginfo_class_Gtk4_GskStroke_get_dash arginfo_class_Gtk4_GskBorderNode_get_widths

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskStroke_set_dash, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, dash, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskStrokeNode___construct, 0, 0, 3)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_OBJ_INFO(0, path, Gtk4\\GskPath, 0)
	ZEND_ARG_OBJ_INFO(0, stroke, Gtk4\\GskStroke, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskStrokeNode_get_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

#define arginfo_class_Gtk4_GskStrokeNode_get_path arginfo_class_Gtk4_GskFillNode_get_path

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskStrokeNode_get_stroke, 0, 0, Gtk4\\GskStroke, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskSubsurfaceNode___construct arginfo_class_Gtk4_GskCairoRenderer___construct

#define arginfo_class_Gtk4_GskSubsurfaceNode_get_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

#define arginfo_class_Gtk4_GskTextNode___construct arginfo_class_Gtk4_GskCairoRenderer___construct

#define arginfo_class_Gtk4_GskTextNode_get_color arginfo_class_Gtk4_GskBorderNode_get_colors

#define arginfo_class_Gtk4_GskTextNode_get_num_glyphs arginfo_class_Gtk4_GskConicGradientNode_get_n_color_stops

#define arginfo_class_Gtk4_GskTextNode_get_offset arginfo_class_Gtk4_GskConicGradientNode_get_center

#define arginfo_class_Gtk4_GskTextNode_has_color_glyphs arginfo_class_Gtk4_GskPath_is_closed

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskTextureNode___construct, 0, 0, 2)
	ZEND_ARG_OBJ_INFO(0, texture, Gtk4\\GdkTexture, 0)
	ZEND_ARG_OBJ_INFO(0, bounds, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTextureNode_get_texture, 0, 0, Gtk4\\GdkTexture, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskTextureScaleNode___construct, 0, 0, 3)
	ZEND_ARG_OBJ_INFO(0, texture, Gtk4\\GdkTexture, 0)
	ZEND_ARG_OBJ_INFO(0, bounds, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_OBJ_INFO(0, filter, Gtk4\\GskScalingFilter, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTextureScaleNode_get_filter, 0, 0, Gtk4\\GskScalingFilter, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskTextureScaleNode_get_texture arginfo_class_Gtk4_GskTextureNode_get_texture

#define arginfo_class_Gtk4_GskTransform___construct arginfo_class_Gtk4_GskCairoRenderer___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskTransform_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, second, Gtk4\\GskTransform, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTransform_get_category, 0, 0, Gtk4\\GskTransformCategory, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTransform_invert, 0, 0, Gtk4\\GskTransform, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTransform_perspective, 0, 1, Gtk4\\GskTransform, 0)
	ZEND_ARG_TYPE_INFO(0, depth, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTransform_rotate, 0, 1, Gtk4\\GskTransform, 1)
	ZEND_ARG_TYPE_INFO(0, angle, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTransform_scale, 0, 2, Gtk4\\GskTransform, 1)
	ZEND_ARG_TYPE_INFO(0, factor_x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, factor_y, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTransform_scale_3d, 0, 3, Gtk4\\GskTransform, 1)
	ZEND_ARG_TYPE_INFO(0, factor_x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, factor_y, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, factor_z, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTransform_skew, 0, 2, Gtk4\\GskTransform, 1)
	ZEND_ARG_TYPE_INFO(0, skew_x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, skew_y, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskTransform_to_2d arginfo_class_Gtk4_GskBorderNode_get_widths

#define arginfo_class_Gtk4_GskTransform_to_2d_components arginfo_class_Gtk4_GskBorderNode_get_widths

#define arginfo_class_Gtk4_GskTransform_to_affine arginfo_class_Gtk4_GskBorderNode_get_widths

#define arginfo_class_Gtk4_GskTransform_to_string arginfo_class_Gtk4_GskDebugNode_get_message

#define arginfo_class_Gtk4_GskTransform_to_translate arginfo_class_Gtk4_GskBorderNode_get_widths

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTransform_transform, 0, 1, Gtk4\\GskTransform, 1)
	ZEND_ARG_OBJ_INFO(0, other, Gtk4\\GskTransform, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTransform_transform_bounds, 0, 1, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_OBJ_INFO(0, rect, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTransform_transform_point, 0, 1, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_OBJ_INFO(0, point, Gtk4\\GraphenePoint, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTransform_translate, 0, 1, Gtk4\\GskTransform, 1)
	ZEND_ARG_OBJ_INFO(0, point, Gtk4\\GraphenePoint, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTransform_parse, 0, 1, Gtk4\\GskTransform, 1)
	ZEND_ARG_TYPE_INFO(0, string, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskTransformNode___construct, 0, 0, 2)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GskRenderNode, 0)
	ZEND_ARG_OBJ_INFO(0, transform, Gtk4\\GskTransform, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskTransformNode_get_child arginfo_class_Gtk4_GskBlendNode_get_bottom_child

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskTransformNode_get_transform, 0, 0, Gtk4\\GskTransform, 0)
ZEND_END_ARG_INFO()

ZEND_METHOD(Gtk4_GskBlendNode, __construct);
ZEND_METHOD(Gtk4_GskBlendNode, get_blend_mode);
ZEND_METHOD(Gtk4_GskBlendNode, get_bottom_child);
ZEND_METHOD(Gtk4_GskBlendNode, get_top_child);
ZEND_METHOD(Gtk4_GskBlurNode, __construct);
ZEND_METHOD(Gtk4_GskBlurNode, get_child);
ZEND_METHOD(Gtk4_GskBlurNode, get_radius);
ZEND_METHOD(Gtk4_GskBorderNode, get_colors);
ZEND_METHOD(Gtk4_GskBorderNode, get_outline);
ZEND_METHOD(Gtk4_GskBorderNode, __construct);
ZEND_METHOD(Gtk4_GskBorderNode, get_widths);
ZEND_METHOD(Gtk4_GskCairoNode, __construct);
ZEND_METHOD(Gtk4_GskCairoNode, get_draw_context);
ZEND_METHOD(Gtk4_GskCairoNode, get_surface);
ZEND_METHOD(Gtk4_GskCairoRenderer, __construct);
ZEND_METHOD(Gtk4_GskClipNode, __construct);
ZEND_METHOD(Gtk4_GskClipNode, get_child);
ZEND_METHOD(Gtk4_GskClipNode, get_clip);
ZEND_METHOD(Gtk4_GskColorMatrixNode, __construct);
ZEND_METHOD(Gtk4_GskColorMatrixNode, get_child);
ZEND_METHOD(Gtk4_GskColorNode, __construct);
ZEND_METHOD(Gtk4_GskColorNode, get_color);
ZEND_METHOD(Gtk4_GskConicGradientNode, get_angle);
ZEND_METHOD(Gtk4_GskConicGradientNode, get_center);
ZEND_METHOD(Gtk4_GskConicGradientNode, get_n_color_stops);
ZEND_METHOD(Gtk4_GskConicGradientNode, get_rotation);
ZEND_METHOD(Gtk4_GskConicGradientNode, __construct);
ZEND_METHOD(Gtk4_GskConicGradientNode, get_color_stops);
ZEND_METHOD(Gtk4_GskContainerNode, get_child);
ZEND_METHOD(Gtk4_GskContainerNode, get_n_children);
ZEND_METHOD(Gtk4_GskContainerNode, __construct);
ZEND_METHOD(Gtk4_GskCrossFadeNode, __construct);
ZEND_METHOD(Gtk4_GskCrossFadeNode, get_end_child);
ZEND_METHOD(Gtk4_GskCrossFadeNode, get_progress);
ZEND_METHOD(Gtk4_GskCrossFadeNode, get_start_child);
ZEND_METHOD(Gtk4_GskDebugNode, __construct);
ZEND_METHOD(Gtk4_GskDebugNode, get_child);
ZEND_METHOD(Gtk4_GskDebugNode, get_message);
ZEND_METHOD(Gtk4_GskFillNode, __construct);
ZEND_METHOD(Gtk4_GskFillNode, get_child);
ZEND_METHOD(Gtk4_GskFillNode, get_fill_rule);
ZEND_METHOD(Gtk4_GskFillNode, get_path);
ZEND_METHOD(Gtk4_GskGLRenderer, __construct);
ZEND_METHOD(Gtk4_GskInsetShadowNode, __construct);
ZEND_METHOD(Gtk4_GskInsetShadowNode, get_blur_radius);
ZEND_METHOD(Gtk4_GskInsetShadowNode, get_color);
ZEND_METHOD(Gtk4_GskInsetShadowNode, get_dx);
ZEND_METHOD(Gtk4_GskInsetShadowNode, get_dy);
ZEND_METHOD(Gtk4_GskInsetShadowNode, get_outline);
ZEND_METHOD(Gtk4_GskInsetShadowNode, get_spread);
ZEND_METHOD(Gtk4_GskLinearGradientNode, get_end);
ZEND_METHOD(Gtk4_GskLinearGradientNode, get_n_color_stops);
ZEND_METHOD(Gtk4_GskLinearGradientNode, get_start);
ZEND_METHOD(Gtk4_GskLinearGradientNode, __construct);
ZEND_METHOD(Gtk4_GskLinearGradientNode, get_color_stops);
ZEND_METHOD(Gtk4_GskMaskNode, __construct);
ZEND_METHOD(Gtk4_GskMaskNode, get_mask);
ZEND_METHOD(Gtk4_GskMaskNode, get_mask_mode);
ZEND_METHOD(Gtk4_GskMaskNode, get_source);
ZEND_METHOD(Gtk4_GskOpacityNode, __construct);
ZEND_METHOD(Gtk4_GskOpacityNode, get_child);
ZEND_METHOD(Gtk4_GskOpacityNode, get_opacity);
ZEND_METHOD(Gtk4_GskOutsetShadowNode, __construct);
ZEND_METHOD(Gtk4_GskOutsetShadowNode, get_blur_radius);
ZEND_METHOD(Gtk4_GskOutsetShadowNode, get_color);
ZEND_METHOD(Gtk4_GskOutsetShadowNode, get_dx);
ZEND_METHOD(Gtk4_GskOutsetShadowNode, get_dy);
ZEND_METHOD(Gtk4_GskOutsetShadowNode, get_outline);
ZEND_METHOD(Gtk4_GskOutsetShadowNode, get_spread);
ZEND_METHOD(Gtk4_GskPath, __construct);
ZEND_METHOD(Gtk4_GskPath, get_bounds);
ZEND_METHOD(Gtk4_GskPath, get_closest_point);
ZEND_METHOD(Gtk4_GskPath, get_end_point);
ZEND_METHOD(Gtk4_GskPath, get_start_point);
ZEND_METHOD(Gtk4_GskPath, get_stroke_bounds);
ZEND_METHOD(Gtk4_GskPath, in_fill);
ZEND_METHOD(Gtk4_GskPath, is_closed);
ZEND_METHOD(Gtk4_GskPath, is_empty);
ZEND_METHOD(Gtk4_GskPath, to_cairo);
ZEND_METHOD(Gtk4_GskPath, to_string);
ZEND_METHOD(Gtk4_GskPath, parse);
ZEND_METHOD(Gtk4_GskPathBuilder, __construct);
ZEND_METHOD(Gtk4_GskPathBuilder, add_circle);
ZEND_METHOD(Gtk4_GskPathBuilder, add_path);
ZEND_METHOD(Gtk4_GskPathBuilder, add_rect);
ZEND_METHOD(Gtk4_GskPathBuilder, add_reverse_path);
ZEND_METHOD(Gtk4_GskPathBuilder, add_rounded_rect);
ZEND_METHOD(Gtk4_GskPathBuilder, add_segment);
ZEND_METHOD(Gtk4_GskPathBuilder, arc_to);
ZEND_METHOD(Gtk4_GskPathBuilder, close);
ZEND_METHOD(Gtk4_GskPathBuilder, conic_to);
ZEND_METHOD(Gtk4_GskPathBuilder, cubic_to);
ZEND_METHOD(Gtk4_GskPathBuilder, get_current_point);
ZEND_METHOD(Gtk4_GskPathBuilder, html_arc_to);
ZEND_METHOD(Gtk4_GskPathBuilder, line_to);
ZEND_METHOD(Gtk4_GskPathBuilder, move_to);
ZEND_METHOD(Gtk4_GskPathBuilder, quad_to);
ZEND_METHOD(Gtk4_GskPathBuilder, rel_arc_to);
ZEND_METHOD(Gtk4_GskPathBuilder, rel_conic_to);
ZEND_METHOD(Gtk4_GskPathBuilder, rel_cubic_to);
ZEND_METHOD(Gtk4_GskPathBuilder, rel_html_arc_to);
ZEND_METHOD(Gtk4_GskPathBuilder, rel_line_to);
ZEND_METHOD(Gtk4_GskPathBuilder, rel_move_to);
ZEND_METHOD(Gtk4_GskPathBuilder, rel_quad_to);
ZEND_METHOD(Gtk4_GskPathBuilder, rel_svg_arc_to);
ZEND_METHOD(Gtk4_GskPathBuilder, svg_arc_to);
ZEND_METHOD(Gtk4_GskPathBuilder, to_path);
ZEND_METHOD(Gtk4_GskPathMeasure, __construct);
ZEND_METHOD(Gtk4_GskPathMeasure, new_with_tolerance);
ZEND_METHOD(Gtk4_GskPathMeasure, get_length);
ZEND_METHOD(Gtk4_GskPathMeasure, get_path);
ZEND_METHOD(Gtk4_GskPathMeasure, get_point);
ZEND_METHOD(Gtk4_GskPathMeasure, get_tolerance);
ZEND_METHOD(Gtk4_GskPathPoint, __construct);
ZEND_METHOD(Gtk4_GskPathPoint, compare);
ZEND_METHOD(Gtk4_GskPathPoint, equal);
ZEND_METHOD(Gtk4_GskPathPoint, get_distance);
ZEND_METHOD(Gtk4_GskPathPoint, get_position);
ZEND_METHOD(Gtk4_GskPathPoint, get_rotation);
ZEND_METHOD(Gtk4_GskRadialGradientNode, get_center);
ZEND_METHOD(Gtk4_GskRadialGradientNode, get_end);
ZEND_METHOD(Gtk4_GskRadialGradientNode, get_hradius);
ZEND_METHOD(Gtk4_GskRadialGradientNode, get_n_color_stops);
ZEND_METHOD(Gtk4_GskRadialGradientNode, get_start);
ZEND_METHOD(Gtk4_GskRadialGradientNode, get_vradius);
ZEND_METHOD(Gtk4_GskRadialGradientNode, __construct);
ZEND_METHOD(Gtk4_GskRadialGradientNode, get_color_stops);
ZEND_METHOD(Gtk4_GskRenderNode, __construct);
ZEND_METHOD(Gtk4_GskRenderNode, draw);
ZEND_METHOD(Gtk4_GskRenderNode, get_bounds);
ZEND_METHOD(Gtk4_GskRenderNode, get_node_type);
ZEND_METHOD(Gtk4_GskRenderNode, serialize);
ZEND_METHOD(Gtk4_GskRenderNode, write_to_file);
ZEND_METHOD(Gtk4_GskRenderNode, deserialize);
ZEND_METHOD(Gtk4_GskRenderer, __construct);
ZEND_METHOD(Gtk4_GskRenderer, new_for_surface);
ZEND_METHOD(Gtk4_GskRenderer, get_surface);
ZEND_METHOD(Gtk4_GskRenderer, is_realized);
ZEND_METHOD(Gtk4_GskRenderer, realize);
ZEND_METHOD(Gtk4_GskRenderer, realize_for_display);
ZEND_METHOD(Gtk4_GskRenderer, render_texture);
ZEND_METHOD(Gtk4_GskRenderer, unrealize);
ZEND_METHOD(Gtk4_GskRepeatNode, __construct);
ZEND_METHOD(Gtk4_GskRepeatNode, get_child);
ZEND_METHOD(Gtk4_GskRepeatNode, get_child_bounds);
ZEND_METHOD(Gtk4_GskRepeatingLinearGradientNode, __construct);
ZEND_METHOD(Gtk4_GskRepeatingRadialGradientNode, __construct);
ZEND_METHOD(Gtk4_GskRoundedClipNode, __construct);
ZEND_METHOD(Gtk4_GskRoundedClipNode, get_child);
ZEND_METHOD(Gtk4_GskRoundedClipNode, get_clip);
ZEND_METHOD(Gtk4_GskShadowNode, get_child);
ZEND_METHOD(Gtk4_GskShadowNode, get_n_shadows);
ZEND_METHOD(Gtk4_GskShadowNode, __construct);
ZEND_METHOD(Gtk4_GskShadowNode, get_shadow);
ZEND_METHOD(Gtk4_GskStroke, __construct);
ZEND_METHOD(Gtk4_GskStroke, get_dash_offset);
ZEND_METHOD(Gtk4_GskStroke, get_line_cap);
ZEND_METHOD(Gtk4_GskStroke, get_line_join);
ZEND_METHOD(Gtk4_GskStroke, get_line_width);
ZEND_METHOD(Gtk4_GskStroke, get_miter_limit);
ZEND_METHOD(Gtk4_GskStroke, set_dash_offset);
ZEND_METHOD(Gtk4_GskStroke, set_line_cap);
ZEND_METHOD(Gtk4_GskStroke, set_line_join);
ZEND_METHOD(Gtk4_GskStroke, set_line_width);
ZEND_METHOD(Gtk4_GskStroke, set_miter_limit);
ZEND_METHOD(Gtk4_GskStroke, to_cairo);
ZEND_METHOD(Gtk4_GskStroke, get_dash);
ZEND_METHOD(Gtk4_GskStroke, set_dash);
ZEND_METHOD(Gtk4_GskStrokeNode, __construct);
ZEND_METHOD(Gtk4_GskStrokeNode, get_child);
ZEND_METHOD(Gtk4_GskStrokeNode, get_path);
ZEND_METHOD(Gtk4_GskStrokeNode, get_stroke);
ZEND_METHOD(Gtk4_GskSubsurfaceNode, __construct);
ZEND_METHOD(Gtk4_GskSubsurfaceNode, get_child);
ZEND_METHOD(Gtk4_GskTextNode, __construct);
ZEND_METHOD(Gtk4_GskTextNode, get_color);
ZEND_METHOD(Gtk4_GskTextNode, get_num_glyphs);
ZEND_METHOD(Gtk4_GskTextNode, get_offset);
ZEND_METHOD(Gtk4_GskTextNode, has_color_glyphs);
ZEND_METHOD(Gtk4_GskTextureNode, __construct);
ZEND_METHOD(Gtk4_GskTextureNode, get_texture);
ZEND_METHOD(Gtk4_GskTextureScaleNode, __construct);
ZEND_METHOD(Gtk4_GskTextureScaleNode, get_filter);
ZEND_METHOD(Gtk4_GskTextureScaleNode, get_texture);
ZEND_METHOD(Gtk4_GskTransform, __construct);
ZEND_METHOD(Gtk4_GskTransform, equal);
ZEND_METHOD(Gtk4_GskTransform, get_category);
ZEND_METHOD(Gtk4_GskTransform, invert);
ZEND_METHOD(Gtk4_GskTransform, perspective);
ZEND_METHOD(Gtk4_GskTransform, rotate);
ZEND_METHOD(Gtk4_GskTransform, scale);
ZEND_METHOD(Gtk4_GskTransform, scale_3d);
ZEND_METHOD(Gtk4_GskTransform, skew);
ZEND_METHOD(Gtk4_GskTransform, to_2d);
ZEND_METHOD(Gtk4_GskTransform, to_2d_components);
ZEND_METHOD(Gtk4_GskTransform, to_affine);
ZEND_METHOD(Gtk4_GskTransform, to_string);
ZEND_METHOD(Gtk4_GskTransform, to_translate);
ZEND_METHOD(Gtk4_GskTransform, transform);
ZEND_METHOD(Gtk4_GskTransform, transform_bounds);
ZEND_METHOD(Gtk4_GskTransform, transform_point);
ZEND_METHOD(Gtk4_GskTransform, translate);
ZEND_METHOD(Gtk4_GskTransform, parse);
ZEND_METHOD(Gtk4_GskTransformNode, __construct);
ZEND_METHOD(Gtk4_GskTransformNode, get_child);
ZEND_METHOD(Gtk4_GskTransformNode, get_transform);

static const zend_function_entry class_Gtk4_GskBlendNode_methods[] = {
	ZEND_ME(Gtk4_GskBlendNode, __construct, arginfo_class_Gtk4_GskBlendNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskBlendNode, get_blend_mode, arginfo_class_Gtk4_GskBlendNode_get_blend_mode, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskBlendNode, get_bottom_child, arginfo_class_Gtk4_GskBlendNode_get_bottom_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskBlendNode, get_top_child, arginfo_class_Gtk4_GskBlendNode_get_top_child, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskBlurNode_methods[] = {
	ZEND_ME(Gtk4_GskBlurNode, __construct, arginfo_class_Gtk4_GskBlurNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskBlurNode, get_child, arginfo_class_Gtk4_GskBlurNode_get_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskBlurNode, get_radius, arginfo_class_Gtk4_GskBlurNode_get_radius, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskBorderNode_methods[] = {
	ZEND_ME(Gtk4_GskBorderNode, get_colors, arginfo_class_Gtk4_GskBorderNode_get_colors, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskBorderNode, get_outline, arginfo_class_Gtk4_GskBorderNode_get_outline, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskBorderNode, __construct, arginfo_class_Gtk4_GskBorderNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskBorderNode, get_widths, arginfo_class_Gtk4_GskBorderNode_get_widths, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskCairoNode_methods[] = {
	ZEND_ME(Gtk4_GskCairoNode, __construct, arginfo_class_Gtk4_GskCairoNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskCairoNode, get_draw_context, arginfo_class_Gtk4_GskCairoNode_get_draw_context, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskCairoNode, get_surface, arginfo_class_Gtk4_GskCairoNode_get_surface, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskCairoRenderer_methods[] = {
	ZEND_ME(Gtk4_GskCairoRenderer, __construct, arginfo_class_Gtk4_GskCairoRenderer___construct, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskClipNode_methods[] = {
	ZEND_ME(Gtk4_GskClipNode, __construct, arginfo_class_Gtk4_GskClipNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskClipNode, get_child, arginfo_class_Gtk4_GskClipNode_get_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskClipNode, get_clip, arginfo_class_Gtk4_GskClipNode_get_clip, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskColorMatrixNode_methods[] = {
	ZEND_ME(Gtk4_GskColorMatrixNode, __construct, arginfo_class_Gtk4_GskColorMatrixNode___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GskColorMatrixNode, get_child, arginfo_class_Gtk4_GskColorMatrixNode_get_child, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskColorNode_methods[] = {
	ZEND_ME(Gtk4_GskColorNode, __construct, arginfo_class_Gtk4_GskColorNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskColorNode, get_color, arginfo_class_Gtk4_GskColorNode_get_color, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskConicGradientNode_methods[] = {
	ZEND_ME(Gtk4_GskConicGradientNode, get_angle, arginfo_class_Gtk4_GskConicGradientNode_get_angle, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskConicGradientNode, get_center, arginfo_class_Gtk4_GskConicGradientNode_get_center, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskConicGradientNode, get_n_color_stops, arginfo_class_Gtk4_GskConicGradientNode_get_n_color_stops, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskConicGradientNode, get_rotation, arginfo_class_Gtk4_GskConicGradientNode_get_rotation, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskConicGradientNode, __construct, arginfo_class_Gtk4_GskConicGradientNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskConicGradientNode, get_color_stops, arginfo_class_Gtk4_GskConicGradientNode_get_color_stops, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskContainerNode_methods[] = {
	ZEND_ME(Gtk4_GskContainerNode, get_child, arginfo_class_Gtk4_GskContainerNode_get_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskContainerNode, get_n_children, arginfo_class_Gtk4_GskContainerNode_get_n_children, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskContainerNode, __construct, arginfo_class_Gtk4_GskContainerNode___construct, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskCrossFadeNode_methods[] = {
	ZEND_ME(Gtk4_GskCrossFadeNode, __construct, arginfo_class_Gtk4_GskCrossFadeNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskCrossFadeNode, get_end_child, arginfo_class_Gtk4_GskCrossFadeNode_get_end_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskCrossFadeNode, get_progress, arginfo_class_Gtk4_GskCrossFadeNode_get_progress, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskCrossFadeNode, get_start_child, arginfo_class_Gtk4_GskCrossFadeNode_get_start_child, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskDebugNode_methods[] = {
	ZEND_ME(Gtk4_GskDebugNode, __construct, arginfo_class_Gtk4_GskDebugNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskDebugNode, get_child, arginfo_class_Gtk4_GskDebugNode_get_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskDebugNode, get_message, arginfo_class_Gtk4_GskDebugNode_get_message, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskFillNode_methods[] = {
	ZEND_ME(Gtk4_GskFillNode, __construct, arginfo_class_Gtk4_GskFillNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskFillNode, get_child, arginfo_class_Gtk4_GskFillNode_get_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskFillNode, get_fill_rule, arginfo_class_Gtk4_GskFillNode_get_fill_rule, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskFillNode, get_path, arginfo_class_Gtk4_GskFillNode_get_path, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskGLRenderer_methods[] = {
	ZEND_ME(Gtk4_GskGLRenderer, __construct, arginfo_class_Gtk4_GskGLRenderer___construct, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskInsetShadowNode_methods[] = {
	ZEND_ME(Gtk4_GskInsetShadowNode, __construct, arginfo_class_Gtk4_GskInsetShadowNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskInsetShadowNode, get_blur_radius, arginfo_class_Gtk4_GskInsetShadowNode_get_blur_radius, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskInsetShadowNode, get_color, arginfo_class_Gtk4_GskInsetShadowNode_get_color, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskInsetShadowNode, get_dx, arginfo_class_Gtk4_GskInsetShadowNode_get_dx, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskInsetShadowNode, get_dy, arginfo_class_Gtk4_GskInsetShadowNode_get_dy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskInsetShadowNode, get_outline, arginfo_class_Gtk4_GskInsetShadowNode_get_outline, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskInsetShadowNode, get_spread, arginfo_class_Gtk4_GskInsetShadowNode_get_spread, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskLinearGradientNode_methods[] = {
	ZEND_ME(Gtk4_GskLinearGradientNode, get_end, arginfo_class_Gtk4_GskLinearGradientNode_get_end, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskLinearGradientNode, get_n_color_stops, arginfo_class_Gtk4_GskLinearGradientNode_get_n_color_stops, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskLinearGradientNode, get_start, arginfo_class_Gtk4_GskLinearGradientNode_get_start, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskLinearGradientNode, __construct, arginfo_class_Gtk4_GskLinearGradientNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskLinearGradientNode, get_color_stops, arginfo_class_Gtk4_GskLinearGradientNode_get_color_stops, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskMaskNode_methods[] = {
	ZEND_ME(Gtk4_GskMaskNode, __construct, arginfo_class_Gtk4_GskMaskNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskMaskNode, get_mask, arginfo_class_Gtk4_GskMaskNode_get_mask, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskMaskNode, get_mask_mode, arginfo_class_Gtk4_GskMaskNode_get_mask_mode, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskMaskNode, get_source, arginfo_class_Gtk4_GskMaskNode_get_source, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskOpacityNode_methods[] = {
	ZEND_ME(Gtk4_GskOpacityNode, __construct, arginfo_class_Gtk4_GskOpacityNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskOpacityNode, get_child, arginfo_class_Gtk4_GskOpacityNode_get_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskOpacityNode, get_opacity, arginfo_class_Gtk4_GskOpacityNode_get_opacity, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskOutsetShadowNode_methods[] = {
	ZEND_ME(Gtk4_GskOutsetShadowNode, __construct, arginfo_class_Gtk4_GskOutsetShadowNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskOutsetShadowNode, get_blur_radius, arginfo_class_Gtk4_GskOutsetShadowNode_get_blur_radius, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskOutsetShadowNode, get_color, arginfo_class_Gtk4_GskOutsetShadowNode_get_color, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskOutsetShadowNode, get_dx, arginfo_class_Gtk4_GskOutsetShadowNode_get_dx, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskOutsetShadowNode, get_dy, arginfo_class_Gtk4_GskOutsetShadowNode_get_dy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskOutsetShadowNode, get_outline, arginfo_class_Gtk4_GskOutsetShadowNode_get_outline, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskOutsetShadowNode, get_spread, arginfo_class_Gtk4_GskOutsetShadowNode_get_spread, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskPath_methods[] = {
	ZEND_ME(Gtk4_GskPath, __construct, arginfo_class_Gtk4_GskPath___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GskPath, get_bounds, arginfo_class_Gtk4_GskPath_get_bounds, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPath, get_closest_point, arginfo_class_Gtk4_GskPath_get_closest_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPath, get_end_point, arginfo_class_Gtk4_GskPath_get_end_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPath, get_start_point, arginfo_class_Gtk4_GskPath_get_start_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPath, get_stroke_bounds, arginfo_class_Gtk4_GskPath_get_stroke_bounds, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPath, in_fill, arginfo_class_Gtk4_GskPath_in_fill, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPath, is_closed, arginfo_class_Gtk4_GskPath_is_closed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPath, is_empty, arginfo_class_Gtk4_GskPath_is_empty, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPath, to_cairo, arginfo_class_Gtk4_GskPath_to_cairo, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPath, to_string, arginfo_class_Gtk4_GskPath_to_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPath, parse, arginfo_class_Gtk4_GskPath_parse, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskPathBuilder_methods[] = {
	ZEND_ME(Gtk4_GskPathBuilder, __construct, arginfo_class_Gtk4_GskPathBuilder___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, add_circle, arginfo_class_Gtk4_GskPathBuilder_add_circle, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, add_path, arginfo_class_Gtk4_GskPathBuilder_add_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, add_rect, arginfo_class_Gtk4_GskPathBuilder_add_rect, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, add_reverse_path, arginfo_class_Gtk4_GskPathBuilder_add_reverse_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, add_rounded_rect, arginfo_class_Gtk4_GskPathBuilder_add_rounded_rect, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, add_segment, arginfo_class_Gtk4_GskPathBuilder_add_segment, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, arc_to, arginfo_class_Gtk4_GskPathBuilder_arc_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, close, arginfo_class_Gtk4_GskPathBuilder_close, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, conic_to, arginfo_class_Gtk4_GskPathBuilder_conic_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, cubic_to, arginfo_class_Gtk4_GskPathBuilder_cubic_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, get_current_point, arginfo_class_Gtk4_GskPathBuilder_get_current_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, html_arc_to, arginfo_class_Gtk4_GskPathBuilder_html_arc_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, line_to, arginfo_class_Gtk4_GskPathBuilder_line_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, move_to, arginfo_class_Gtk4_GskPathBuilder_move_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, quad_to, arginfo_class_Gtk4_GskPathBuilder_quad_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, rel_arc_to, arginfo_class_Gtk4_GskPathBuilder_rel_arc_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, rel_conic_to, arginfo_class_Gtk4_GskPathBuilder_rel_conic_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, rel_cubic_to, arginfo_class_Gtk4_GskPathBuilder_rel_cubic_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, rel_html_arc_to, arginfo_class_Gtk4_GskPathBuilder_rel_html_arc_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, rel_line_to, arginfo_class_Gtk4_GskPathBuilder_rel_line_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, rel_move_to, arginfo_class_Gtk4_GskPathBuilder_rel_move_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, rel_quad_to, arginfo_class_Gtk4_GskPathBuilder_rel_quad_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, rel_svg_arc_to, arginfo_class_Gtk4_GskPathBuilder_rel_svg_arc_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, svg_arc_to, arginfo_class_Gtk4_GskPathBuilder_svg_arc_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathBuilder, to_path, arginfo_class_Gtk4_GskPathBuilder_to_path, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskPathMeasure_methods[] = {
	ZEND_ME(Gtk4_GskPathMeasure, __construct, arginfo_class_Gtk4_GskPathMeasure___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathMeasure, new_with_tolerance, arginfo_class_Gtk4_GskPathMeasure_new_with_tolerance, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GskPathMeasure, get_length, arginfo_class_Gtk4_GskPathMeasure_get_length, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathMeasure, get_path, arginfo_class_Gtk4_GskPathMeasure_get_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathMeasure, get_point, arginfo_class_Gtk4_GskPathMeasure_get_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathMeasure, get_tolerance, arginfo_class_Gtk4_GskPathMeasure_get_tolerance, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskPathPoint_methods[] = {
	ZEND_ME(Gtk4_GskPathPoint, __construct, arginfo_class_Gtk4_GskPathPoint___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GskPathPoint, compare, arginfo_class_Gtk4_GskPathPoint_compare, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathPoint, equal, arginfo_class_Gtk4_GskPathPoint_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathPoint, get_distance, arginfo_class_Gtk4_GskPathPoint_get_distance, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathPoint, get_position, arginfo_class_Gtk4_GskPathPoint_get_position, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskPathPoint, get_rotation, arginfo_class_Gtk4_GskPathPoint_get_rotation, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskRadialGradientNode_methods[] = {
	ZEND_ME(Gtk4_GskRadialGradientNode, get_center, arginfo_class_Gtk4_GskRadialGradientNode_get_center, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRadialGradientNode, get_end, arginfo_class_Gtk4_GskRadialGradientNode_get_end, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRadialGradientNode, get_hradius, arginfo_class_Gtk4_GskRadialGradientNode_get_hradius, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRadialGradientNode, get_n_color_stops, arginfo_class_Gtk4_GskRadialGradientNode_get_n_color_stops, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRadialGradientNode, get_start, arginfo_class_Gtk4_GskRadialGradientNode_get_start, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRadialGradientNode, get_vradius, arginfo_class_Gtk4_GskRadialGradientNode_get_vradius, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRadialGradientNode, __construct, arginfo_class_Gtk4_GskRadialGradientNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRadialGradientNode, get_color_stops, arginfo_class_Gtk4_GskRadialGradientNode_get_color_stops, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskRenderNode_methods[] = {
	ZEND_ME(Gtk4_GskRenderNode, __construct, arginfo_class_Gtk4_GskRenderNode___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GskRenderNode, draw, arginfo_class_Gtk4_GskRenderNode_draw, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRenderNode, get_bounds, arginfo_class_Gtk4_GskRenderNode_get_bounds, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRenderNode, get_node_type, arginfo_class_Gtk4_GskRenderNode_get_node_type, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRenderNode, serialize, arginfo_class_Gtk4_GskRenderNode_serialize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRenderNode, write_to_file, arginfo_class_Gtk4_GskRenderNode_write_to_file, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRenderNode, deserialize, arginfo_class_Gtk4_GskRenderNode_deserialize, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskRenderer_methods[] = {
	ZEND_ME(Gtk4_GskRenderer, __construct, arginfo_class_Gtk4_GskRenderer___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRenderer, new_for_surface, arginfo_class_Gtk4_GskRenderer_new_for_surface, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GskRenderer, get_surface, arginfo_class_Gtk4_GskRenderer_get_surface, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRenderer, is_realized, arginfo_class_Gtk4_GskRenderer_is_realized, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRenderer, realize, arginfo_class_Gtk4_GskRenderer_realize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRenderer, realize_for_display, arginfo_class_Gtk4_GskRenderer_realize_for_display, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRenderer, render_texture, arginfo_class_Gtk4_GskRenderer_render_texture, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRenderer, unrealize, arginfo_class_Gtk4_GskRenderer_unrealize, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskRepeatNode_methods[] = {
	ZEND_ME(Gtk4_GskRepeatNode, __construct, arginfo_class_Gtk4_GskRepeatNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRepeatNode, get_child, arginfo_class_Gtk4_GskRepeatNode_get_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRepeatNode, get_child_bounds, arginfo_class_Gtk4_GskRepeatNode_get_child_bounds, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskRepeatingLinearGradientNode_methods[] = {
	ZEND_ME(Gtk4_GskRepeatingLinearGradientNode, __construct, arginfo_class_Gtk4_GskRepeatingLinearGradientNode___construct, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskRepeatingRadialGradientNode_methods[] = {
	ZEND_ME(Gtk4_GskRepeatingRadialGradientNode, __construct, arginfo_class_Gtk4_GskRepeatingRadialGradientNode___construct, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskRoundedClipNode_methods[] = {
	ZEND_ME(Gtk4_GskRoundedClipNode, __construct, arginfo_class_Gtk4_GskRoundedClipNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRoundedClipNode, get_child, arginfo_class_Gtk4_GskRoundedClipNode_get_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRoundedClipNode, get_clip, arginfo_class_Gtk4_GskRoundedClipNode_get_clip, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskShadowNode_methods[] = {
	ZEND_ME(Gtk4_GskShadowNode, get_child, arginfo_class_Gtk4_GskShadowNode_get_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskShadowNode, get_n_shadows, arginfo_class_Gtk4_GskShadowNode_get_n_shadows, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskShadowNode, __construct, arginfo_class_Gtk4_GskShadowNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskShadowNode, get_shadow, arginfo_class_Gtk4_GskShadowNode_get_shadow, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskStroke_methods[] = {
	ZEND_ME(Gtk4_GskStroke, __construct, arginfo_class_Gtk4_GskStroke___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStroke, get_dash_offset, arginfo_class_Gtk4_GskStroke_get_dash_offset, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStroke, get_line_cap, arginfo_class_Gtk4_GskStroke_get_line_cap, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStroke, get_line_join, arginfo_class_Gtk4_GskStroke_get_line_join, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStroke, get_line_width, arginfo_class_Gtk4_GskStroke_get_line_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStroke, get_miter_limit, arginfo_class_Gtk4_GskStroke_get_miter_limit, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStroke, set_dash_offset, arginfo_class_Gtk4_GskStroke_set_dash_offset, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStroke, set_line_cap, arginfo_class_Gtk4_GskStroke_set_line_cap, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStroke, set_line_join, arginfo_class_Gtk4_GskStroke_set_line_join, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStroke, set_line_width, arginfo_class_Gtk4_GskStroke_set_line_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStroke, set_miter_limit, arginfo_class_Gtk4_GskStroke_set_miter_limit, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStroke, to_cairo, arginfo_class_Gtk4_GskStroke_to_cairo, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStroke, get_dash, arginfo_class_Gtk4_GskStroke_get_dash, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStroke, set_dash, arginfo_class_Gtk4_GskStroke_set_dash, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskStrokeNode_methods[] = {
	ZEND_ME(Gtk4_GskStrokeNode, __construct, arginfo_class_Gtk4_GskStrokeNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStrokeNode, get_child, arginfo_class_Gtk4_GskStrokeNode_get_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStrokeNode, get_path, arginfo_class_Gtk4_GskStrokeNode_get_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskStrokeNode, get_stroke, arginfo_class_Gtk4_GskStrokeNode_get_stroke, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskSubsurfaceNode_methods[] = {
	ZEND_ME(Gtk4_GskSubsurfaceNode, __construct, arginfo_class_Gtk4_GskSubsurfaceNode___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GskSubsurfaceNode, get_child, arginfo_class_Gtk4_GskSubsurfaceNode_get_child, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskTextNode_methods[] = {
	ZEND_ME(Gtk4_GskTextNode, __construct, arginfo_class_Gtk4_GskTextNode___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GskTextNode, get_color, arginfo_class_Gtk4_GskTextNode_get_color, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTextNode, get_num_glyphs, arginfo_class_Gtk4_GskTextNode_get_num_glyphs, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTextNode, get_offset, arginfo_class_Gtk4_GskTextNode_get_offset, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTextNode, has_color_glyphs, arginfo_class_Gtk4_GskTextNode_has_color_glyphs, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskTextureNode_methods[] = {
	ZEND_ME(Gtk4_GskTextureNode, __construct, arginfo_class_Gtk4_GskTextureNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTextureNode, get_texture, arginfo_class_Gtk4_GskTextureNode_get_texture, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskTextureScaleNode_methods[] = {
	ZEND_ME(Gtk4_GskTextureScaleNode, __construct, arginfo_class_Gtk4_GskTextureScaleNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTextureScaleNode, get_filter, arginfo_class_Gtk4_GskTextureScaleNode_get_filter, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTextureScaleNode, get_texture, arginfo_class_Gtk4_GskTextureScaleNode_get_texture, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskTransform_methods[] = {
	ZEND_ME(Gtk4_GskTransform, __construct, arginfo_class_Gtk4_GskTransform___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, equal, arginfo_class_Gtk4_GskTransform_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, get_category, arginfo_class_Gtk4_GskTransform_get_category, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, invert, arginfo_class_Gtk4_GskTransform_invert, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, perspective, arginfo_class_Gtk4_GskTransform_perspective, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, rotate, arginfo_class_Gtk4_GskTransform_rotate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, scale, arginfo_class_Gtk4_GskTransform_scale, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, scale_3d, arginfo_class_Gtk4_GskTransform_scale_3d, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, skew, arginfo_class_Gtk4_GskTransform_skew, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, to_2d, arginfo_class_Gtk4_GskTransform_to_2d, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, to_2d_components, arginfo_class_Gtk4_GskTransform_to_2d_components, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, to_affine, arginfo_class_Gtk4_GskTransform_to_affine, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, to_string, arginfo_class_Gtk4_GskTransform_to_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, to_translate, arginfo_class_Gtk4_GskTransform_to_translate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, transform, arginfo_class_Gtk4_GskTransform_transform, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, transform_bounds, arginfo_class_Gtk4_GskTransform_transform_bounds, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, transform_point, arginfo_class_Gtk4_GskTransform_transform_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, translate, arginfo_class_Gtk4_GskTransform_translate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransform, parse, arginfo_class_Gtk4_GskTransform_parse, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskTransformNode_methods[] = {
	ZEND_ME(Gtk4_GskTransformNode, __construct, arginfo_class_Gtk4_GskTransformNode___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransformNode, get_child, arginfo_class_Gtk4_GskTransformNode_get_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskTransformNode, get_transform, arginfo_class_Gtk4_GskTransformNode_get_transform, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static zend_class_entry *register_class_Gtk4_GskBlendMode(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GskBlendMode", IS_LONG, NULL);

	zval enum_case_Default_value;
	ZVAL_LONG(&enum_case_Default_value, 0);
	zend_enum_add_case_cstr(class_entry, "Default", &enum_case_Default_value);

	zval enum_case_Multiply_value;
	ZVAL_LONG(&enum_case_Multiply_value, 1);
	zend_enum_add_case_cstr(class_entry, "Multiply", &enum_case_Multiply_value);

	zval enum_case_Screen_value;
	ZVAL_LONG(&enum_case_Screen_value, 2);
	zend_enum_add_case_cstr(class_entry, "Screen", &enum_case_Screen_value);

	zval enum_case_Overlay_value;
	ZVAL_LONG(&enum_case_Overlay_value, 3);
	zend_enum_add_case_cstr(class_entry, "Overlay", &enum_case_Overlay_value);

	zval enum_case_Darken_value;
	ZVAL_LONG(&enum_case_Darken_value, 4);
	zend_enum_add_case_cstr(class_entry, "Darken", &enum_case_Darken_value);

	zval enum_case_Lighten_value;
	ZVAL_LONG(&enum_case_Lighten_value, 5);
	zend_enum_add_case_cstr(class_entry, "Lighten", &enum_case_Lighten_value);

	zval enum_case_ColorDodge_value;
	ZVAL_LONG(&enum_case_ColorDodge_value, 6);
	zend_enum_add_case_cstr(class_entry, "ColorDodge", &enum_case_ColorDodge_value);

	zval enum_case_ColorBurn_value;
	ZVAL_LONG(&enum_case_ColorBurn_value, 7);
	zend_enum_add_case_cstr(class_entry, "ColorBurn", &enum_case_ColorBurn_value);

	zval enum_case_HardLight_value;
	ZVAL_LONG(&enum_case_HardLight_value, 8);
	zend_enum_add_case_cstr(class_entry, "HardLight", &enum_case_HardLight_value);

	zval enum_case_SoftLight_value;
	ZVAL_LONG(&enum_case_SoftLight_value, 9);
	zend_enum_add_case_cstr(class_entry, "SoftLight", &enum_case_SoftLight_value);

	zval enum_case_Difference_value;
	ZVAL_LONG(&enum_case_Difference_value, 10);
	zend_enum_add_case_cstr(class_entry, "Difference", &enum_case_Difference_value);

	zval enum_case_Exclusion_value;
	ZVAL_LONG(&enum_case_Exclusion_value, 11);
	zend_enum_add_case_cstr(class_entry, "Exclusion", &enum_case_Exclusion_value);

	zval enum_case_Color_value;
	ZVAL_LONG(&enum_case_Color_value, 12);
	zend_enum_add_case_cstr(class_entry, "Color", &enum_case_Color_value);

	zval enum_case_Hue_value;
	ZVAL_LONG(&enum_case_Hue_value, 13);
	zend_enum_add_case_cstr(class_entry, "Hue", &enum_case_Hue_value);

	zval enum_case_Saturation_value;
	ZVAL_LONG(&enum_case_Saturation_value, 14);
	zend_enum_add_case_cstr(class_entry, "Saturation", &enum_case_Saturation_value);

	zval enum_case_Luminosity_value;
	ZVAL_LONG(&enum_case_Luminosity_value, 15);
	zend_enum_add_case_cstr(class_entry, "Luminosity", &enum_case_Luminosity_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskBlendNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskBlendNode", class_Gtk4_GskBlendNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskBlurNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskBlurNode", class_Gtk4_GskBlurNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskBorderNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskBorderNode", class_Gtk4_GskBorderNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskCairoNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskCairoNode", class_Gtk4_GskCairoNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskCairoRenderer(zend_class_entry *class_entry_Gtk4_GskRenderer)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskCairoRenderer", class_Gtk4_GskCairoRenderer_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderer, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskClipNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskClipNode", class_Gtk4_GskClipNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskColorMatrixNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskColorMatrixNode", class_Gtk4_GskColorMatrixNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskColorNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskColorNode", class_Gtk4_GskColorNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskConicGradientNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskConicGradientNode", class_Gtk4_GskConicGradientNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskContainerNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskContainerNode", class_Gtk4_GskContainerNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskCorner(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GskCorner", IS_LONG, NULL);

	zval enum_case_TopLeft_value;
	ZVAL_LONG(&enum_case_TopLeft_value, 0);
	zend_enum_add_case_cstr(class_entry, "TopLeft", &enum_case_TopLeft_value);

	zval enum_case_TopRight_value;
	ZVAL_LONG(&enum_case_TopRight_value, 1);
	zend_enum_add_case_cstr(class_entry, "TopRight", &enum_case_TopRight_value);

	zval enum_case_BottomRight_value;
	ZVAL_LONG(&enum_case_BottomRight_value, 2);
	zend_enum_add_case_cstr(class_entry, "BottomRight", &enum_case_BottomRight_value);

	zval enum_case_BottomLeft_value;
	ZVAL_LONG(&enum_case_BottomLeft_value, 3);
	zend_enum_add_case_cstr(class_entry, "BottomLeft", &enum_case_BottomLeft_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskCrossFadeNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskCrossFadeNode", class_Gtk4_GskCrossFadeNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskDebugNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskDebugNode", class_Gtk4_GskDebugNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskFillNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskFillNode", class_Gtk4_GskFillNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskFillRule(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GskFillRule", IS_LONG, NULL);

	zval enum_case_Winding_value;
	ZVAL_LONG(&enum_case_Winding_value, 0);
	zend_enum_add_case_cstr(class_entry, "Winding", &enum_case_Winding_value);

	zval enum_case_EvenOdd_value;
	ZVAL_LONG(&enum_case_EvenOdd_value, 1);
	zend_enum_add_case_cstr(class_entry, "EvenOdd", &enum_case_EvenOdd_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskGLRenderer(zend_class_entry *class_entry_Gtk4_GskRenderer)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskGLRenderer", class_Gtk4_GskGLRenderer_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderer, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskInsetShadowNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskInsetShadowNode", class_Gtk4_GskInsetShadowNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskLineCap(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GskLineCap", IS_LONG, NULL);

	zval enum_case_Butt_value;
	ZVAL_LONG(&enum_case_Butt_value, 0);
	zend_enum_add_case_cstr(class_entry, "Butt", &enum_case_Butt_value);

	zval enum_case_Round_value;
	ZVAL_LONG(&enum_case_Round_value, 1);
	zend_enum_add_case_cstr(class_entry, "Round", &enum_case_Round_value);

	zval enum_case_Square_value;
	ZVAL_LONG(&enum_case_Square_value, 2);
	zend_enum_add_case_cstr(class_entry, "Square", &enum_case_Square_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskLineJoin(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GskLineJoin", IS_LONG, NULL);

	zval enum_case_Miter_value;
	ZVAL_LONG(&enum_case_Miter_value, 0);
	zend_enum_add_case_cstr(class_entry, "Miter", &enum_case_Miter_value);

	zval enum_case_Round_value;
	ZVAL_LONG(&enum_case_Round_value, 1);
	zend_enum_add_case_cstr(class_entry, "Round", &enum_case_Round_value);

	zval enum_case_Bevel_value;
	ZVAL_LONG(&enum_case_Bevel_value, 2);
	zend_enum_add_case_cstr(class_entry, "Bevel", &enum_case_Bevel_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskLinearGradientNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskLinearGradientNode", class_Gtk4_GskLinearGradientNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskMaskMode(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GskMaskMode", IS_LONG, NULL);

	zval enum_case_Alpha_value;
	ZVAL_LONG(&enum_case_Alpha_value, 0);
	zend_enum_add_case_cstr(class_entry, "Alpha", &enum_case_Alpha_value);

	zval enum_case_InvertedAlpha_value;
	ZVAL_LONG(&enum_case_InvertedAlpha_value, 1);
	zend_enum_add_case_cstr(class_entry, "InvertedAlpha", &enum_case_InvertedAlpha_value);

	zval enum_case_Luminance_value;
	ZVAL_LONG(&enum_case_Luminance_value, 2);
	zend_enum_add_case_cstr(class_entry, "Luminance", &enum_case_Luminance_value);

	zval enum_case_InvertedLuminance_value;
	ZVAL_LONG(&enum_case_InvertedLuminance_value, 3);
	zend_enum_add_case_cstr(class_entry, "InvertedLuminance", &enum_case_InvertedLuminance_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskMaskNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskMaskNode", class_Gtk4_GskMaskNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskOpacityNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskOpacityNode", class_Gtk4_GskOpacityNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskOutsetShadowNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskOutsetShadowNode", class_Gtk4_GskOutsetShadowNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskPath(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskPath", class_Gtk4_GskPath_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskPathBuilder(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskPathBuilder", class_Gtk4_GskPathBuilder_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskPathDirection(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GskPathDirection", IS_LONG, NULL);

	zval enum_case_FromStart_value;
	ZVAL_LONG(&enum_case_FromStart_value, 0);
	zend_enum_add_case_cstr(class_entry, "FromStart", &enum_case_FromStart_value);

	zval enum_case_ToStart_value;
	ZVAL_LONG(&enum_case_ToStart_value, 1);
	zend_enum_add_case_cstr(class_entry, "ToStart", &enum_case_ToStart_value);

	zval enum_case_ToEnd_value;
	ZVAL_LONG(&enum_case_ToEnd_value, 2);
	zend_enum_add_case_cstr(class_entry, "ToEnd", &enum_case_ToEnd_value);

	zval enum_case_FromEnd_value;
	ZVAL_LONG(&enum_case_FromEnd_value, 3);
	zend_enum_add_case_cstr(class_entry, "FromEnd", &enum_case_FromEnd_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskPathForeachFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskPathForeachFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_ONLY_LINES_value;
	ZVAL_LONG(&const_ONLY_LINES_value, 0);
	zend_string *const_ONLY_LINES_name = zend_string_init_interned("ONLY_LINES", sizeof("ONLY_LINES") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_ONLY_LINES_name, &const_ONLY_LINES_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_ONLY_LINES_name);

	zval const_QUAD_value;
	ZVAL_LONG(&const_QUAD_value, 1);
	zend_string *const_QUAD_name = zend_string_init_interned("QUAD", sizeof("QUAD") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_QUAD_name, &const_QUAD_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_QUAD_name);

	zval const_CUBIC_value;
	ZVAL_LONG(&const_CUBIC_value, 2);
	zend_string *const_CUBIC_name = zend_string_init_interned("CUBIC", sizeof("CUBIC") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_CUBIC_name, &const_CUBIC_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_CUBIC_name);

	zval const_CONIC_value;
	ZVAL_LONG(&const_CONIC_value, 4);
	zend_string *const_CONIC_name = zend_string_init_interned("CONIC", sizeof("CONIC") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_CONIC_name, &const_CONIC_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_CONIC_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskPathMeasure(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskPathMeasure", class_Gtk4_GskPathMeasure_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskPathPoint(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskPathPoint", class_Gtk4_GskPathPoint_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskRadialGradientNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskRadialGradientNode", class_Gtk4_GskRadialGradientNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskRenderNode(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskRenderNode", class_Gtk4_GskRenderNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskRenderNodeType(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GskRenderNodeType", IS_LONG, NULL);

	zval enum_case_NotARenderNode_value;
	ZVAL_LONG(&enum_case_NotARenderNode_value, 0);
	zend_enum_add_case_cstr(class_entry, "NotARenderNode", &enum_case_NotARenderNode_value);

	zval enum_case_ContainerNode_value;
	ZVAL_LONG(&enum_case_ContainerNode_value, 1);
	zend_enum_add_case_cstr(class_entry, "ContainerNode", &enum_case_ContainerNode_value);

	zval enum_case_CairoNode_value;
	ZVAL_LONG(&enum_case_CairoNode_value, 2);
	zend_enum_add_case_cstr(class_entry, "CairoNode", &enum_case_CairoNode_value);

	zval enum_case_ColorNode_value;
	ZVAL_LONG(&enum_case_ColorNode_value, 3);
	zend_enum_add_case_cstr(class_entry, "ColorNode", &enum_case_ColorNode_value);

	zval enum_case_LinearGradientNode_value;
	ZVAL_LONG(&enum_case_LinearGradientNode_value, 4);
	zend_enum_add_case_cstr(class_entry, "LinearGradientNode", &enum_case_LinearGradientNode_value);

	zval enum_case_RepeatingLinearGradientNode_value;
	ZVAL_LONG(&enum_case_RepeatingLinearGradientNode_value, 5);
	zend_enum_add_case_cstr(class_entry, "RepeatingLinearGradientNode", &enum_case_RepeatingLinearGradientNode_value);

	zval enum_case_RadialGradientNode_value;
	ZVAL_LONG(&enum_case_RadialGradientNode_value, 6);
	zend_enum_add_case_cstr(class_entry, "RadialGradientNode", &enum_case_RadialGradientNode_value);

	zval enum_case_RepeatingRadialGradientNode_value;
	ZVAL_LONG(&enum_case_RepeatingRadialGradientNode_value, 7);
	zend_enum_add_case_cstr(class_entry, "RepeatingRadialGradientNode", &enum_case_RepeatingRadialGradientNode_value);

	zval enum_case_ConicGradientNode_value;
	ZVAL_LONG(&enum_case_ConicGradientNode_value, 8);
	zend_enum_add_case_cstr(class_entry, "ConicGradientNode", &enum_case_ConicGradientNode_value);

	zval enum_case_BorderNode_value;
	ZVAL_LONG(&enum_case_BorderNode_value, 9);
	zend_enum_add_case_cstr(class_entry, "BorderNode", &enum_case_BorderNode_value);

	zval enum_case_TextureNode_value;
	ZVAL_LONG(&enum_case_TextureNode_value, 10);
	zend_enum_add_case_cstr(class_entry, "TextureNode", &enum_case_TextureNode_value);

	zval enum_case_InsetShadowNode_value;
	ZVAL_LONG(&enum_case_InsetShadowNode_value, 11);
	zend_enum_add_case_cstr(class_entry, "InsetShadowNode", &enum_case_InsetShadowNode_value);

	zval enum_case_OutsetShadowNode_value;
	ZVAL_LONG(&enum_case_OutsetShadowNode_value, 12);
	zend_enum_add_case_cstr(class_entry, "OutsetShadowNode", &enum_case_OutsetShadowNode_value);

	zval enum_case_TransformNode_value;
	ZVAL_LONG(&enum_case_TransformNode_value, 13);
	zend_enum_add_case_cstr(class_entry, "TransformNode", &enum_case_TransformNode_value);

	zval enum_case_OpacityNode_value;
	ZVAL_LONG(&enum_case_OpacityNode_value, 14);
	zend_enum_add_case_cstr(class_entry, "OpacityNode", &enum_case_OpacityNode_value);

	zval enum_case_ColorMatrixNode_value;
	ZVAL_LONG(&enum_case_ColorMatrixNode_value, 15);
	zend_enum_add_case_cstr(class_entry, "ColorMatrixNode", &enum_case_ColorMatrixNode_value);

	zval enum_case_RepeatNode_value;
	ZVAL_LONG(&enum_case_RepeatNode_value, 16);
	zend_enum_add_case_cstr(class_entry, "RepeatNode", &enum_case_RepeatNode_value);

	zval enum_case_ClipNode_value;
	ZVAL_LONG(&enum_case_ClipNode_value, 17);
	zend_enum_add_case_cstr(class_entry, "ClipNode", &enum_case_ClipNode_value);

	zval enum_case_RoundedClipNode_value;
	ZVAL_LONG(&enum_case_RoundedClipNode_value, 18);
	zend_enum_add_case_cstr(class_entry, "RoundedClipNode", &enum_case_RoundedClipNode_value);

	zval enum_case_ShadowNode_value;
	ZVAL_LONG(&enum_case_ShadowNode_value, 19);
	zend_enum_add_case_cstr(class_entry, "ShadowNode", &enum_case_ShadowNode_value);

	zval enum_case_BlendNode_value;
	ZVAL_LONG(&enum_case_BlendNode_value, 20);
	zend_enum_add_case_cstr(class_entry, "BlendNode", &enum_case_BlendNode_value);

	zval enum_case_CrossFadeNode_value;
	ZVAL_LONG(&enum_case_CrossFadeNode_value, 21);
	zend_enum_add_case_cstr(class_entry, "CrossFadeNode", &enum_case_CrossFadeNode_value);

	zval enum_case_TextNode_value;
	ZVAL_LONG(&enum_case_TextNode_value, 22);
	zend_enum_add_case_cstr(class_entry, "TextNode", &enum_case_TextNode_value);

	zval enum_case_BlurNode_value;
	ZVAL_LONG(&enum_case_BlurNode_value, 23);
	zend_enum_add_case_cstr(class_entry, "BlurNode", &enum_case_BlurNode_value);

	zval enum_case_DebugNode_value;
	ZVAL_LONG(&enum_case_DebugNode_value, 24);
	zend_enum_add_case_cstr(class_entry, "DebugNode", &enum_case_DebugNode_value);

	zval enum_case_GlShaderNode_value;
	ZVAL_LONG(&enum_case_GlShaderNode_value, 25);
	zend_enum_add_case_cstr(class_entry, "GlShaderNode", &enum_case_GlShaderNode_value);

	zval enum_case_TextureScaleNode_value;
	ZVAL_LONG(&enum_case_TextureScaleNode_value, 26);
	zend_enum_add_case_cstr(class_entry, "TextureScaleNode", &enum_case_TextureScaleNode_value);

	zval enum_case_MaskNode_value;
	ZVAL_LONG(&enum_case_MaskNode_value, 27);
	zend_enum_add_case_cstr(class_entry, "MaskNode", &enum_case_MaskNode_value);

	zval enum_case_FillNode_value;
	ZVAL_LONG(&enum_case_FillNode_value, 28);
	zend_enum_add_case_cstr(class_entry, "FillNode", &enum_case_FillNode_value);

	zval enum_case_StrokeNode_value;
	ZVAL_LONG(&enum_case_StrokeNode_value, 29);
	zend_enum_add_case_cstr(class_entry, "StrokeNode", &enum_case_StrokeNode_value);

	zval enum_case_SubsurfaceNode_value;
	ZVAL_LONG(&enum_case_SubsurfaceNode_value, 30);
	zend_enum_add_case_cstr(class_entry, "SubsurfaceNode", &enum_case_SubsurfaceNode_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskRenderer(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskRenderer", class_Gtk4_GskRenderer_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskRepeatNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskRepeatNode", class_Gtk4_GskRepeatNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskRepeatingLinearGradientNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskRepeatingLinearGradientNode", class_Gtk4_GskRepeatingLinearGradientNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskRepeatingRadialGradientNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskRepeatingRadialGradientNode", class_Gtk4_GskRepeatingRadialGradientNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskRoundedClipNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskRoundedClipNode", class_Gtk4_GskRoundedClipNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskScalingFilter(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GskScalingFilter", IS_LONG, NULL);

	zval enum_case_Linear_value;
	ZVAL_LONG(&enum_case_Linear_value, 0);
	zend_enum_add_case_cstr(class_entry, "Linear", &enum_case_Linear_value);

	zval enum_case_Nearest_value;
	ZVAL_LONG(&enum_case_Nearest_value, 1);
	zend_enum_add_case_cstr(class_entry, "Nearest", &enum_case_Nearest_value);

	zval enum_case_Trilinear_value;
	ZVAL_LONG(&enum_case_Trilinear_value, 2);
	zend_enum_add_case_cstr(class_entry, "Trilinear", &enum_case_Trilinear_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskShadowNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskShadowNode", class_Gtk4_GskShadowNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskStroke(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskStroke", class_Gtk4_GskStroke_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskStrokeNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskStrokeNode", class_Gtk4_GskStrokeNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskSubsurfaceNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskSubsurfaceNode", class_Gtk4_GskSubsurfaceNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskTextNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskTextNode", class_Gtk4_GskTextNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskTextureNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskTextureNode", class_Gtk4_GskTextureNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskTextureScaleNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskTextureScaleNode", class_Gtk4_GskTextureScaleNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskTransform(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskTransform", class_Gtk4_GskTransform_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskTransformCategory(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GskTransformCategory", IS_LONG, NULL);

	zval enum_case_Unknown_value;
	ZVAL_LONG(&enum_case_Unknown_value, 0);
	zend_enum_add_case_cstr(class_entry, "Unknown", &enum_case_Unknown_value);

	zval enum_case_Any_value;
	ZVAL_LONG(&enum_case_Any_value, 1);
	zend_enum_add_case_cstr(class_entry, "Any", &enum_case_Any_value);

	zval enum_case_D3_value;
	ZVAL_LONG(&enum_case_D3_value, 2);
	zend_enum_add_case_cstr(class_entry, "D3", &enum_case_D3_value);

	zval enum_case_D2_value;
	ZVAL_LONG(&enum_case_D2_value, 3);
	zend_enum_add_case_cstr(class_entry, "D2", &enum_case_D2_value);

	zval enum_case_DAffine2_value;
	ZVAL_LONG(&enum_case_DAffine2_value, 4);
	zend_enum_add_case_cstr(class_entry, "DAffine2", &enum_case_DAffine2_value);

	zval enum_case_DTranslate2_value;
	ZVAL_LONG(&enum_case_DTranslate2_value, 5);
	zend_enum_add_case_cstr(class_entry, "DTranslate2", &enum_case_DTranslate2_value);

	zval enum_case_Identity_value;
	ZVAL_LONG(&enum_case_Identity_value, 6);
	zend_enum_add_case_cstr(class_entry, "Identity", &enum_case_Identity_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskTransformNode(zend_class_entry *class_entry_Gtk4_GskRenderNode)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskTransformNode", class_Gtk4_GskTransformNode_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GskRenderNode, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}
