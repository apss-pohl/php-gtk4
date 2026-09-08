/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 53d6b7c5cc899617aa38324072894532042bbd53 */

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_alloc, 0, 0, Gtk4\\GrapheneMatrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_determinant, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneMatrix, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneMatrix_equal_fast arginfo_class_Gtk4_GrapheneMatrix_equal

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_get_row, 0, 1, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_TYPE_INFO(0, index, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_get_value, 0, 2, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, row, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, col, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneMatrix_get_x_scale arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneMatrix_get_x_translation arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneMatrix_get_y_scale arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneMatrix_get_y_translation arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneMatrix_get_z_scale arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneMatrix_get_z_translation arginfo_class_Gtk4_GrapheneMatrix_determinant

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_init_from_2d, 0, 6, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_TYPE_INFO(0, xx, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, yx, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, xy, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, yy, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, x_0, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y_0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_init_from_matrix, 0, 1, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_OBJ_INFO(0, src, Gtk4\\GrapheneMatrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_init_from_vec4, 0, 4, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_OBJ_INFO(0, v0, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_OBJ_INFO(0, v1, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_OBJ_INFO(0, v2, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_OBJ_INFO(0, v3, Gtk4\\GrapheneVec4, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_init_frustum, 0, 6, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_TYPE_INFO(0, left, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, right, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, bottom, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, top, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, z_near, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, z_far, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneMatrix_init_identity arginfo_class_Gtk4_GrapheneMatrix_alloc

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_init_look_at, 0, 3, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_OBJ_INFO(0, eye, Gtk4\\GrapheneVec3, 0)
	ZEND_ARG_OBJ_INFO(0, center, Gtk4\\GrapheneVec3, 0)
	ZEND_ARG_OBJ_INFO(0, up, Gtk4\\GrapheneVec3, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_init_ortho, 0, 6, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_TYPE_INFO(0, left, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, right, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, top, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, bottom, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, z_near, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, z_far, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_init_perspective, 0, 4, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_TYPE_INFO(0, fovy, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, aspect, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, z_near, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, z_far, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_init_rotate, 0, 2, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_TYPE_INFO(0, angle, IS_DOUBLE, 0)
	ZEND_ARG_OBJ_INFO(0, axis, Gtk4\\GrapheneVec3, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_init_scale, 0, 3, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_TYPE_INFO(0, x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, z, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_init_skew, 0, 2, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_TYPE_INFO(0, x_skew, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y_skew, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_init_translate, 0, 1, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_OBJ_INFO(0, p, Gtk4\\GraphenePoint3D, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_interpolate, 0, 2, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_inverse, 0, 0, Gtk4\\GrapheneMatrix, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_is_2d, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneMatrix_is_backface_visible arginfo_class_Gtk4_GrapheneMatrix_is_2d

#define arginfo_class_Gtk4_GrapheneMatrix_is_identity arginfo_class_Gtk4_GrapheneMatrix_is_2d

#define arginfo_class_Gtk4_GrapheneMatrix_is_singular arginfo_class_Gtk4_GrapheneMatrix_is_2d

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_multiply, 0, 1, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneMatrix, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_near, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_TYPE_INFO(0, epsilon, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneMatrix_normalize arginfo_class_Gtk4_GrapheneMatrix_alloc

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_perspective, 0, 1, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_TYPE_INFO(0, depth, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_print, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_project_point, 0, 1, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_OBJ_INFO(0, p, Gtk4\\GraphenePoint, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_project_rect_bounds, 0, 1, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_OBJ_INFO(0, r, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_rotate, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, angle, IS_DOUBLE, 0)
	ZEND_ARG_OBJ_INFO(0, axis, Gtk4\\GrapheneVec3, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_rotate_x, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, angle, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneMatrix_rotate_y arginfo_class_Gtk4_GrapheneMatrix_rotate_x

#define arginfo_class_Gtk4_GrapheneMatrix_rotate_z arginfo_class_Gtk4_GrapheneMatrix_rotate_x

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_scale, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, factor_x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, factor_y, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, factor_z, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_skew_xy, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneMatrix_skew_xz arginfo_class_Gtk4_GrapheneMatrix_skew_xy

#define arginfo_class_Gtk4_GrapheneMatrix_skew_yz arginfo_class_Gtk4_GrapheneMatrix_skew_xy

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_to_2d, 0, 0, IS_ARRAY, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneMatrix_transform_bounds arginfo_class_Gtk4_GrapheneMatrix_project_rect_bounds

#define arginfo_class_Gtk4_GrapheneMatrix_transform_point arginfo_class_Gtk4_GrapheneMatrix_project_point

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_transform_point3d, 0, 1, Gtk4\\GraphenePoint3D, 0)
	ZEND_ARG_OBJ_INFO(0, p, Gtk4\\GraphenePoint3D, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_transform_vec3, 0, 1, Gtk4\\GrapheneVec3, 0)
	ZEND_ARG_OBJ_INFO(0, v, Gtk4\\GrapheneVec3, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_transform_vec4, 0, 1, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_OBJ_INFO(0, v, Gtk4\\GrapheneVec4, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_translate, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, pos, Gtk4\\GraphenePoint3D, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneMatrix_transpose arginfo_class_Gtk4_GrapheneMatrix_alloc

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_unproject_point3d, 0, 2, Gtk4\\GraphenePoint3D, 0)
	ZEND_ARG_OBJ_INFO(0, modelview, Gtk4\\GrapheneMatrix, 0)
	ZEND_ARG_OBJ_INFO(0, point, Gtk4\\GraphenePoint3D, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_untransform_bounds, 0, 2, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_OBJ_INFO(0, r, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_OBJ_INFO(0, bounds, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneMatrix_untransform_point, 0, 2, Gtk4\\GraphenePoint, 1)
	ZEND_ARG_OBJ_INFO(0, p, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_OBJ_INFO(0, bounds, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GraphenePoint___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, x, IS_DOUBLE, 0, "0.0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, y, IS_DOUBLE, 0, "0.0")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint_alloc, 0, 0, Gtk4\\GraphenePoint, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GraphenePoint_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GraphenePoint, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint_init, 0, 2, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_TYPE_INFO(0, x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint_init_from_point, 0, 1, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_OBJ_INFO(0, src, Gtk4\\GraphenePoint, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint_init_from_vec2, 0, 1, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_OBJ_INFO(0, src, Gtk4\\GrapheneVec2, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint_interpolate, 0, 2, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GraphenePoint_near, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_TYPE_INFO(0, epsilon, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint_to_vec2, 0, 0, Gtk4\\GrapheneVec2, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GraphenePoint_zero arginfo_class_Gtk4_GraphenePoint_alloc

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GraphenePoint3D___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, x, IS_DOUBLE, 0, "0.0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, y, IS_DOUBLE, 0, "0.0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, z, IS_DOUBLE, 0, "0.0")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint3D_alloc, 0, 0, Gtk4\\GraphenePoint3D, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint3D_cross, 0, 1, Gtk4\\GraphenePoint3D, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GraphenePoint3D, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GraphenePoint3D_dot, 0, 1, IS_DOUBLE, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GraphenePoint3D, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GraphenePoint3D_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GraphenePoint3D, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint3D_init, 0, 3, Gtk4\\GraphenePoint3D, 0)
	ZEND_ARG_TYPE_INFO(0, x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, z, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint3D_init_from_point, 0, 1, Gtk4\\GraphenePoint3D, 0)
	ZEND_ARG_OBJ_INFO(0, src, Gtk4\\GraphenePoint3D, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint3D_init_from_vec3, 0, 1, Gtk4\\GraphenePoint3D, 0)
	ZEND_ARG_OBJ_INFO(0, v, Gtk4\\GrapheneVec3, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint3D_interpolate, 0, 2, Gtk4\\GraphenePoint3D, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GraphenePoint3D, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GraphenePoint3D_length arginfo_class_Gtk4_GrapheneMatrix_determinant

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GraphenePoint3D_near, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GraphenePoint3D, 0)
	ZEND_ARG_TYPE_INFO(0, epsilon, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GraphenePoint3D_normalize arginfo_class_Gtk4_GraphenePoint3D_alloc

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint3D_normalize_viewport, 0, 3, Gtk4\\GraphenePoint3D, 0)
	ZEND_ARG_OBJ_INFO(0, viewport, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_TYPE_INFO(0, z_near, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, z_far, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint3D_scale, 0, 1, Gtk4\\GraphenePoint3D, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint3D_to_vec3, 0, 0, Gtk4\\GrapheneVec3, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GraphenePoint3D_zero arginfo_class_Gtk4_GraphenePoint3D_alloc

#define arginfo_class_Gtk4_GrapheneRect___construct arginfo_class_Gtk4_GrapheneMatrix___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneRect_contains_point, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, p, Gtk4\\GraphenePoint, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneRect_contains_rect, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneRect_equal arginfo_class_Gtk4_GrapheneRect_contains_rect

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneRect_expand, 0, 1, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_OBJ_INFO(0, p, Gtk4\\GraphenePoint, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneRect_get_area arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneRect_get_bottom_left arginfo_class_Gtk4_GraphenePoint_alloc

#define arginfo_class_Gtk4_GrapheneRect_get_bottom_right arginfo_class_Gtk4_GraphenePoint_alloc

#define arginfo_class_Gtk4_GrapheneRect_get_center arginfo_class_Gtk4_GraphenePoint_alloc

#define arginfo_class_Gtk4_GrapheneRect_get_height arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneRect_get_top_left arginfo_class_Gtk4_GraphenePoint_alloc

#define arginfo_class_Gtk4_GrapheneRect_get_top_right arginfo_class_Gtk4_GraphenePoint_alloc

#define arginfo_class_Gtk4_GrapheneRect_get_width arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneRect_get_x arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneRect_get_y arginfo_class_Gtk4_GrapheneMatrix_determinant

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneRect_init, 0, 4, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_TYPE_INFO(0, x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneRect_init_from_rect, 0, 1, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_OBJ_INFO(0, src, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneRect_interpolate, 0, 2, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneRect_intersection, 0, 1, Gtk4\\GrapheneRect, 1)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneRect_round_extents, 0, 0, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneRect_scale, 0, 2, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_TYPE_INFO(0, s_h, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, s_v, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneRect_union, 0, 1, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneRect_alloc arginfo_class_Gtk4_GrapheneRect_round_extents

#define arginfo_class_Gtk4_GrapheneRect_zero arginfo_class_Gtk4_GrapheneRect_round_extents

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneRect_inset, 0, 2, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_TYPE_INFO(0, d_x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, d_y, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneRect_normalize arginfo_class_Gtk4_GrapheneRect_round_extents

#define arginfo_class_Gtk4_GrapheneRect_offset arginfo_class_Gtk4_GrapheneRect_inset

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GrapheneSize___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, width, IS_DOUBLE, 0, "0.0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, height, IS_DOUBLE, 0, "0.0")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneSize_alloc, 0, 0, Gtk4\\GrapheneSize, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneSize_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneSize, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneSize_init, 0, 2, Gtk4\\GrapheneSize, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneSize_init_from_size, 0, 1, Gtk4\\GrapheneSize, 0)
	ZEND_ARG_OBJ_INFO(0, src, Gtk4\\GrapheneSize, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneSize_interpolate, 0, 2, Gtk4\\GrapheneSize, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneSize, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneSize_scale, 0, 1, Gtk4\\GrapheneSize, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneSize_zero arginfo_class_Gtk4_GrapheneSize_alloc

#define arginfo_class_Gtk4_GrapheneVec2___construct arginfo_class_Gtk4_GrapheneMatrix___construct

#define arginfo_class_Gtk4_GrapheneVec2_alloc arginfo_class_Gtk4_GraphenePoint_to_vec2

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec2_add, 0, 1, Gtk4\\GrapheneVec2, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneVec2, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec2_divide arginfo_class_Gtk4_GrapheneVec2_add

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneVec2_dot, 0, 1, IS_DOUBLE, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneVec2, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneVec2_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, v2, Gtk4\\GrapheneVec2, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec2_get_x arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneVec2_get_y arginfo_class_Gtk4_GrapheneMatrix_determinant

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec2_init, 0, 2, Gtk4\\GrapheneVec2, 0)
	ZEND_ARG_TYPE_INFO(0, x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec2_init_from_vec2, 0, 1, Gtk4\\GrapheneVec2, 0)
	ZEND_ARG_OBJ_INFO(0, src, Gtk4\\GrapheneVec2, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec2_interpolate, 0, 2, Gtk4\\GrapheneVec2, 0)
	ZEND_ARG_OBJ_INFO(0, v2, Gtk4\\GrapheneVec2, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec2_length arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneVec2_max arginfo_class_Gtk4_GrapheneVec2_add

#define arginfo_class_Gtk4_GrapheneVec2_min arginfo_class_Gtk4_GrapheneVec2_add

#define arginfo_class_Gtk4_GrapheneVec2_multiply arginfo_class_Gtk4_GrapheneVec2_add

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneVec2_near, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, v2, Gtk4\\GrapheneVec2, 0)
	ZEND_ARG_TYPE_INFO(0, epsilon, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec2_negate arginfo_class_Gtk4_GraphenePoint_to_vec2

#define arginfo_class_Gtk4_GrapheneVec2_normalize arginfo_class_Gtk4_GraphenePoint_to_vec2

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec2_scale, 0, 1, Gtk4\\GrapheneVec2, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec2_subtract arginfo_class_Gtk4_GrapheneVec2_add

#define arginfo_class_Gtk4_GrapheneVec2_one arginfo_class_Gtk4_GraphenePoint_to_vec2

#define arginfo_class_Gtk4_GrapheneVec2_x_axis arginfo_class_Gtk4_GraphenePoint_to_vec2

#define arginfo_class_Gtk4_GrapheneVec2_y_axis arginfo_class_Gtk4_GraphenePoint_to_vec2

#define arginfo_class_Gtk4_GrapheneVec2_zero arginfo_class_Gtk4_GraphenePoint_to_vec2

#define arginfo_class_Gtk4_GrapheneVec3___construct arginfo_class_Gtk4_GrapheneMatrix___construct

#define arginfo_class_Gtk4_GrapheneVec3_alloc arginfo_class_Gtk4_GraphenePoint3D_to_vec3

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec3_add, 0, 1, Gtk4\\GrapheneVec3, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneVec3, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec3_cross arginfo_class_Gtk4_GrapheneVec3_add

#define arginfo_class_Gtk4_GrapheneVec3_divide arginfo_class_Gtk4_GrapheneVec3_add

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneVec3_dot, 0, 1, IS_DOUBLE, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneVec3, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneVec3_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, v2, Gtk4\\GrapheneVec3, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec3_get_x arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneVec3_get_xy arginfo_class_Gtk4_GraphenePoint_to_vec2

#define arginfo_class_Gtk4_GrapheneVec3_get_xy0 arginfo_class_Gtk4_GraphenePoint3D_to_vec3

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec3_get_xyz0, 0, 0, Gtk4\\GrapheneVec4, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec3_get_xyz1 arginfo_class_Gtk4_GrapheneVec3_get_xyz0

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec3_get_xyzw, 0, 1, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_TYPE_INFO(0, w, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec3_get_y arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneVec3_get_z arginfo_class_Gtk4_GrapheneMatrix_determinant

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec3_init, 0, 3, Gtk4\\GrapheneVec3, 0)
	ZEND_ARG_TYPE_INFO(0, x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, z, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec3_init_from_vec3, 0, 1, Gtk4\\GrapheneVec3, 0)
	ZEND_ARG_OBJ_INFO(0, src, Gtk4\\GrapheneVec3, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec3_interpolate, 0, 2, Gtk4\\GrapheneVec3, 0)
	ZEND_ARG_OBJ_INFO(0, v2, Gtk4\\GrapheneVec3, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec3_length arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneVec3_max arginfo_class_Gtk4_GrapheneVec3_add

#define arginfo_class_Gtk4_GrapheneVec3_min arginfo_class_Gtk4_GrapheneVec3_add

#define arginfo_class_Gtk4_GrapheneVec3_multiply arginfo_class_Gtk4_GrapheneVec3_add

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneVec3_near, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, v2, Gtk4\\GrapheneVec3, 0)
	ZEND_ARG_TYPE_INFO(0, epsilon, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec3_negate arginfo_class_Gtk4_GraphenePoint3D_to_vec3

#define arginfo_class_Gtk4_GrapheneVec3_normalize arginfo_class_Gtk4_GraphenePoint3D_to_vec3

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec3_scale, 0, 1, Gtk4\\GrapheneVec3, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec3_subtract arginfo_class_Gtk4_GrapheneVec3_add

#define arginfo_class_Gtk4_GrapheneVec3_one arginfo_class_Gtk4_GraphenePoint3D_to_vec3

#define arginfo_class_Gtk4_GrapheneVec3_x_axis arginfo_class_Gtk4_GraphenePoint3D_to_vec3

#define arginfo_class_Gtk4_GrapheneVec3_y_axis arginfo_class_Gtk4_GraphenePoint3D_to_vec3

#define arginfo_class_Gtk4_GrapheneVec3_z_axis arginfo_class_Gtk4_GraphenePoint3D_to_vec3

#define arginfo_class_Gtk4_GrapheneVec3_zero arginfo_class_Gtk4_GraphenePoint3D_to_vec3

#define arginfo_class_Gtk4_GrapheneVec4___construct arginfo_class_Gtk4_GrapheneMatrix___construct

#define arginfo_class_Gtk4_GrapheneVec4_alloc arginfo_class_Gtk4_GrapheneVec3_get_xyz0

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec4_add, 0, 1, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneVec4, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec4_divide arginfo_class_Gtk4_GrapheneVec4_add

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneVec4_dot, 0, 1, IS_DOUBLE, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GrapheneVec4, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneVec4_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, v2, Gtk4\\GrapheneVec4, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec4_get_w arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneVec4_get_x arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneVec4_get_xy arginfo_class_Gtk4_GraphenePoint_to_vec2

#define arginfo_class_Gtk4_GrapheneVec4_get_xyz arginfo_class_Gtk4_GraphenePoint3D_to_vec3

#define arginfo_class_Gtk4_GrapheneVec4_get_y arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneVec4_get_z arginfo_class_Gtk4_GrapheneMatrix_determinant

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec4_init, 0, 4, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_TYPE_INFO(0, x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, z, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, w, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec4_init_from_vec2, 0, 3, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_OBJ_INFO(0, src, Gtk4\\GrapheneVec2, 0)
	ZEND_ARG_TYPE_INFO(0, z, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, w, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec4_init_from_vec3, 0, 2, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_OBJ_INFO(0, src, Gtk4\\GrapheneVec3, 0)
	ZEND_ARG_TYPE_INFO(0, w, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec4_init_from_vec4, 0, 1, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_OBJ_INFO(0, src, Gtk4\\GrapheneVec4, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec4_interpolate, 0, 2, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_OBJ_INFO(0, v2, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec4_length arginfo_class_Gtk4_GrapheneMatrix_determinant

#define arginfo_class_Gtk4_GrapheneVec4_max arginfo_class_Gtk4_GrapheneVec4_add

#define arginfo_class_Gtk4_GrapheneVec4_min arginfo_class_Gtk4_GrapheneVec4_add

#define arginfo_class_Gtk4_GrapheneVec4_multiply arginfo_class_Gtk4_GrapheneVec4_add

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneVec4_near, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, v2, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_TYPE_INFO(0, epsilon, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec4_negate arginfo_class_Gtk4_GrapheneVec3_get_xyz0

#define arginfo_class_Gtk4_GrapheneVec4_normalize arginfo_class_Gtk4_GrapheneVec3_get_xyz0

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GrapheneVec4_scale, 0, 1, Gtk4\\GrapheneVec4, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneVec4_subtract arginfo_class_Gtk4_GrapheneVec4_add

#define arginfo_class_Gtk4_GrapheneVec4_one arginfo_class_Gtk4_GrapheneVec3_get_xyz0

#define arginfo_class_Gtk4_GrapheneVec4_w_axis arginfo_class_Gtk4_GrapheneVec3_get_xyz0

#define arginfo_class_Gtk4_GrapheneVec4_x_axis arginfo_class_Gtk4_GrapheneVec3_get_xyz0

#define arginfo_class_Gtk4_GrapheneVec4_y_axis arginfo_class_Gtk4_GrapheneVec3_get_xyz0

#define arginfo_class_Gtk4_GrapheneVec4_z_axis arginfo_class_Gtk4_GrapheneVec3_get_xyz0

#define arginfo_class_Gtk4_GrapheneVec4_zero arginfo_class_Gtk4_GrapheneVec3_get_xyz0

ZEND_METHOD(Gtk4_GrapheneMatrix, __construct);
ZEND_METHOD(Gtk4_GrapheneMatrix, alloc);
ZEND_METHOD(Gtk4_GrapheneMatrix, determinant);
ZEND_METHOD(Gtk4_GrapheneMatrix, equal);
ZEND_METHOD(Gtk4_GrapheneMatrix, equal_fast);
ZEND_METHOD(Gtk4_GrapheneMatrix, get_row);
ZEND_METHOD(Gtk4_GrapheneMatrix, get_value);
ZEND_METHOD(Gtk4_GrapheneMatrix, get_x_scale);
ZEND_METHOD(Gtk4_GrapheneMatrix, get_x_translation);
ZEND_METHOD(Gtk4_GrapheneMatrix, get_y_scale);
ZEND_METHOD(Gtk4_GrapheneMatrix, get_y_translation);
ZEND_METHOD(Gtk4_GrapheneMatrix, get_z_scale);
ZEND_METHOD(Gtk4_GrapheneMatrix, get_z_translation);
ZEND_METHOD(Gtk4_GrapheneMatrix, init_from_2d);
ZEND_METHOD(Gtk4_GrapheneMatrix, init_from_matrix);
ZEND_METHOD(Gtk4_GrapheneMatrix, init_from_vec4);
ZEND_METHOD(Gtk4_GrapheneMatrix, init_frustum);
ZEND_METHOD(Gtk4_GrapheneMatrix, init_identity);
ZEND_METHOD(Gtk4_GrapheneMatrix, init_look_at);
ZEND_METHOD(Gtk4_GrapheneMatrix, init_ortho);
ZEND_METHOD(Gtk4_GrapheneMatrix, init_perspective);
ZEND_METHOD(Gtk4_GrapheneMatrix, init_rotate);
ZEND_METHOD(Gtk4_GrapheneMatrix, init_scale);
ZEND_METHOD(Gtk4_GrapheneMatrix, init_skew);
ZEND_METHOD(Gtk4_GrapheneMatrix, init_translate);
ZEND_METHOD(Gtk4_GrapheneMatrix, interpolate);
ZEND_METHOD(Gtk4_GrapheneMatrix, inverse);
ZEND_METHOD(Gtk4_GrapheneMatrix, is_2d);
ZEND_METHOD(Gtk4_GrapheneMatrix, is_backface_visible);
ZEND_METHOD(Gtk4_GrapheneMatrix, is_identity);
ZEND_METHOD(Gtk4_GrapheneMatrix, is_singular);
ZEND_METHOD(Gtk4_GrapheneMatrix, multiply);
ZEND_METHOD(Gtk4_GrapheneMatrix, near);
ZEND_METHOD(Gtk4_GrapheneMatrix, normalize);
ZEND_METHOD(Gtk4_GrapheneMatrix, perspective);
ZEND_METHOD(Gtk4_GrapheneMatrix, print);
ZEND_METHOD(Gtk4_GrapheneMatrix, project_point);
ZEND_METHOD(Gtk4_GrapheneMatrix, project_rect_bounds);
ZEND_METHOD(Gtk4_GrapheneMatrix, rotate);
ZEND_METHOD(Gtk4_GrapheneMatrix, rotate_x);
ZEND_METHOD(Gtk4_GrapheneMatrix, rotate_y);
ZEND_METHOD(Gtk4_GrapheneMatrix, rotate_z);
ZEND_METHOD(Gtk4_GrapheneMatrix, scale);
ZEND_METHOD(Gtk4_GrapheneMatrix, skew_xy);
ZEND_METHOD(Gtk4_GrapheneMatrix, skew_xz);
ZEND_METHOD(Gtk4_GrapheneMatrix, skew_yz);
ZEND_METHOD(Gtk4_GrapheneMatrix, to_2d);
ZEND_METHOD(Gtk4_GrapheneMatrix, transform_bounds);
ZEND_METHOD(Gtk4_GrapheneMatrix, transform_point);
ZEND_METHOD(Gtk4_GrapheneMatrix, transform_point3d);
ZEND_METHOD(Gtk4_GrapheneMatrix, transform_vec3);
ZEND_METHOD(Gtk4_GrapheneMatrix, transform_vec4);
ZEND_METHOD(Gtk4_GrapheneMatrix, translate);
ZEND_METHOD(Gtk4_GrapheneMatrix, transpose);
ZEND_METHOD(Gtk4_GrapheneMatrix, unproject_point3d);
ZEND_METHOD(Gtk4_GrapheneMatrix, untransform_bounds);
ZEND_METHOD(Gtk4_GrapheneMatrix, untransform_point);
ZEND_METHOD(Gtk4_GraphenePoint, __construct);
ZEND_METHOD(Gtk4_GraphenePoint, alloc);
ZEND_METHOD(Gtk4_GraphenePoint, equal);
ZEND_METHOD(Gtk4_GraphenePoint, init);
ZEND_METHOD(Gtk4_GraphenePoint, init_from_point);
ZEND_METHOD(Gtk4_GraphenePoint, init_from_vec2);
ZEND_METHOD(Gtk4_GraphenePoint, interpolate);
ZEND_METHOD(Gtk4_GraphenePoint, near);
ZEND_METHOD(Gtk4_GraphenePoint, to_vec2);
ZEND_METHOD(Gtk4_GraphenePoint, zero);
ZEND_METHOD(Gtk4_GraphenePoint3D, __construct);
ZEND_METHOD(Gtk4_GraphenePoint3D, alloc);
ZEND_METHOD(Gtk4_GraphenePoint3D, cross);
ZEND_METHOD(Gtk4_GraphenePoint3D, dot);
ZEND_METHOD(Gtk4_GraphenePoint3D, equal);
ZEND_METHOD(Gtk4_GraphenePoint3D, init);
ZEND_METHOD(Gtk4_GraphenePoint3D, init_from_point);
ZEND_METHOD(Gtk4_GraphenePoint3D, init_from_vec3);
ZEND_METHOD(Gtk4_GraphenePoint3D, interpolate);
ZEND_METHOD(Gtk4_GraphenePoint3D, length);
ZEND_METHOD(Gtk4_GraphenePoint3D, near);
ZEND_METHOD(Gtk4_GraphenePoint3D, normalize);
ZEND_METHOD(Gtk4_GraphenePoint3D, normalize_viewport);
ZEND_METHOD(Gtk4_GraphenePoint3D, scale);
ZEND_METHOD(Gtk4_GraphenePoint3D, to_vec3);
ZEND_METHOD(Gtk4_GraphenePoint3D, zero);
ZEND_METHOD(Gtk4_GrapheneRect, __construct);
ZEND_METHOD(Gtk4_GrapheneRect, contains_point);
ZEND_METHOD(Gtk4_GrapheneRect, contains_rect);
ZEND_METHOD(Gtk4_GrapheneRect, equal);
ZEND_METHOD(Gtk4_GrapheneRect, expand);
ZEND_METHOD(Gtk4_GrapheneRect, get_area);
ZEND_METHOD(Gtk4_GrapheneRect, get_bottom_left);
ZEND_METHOD(Gtk4_GrapheneRect, get_bottom_right);
ZEND_METHOD(Gtk4_GrapheneRect, get_center);
ZEND_METHOD(Gtk4_GrapheneRect, get_height);
ZEND_METHOD(Gtk4_GrapheneRect, get_top_left);
ZEND_METHOD(Gtk4_GrapheneRect, get_top_right);
ZEND_METHOD(Gtk4_GrapheneRect, get_width);
ZEND_METHOD(Gtk4_GrapheneRect, get_x);
ZEND_METHOD(Gtk4_GrapheneRect, get_y);
ZEND_METHOD(Gtk4_GrapheneRect, init);
ZEND_METHOD(Gtk4_GrapheneRect, init_from_rect);
ZEND_METHOD(Gtk4_GrapheneRect, interpolate);
ZEND_METHOD(Gtk4_GrapheneRect, intersection);
ZEND_METHOD(Gtk4_GrapheneRect, round_extents);
ZEND_METHOD(Gtk4_GrapheneRect, scale);
ZEND_METHOD(Gtk4_GrapheneRect, union);
ZEND_METHOD(Gtk4_GrapheneRect, alloc);
ZEND_METHOD(Gtk4_GrapheneRect, zero);
ZEND_METHOD(Gtk4_GrapheneRect, inset);
ZEND_METHOD(Gtk4_GrapheneRect, normalize);
ZEND_METHOD(Gtk4_GrapheneRect, offset);
ZEND_METHOD(Gtk4_GrapheneSize, __construct);
ZEND_METHOD(Gtk4_GrapheneSize, alloc);
ZEND_METHOD(Gtk4_GrapheneSize, equal);
ZEND_METHOD(Gtk4_GrapheneSize, init);
ZEND_METHOD(Gtk4_GrapheneSize, init_from_size);
ZEND_METHOD(Gtk4_GrapheneSize, interpolate);
ZEND_METHOD(Gtk4_GrapheneSize, scale);
ZEND_METHOD(Gtk4_GrapheneSize, zero);
ZEND_METHOD(Gtk4_GrapheneVec2, __construct);
ZEND_METHOD(Gtk4_GrapheneVec2, alloc);
ZEND_METHOD(Gtk4_GrapheneVec2, add);
ZEND_METHOD(Gtk4_GrapheneVec2, divide);
ZEND_METHOD(Gtk4_GrapheneVec2, dot);
ZEND_METHOD(Gtk4_GrapheneVec2, equal);
ZEND_METHOD(Gtk4_GrapheneVec2, get_x);
ZEND_METHOD(Gtk4_GrapheneVec2, get_y);
ZEND_METHOD(Gtk4_GrapheneVec2, init);
ZEND_METHOD(Gtk4_GrapheneVec2, init_from_vec2);
ZEND_METHOD(Gtk4_GrapheneVec2, interpolate);
ZEND_METHOD(Gtk4_GrapheneVec2, length);
ZEND_METHOD(Gtk4_GrapheneVec2, max);
ZEND_METHOD(Gtk4_GrapheneVec2, min);
ZEND_METHOD(Gtk4_GrapheneVec2, multiply);
ZEND_METHOD(Gtk4_GrapheneVec2, near);
ZEND_METHOD(Gtk4_GrapheneVec2, negate);
ZEND_METHOD(Gtk4_GrapheneVec2, normalize);
ZEND_METHOD(Gtk4_GrapheneVec2, scale);
ZEND_METHOD(Gtk4_GrapheneVec2, subtract);
ZEND_METHOD(Gtk4_GrapheneVec2, one);
ZEND_METHOD(Gtk4_GrapheneVec2, x_axis);
ZEND_METHOD(Gtk4_GrapheneVec2, y_axis);
ZEND_METHOD(Gtk4_GrapheneVec2, zero);
ZEND_METHOD(Gtk4_GrapheneVec3, __construct);
ZEND_METHOD(Gtk4_GrapheneVec3, alloc);
ZEND_METHOD(Gtk4_GrapheneVec3, add);
ZEND_METHOD(Gtk4_GrapheneVec3, cross);
ZEND_METHOD(Gtk4_GrapheneVec3, divide);
ZEND_METHOD(Gtk4_GrapheneVec3, dot);
ZEND_METHOD(Gtk4_GrapheneVec3, equal);
ZEND_METHOD(Gtk4_GrapheneVec3, get_x);
ZEND_METHOD(Gtk4_GrapheneVec3, get_xy);
ZEND_METHOD(Gtk4_GrapheneVec3, get_xy0);
ZEND_METHOD(Gtk4_GrapheneVec3, get_xyz0);
ZEND_METHOD(Gtk4_GrapheneVec3, get_xyz1);
ZEND_METHOD(Gtk4_GrapheneVec3, get_xyzw);
ZEND_METHOD(Gtk4_GrapheneVec3, get_y);
ZEND_METHOD(Gtk4_GrapheneVec3, get_z);
ZEND_METHOD(Gtk4_GrapheneVec3, init);
ZEND_METHOD(Gtk4_GrapheneVec3, init_from_vec3);
ZEND_METHOD(Gtk4_GrapheneVec3, interpolate);
ZEND_METHOD(Gtk4_GrapheneVec3, length);
ZEND_METHOD(Gtk4_GrapheneVec3, max);
ZEND_METHOD(Gtk4_GrapheneVec3, min);
ZEND_METHOD(Gtk4_GrapheneVec3, multiply);
ZEND_METHOD(Gtk4_GrapheneVec3, near);
ZEND_METHOD(Gtk4_GrapheneVec3, negate);
ZEND_METHOD(Gtk4_GrapheneVec3, normalize);
ZEND_METHOD(Gtk4_GrapheneVec3, scale);
ZEND_METHOD(Gtk4_GrapheneVec3, subtract);
ZEND_METHOD(Gtk4_GrapheneVec3, one);
ZEND_METHOD(Gtk4_GrapheneVec3, x_axis);
ZEND_METHOD(Gtk4_GrapheneVec3, y_axis);
ZEND_METHOD(Gtk4_GrapheneVec3, z_axis);
ZEND_METHOD(Gtk4_GrapheneVec3, zero);
ZEND_METHOD(Gtk4_GrapheneVec4, __construct);
ZEND_METHOD(Gtk4_GrapheneVec4, alloc);
ZEND_METHOD(Gtk4_GrapheneVec4, add);
ZEND_METHOD(Gtk4_GrapheneVec4, divide);
ZEND_METHOD(Gtk4_GrapheneVec4, dot);
ZEND_METHOD(Gtk4_GrapheneVec4, equal);
ZEND_METHOD(Gtk4_GrapheneVec4, get_w);
ZEND_METHOD(Gtk4_GrapheneVec4, get_x);
ZEND_METHOD(Gtk4_GrapheneVec4, get_xy);
ZEND_METHOD(Gtk4_GrapheneVec4, get_xyz);
ZEND_METHOD(Gtk4_GrapheneVec4, get_y);
ZEND_METHOD(Gtk4_GrapheneVec4, get_z);
ZEND_METHOD(Gtk4_GrapheneVec4, init);
ZEND_METHOD(Gtk4_GrapheneVec4, init_from_vec2);
ZEND_METHOD(Gtk4_GrapheneVec4, init_from_vec3);
ZEND_METHOD(Gtk4_GrapheneVec4, init_from_vec4);
ZEND_METHOD(Gtk4_GrapheneVec4, interpolate);
ZEND_METHOD(Gtk4_GrapheneVec4, length);
ZEND_METHOD(Gtk4_GrapheneVec4, max);
ZEND_METHOD(Gtk4_GrapheneVec4, min);
ZEND_METHOD(Gtk4_GrapheneVec4, multiply);
ZEND_METHOD(Gtk4_GrapheneVec4, near);
ZEND_METHOD(Gtk4_GrapheneVec4, negate);
ZEND_METHOD(Gtk4_GrapheneVec4, normalize);
ZEND_METHOD(Gtk4_GrapheneVec4, scale);
ZEND_METHOD(Gtk4_GrapheneVec4, subtract);
ZEND_METHOD(Gtk4_GrapheneVec4, one);
ZEND_METHOD(Gtk4_GrapheneVec4, w_axis);
ZEND_METHOD(Gtk4_GrapheneVec4, x_axis);
ZEND_METHOD(Gtk4_GrapheneVec4, y_axis);
ZEND_METHOD(Gtk4_GrapheneVec4, z_axis);
ZEND_METHOD(Gtk4_GrapheneVec4, zero);

static const zend_function_entry class_Gtk4_GrapheneMatrix_methods[] = {
	ZEND_ME(Gtk4_GrapheneMatrix, __construct, arginfo_class_Gtk4_GrapheneMatrix___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GrapheneMatrix, alloc, arginfo_class_Gtk4_GrapheneMatrix_alloc, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneMatrix, determinant, arginfo_class_Gtk4_GrapheneMatrix_determinant, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, equal, arginfo_class_Gtk4_GrapheneMatrix_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, equal_fast, arginfo_class_Gtk4_GrapheneMatrix_equal_fast, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, get_row, arginfo_class_Gtk4_GrapheneMatrix_get_row, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, get_value, arginfo_class_Gtk4_GrapheneMatrix_get_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, get_x_scale, arginfo_class_Gtk4_GrapheneMatrix_get_x_scale, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, get_x_translation, arginfo_class_Gtk4_GrapheneMatrix_get_x_translation, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, get_y_scale, arginfo_class_Gtk4_GrapheneMatrix_get_y_scale, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, get_y_translation, arginfo_class_Gtk4_GrapheneMatrix_get_y_translation, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, get_z_scale, arginfo_class_Gtk4_GrapheneMatrix_get_z_scale, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, get_z_translation, arginfo_class_Gtk4_GrapheneMatrix_get_z_translation, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, init_from_2d, arginfo_class_Gtk4_GrapheneMatrix_init_from_2d, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, init_from_matrix, arginfo_class_Gtk4_GrapheneMatrix_init_from_matrix, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, init_from_vec4, arginfo_class_Gtk4_GrapheneMatrix_init_from_vec4, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, init_frustum, arginfo_class_Gtk4_GrapheneMatrix_init_frustum, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, init_identity, arginfo_class_Gtk4_GrapheneMatrix_init_identity, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, init_look_at, arginfo_class_Gtk4_GrapheneMatrix_init_look_at, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, init_ortho, arginfo_class_Gtk4_GrapheneMatrix_init_ortho, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, init_perspective, arginfo_class_Gtk4_GrapheneMatrix_init_perspective, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, init_rotate, arginfo_class_Gtk4_GrapheneMatrix_init_rotate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, init_scale, arginfo_class_Gtk4_GrapheneMatrix_init_scale, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, init_skew, arginfo_class_Gtk4_GrapheneMatrix_init_skew, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, init_translate, arginfo_class_Gtk4_GrapheneMatrix_init_translate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, interpolate, arginfo_class_Gtk4_GrapheneMatrix_interpolate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, inverse, arginfo_class_Gtk4_GrapheneMatrix_inverse, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, is_2d, arginfo_class_Gtk4_GrapheneMatrix_is_2d, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, is_backface_visible, arginfo_class_Gtk4_GrapheneMatrix_is_backface_visible, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, is_identity, arginfo_class_Gtk4_GrapheneMatrix_is_identity, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, is_singular, arginfo_class_Gtk4_GrapheneMatrix_is_singular, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, multiply, arginfo_class_Gtk4_GrapheneMatrix_multiply, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, near, arginfo_class_Gtk4_GrapheneMatrix_near, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, normalize, arginfo_class_Gtk4_GrapheneMatrix_normalize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, perspective, arginfo_class_Gtk4_GrapheneMatrix_perspective, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, print, arginfo_class_Gtk4_GrapheneMatrix_print, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, project_point, arginfo_class_Gtk4_GrapheneMatrix_project_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, project_rect_bounds, arginfo_class_Gtk4_GrapheneMatrix_project_rect_bounds, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, rotate, arginfo_class_Gtk4_GrapheneMatrix_rotate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, rotate_x, arginfo_class_Gtk4_GrapheneMatrix_rotate_x, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, rotate_y, arginfo_class_Gtk4_GrapheneMatrix_rotate_y, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, rotate_z, arginfo_class_Gtk4_GrapheneMatrix_rotate_z, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, scale, arginfo_class_Gtk4_GrapheneMatrix_scale, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, skew_xy, arginfo_class_Gtk4_GrapheneMatrix_skew_xy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, skew_xz, arginfo_class_Gtk4_GrapheneMatrix_skew_xz, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, skew_yz, arginfo_class_Gtk4_GrapheneMatrix_skew_yz, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, to_2d, arginfo_class_Gtk4_GrapheneMatrix_to_2d, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, transform_bounds, arginfo_class_Gtk4_GrapheneMatrix_transform_bounds, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, transform_point, arginfo_class_Gtk4_GrapheneMatrix_transform_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, transform_point3d, arginfo_class_Gtk4_GrapheneMatrix_transform_point3d, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, transform_vec3, arginfo_class_Gtk4_GrapheneMatrix_transform_vec3, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, transform_vec4, arginfo_class_Gtk4_GrapheneMatrix_transform_vec4, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, translate, arginfo_class_Gtk4_GrapheneMatrix_translate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, transpose, arginfo_class_Gtk4_GrapheneMatrix_transpose, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, unproject_point3d, arginfo_class_Gtk4_GrapheneMatrix_unproject_point3d, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, untransform_bounds, arginfo_class_Gtk4_GrapheneMatrix_untransform_bounds, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneMatrix, untransform_point, arginfo_class_Gtk4_GrapheneMatrix_untransform_point, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GraphenePoint_methods[] = {
	ZEND_ME(Gtk4_GraphenePoint, __construct, arginfo_class_Gtk4_GraphenePoint___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint, alloc, arginfo_class_Gtk4_GraphenePoint_alloc, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GraphenePoint, equal, arginfo_class_Gtk4_GraphenePoint_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint, init, arginfo_class_Gtk4_GraphenePoint_init, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint, init_from_point, arginfo_class_Gtk4_GraphenePoint_init_from_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint, init_from_vec2, arginfo_class_Gtk4_GraphenePoint_init_from_vec2, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint, interpolate, arginfo_class_Gtk4_GraphenePoint_interpolate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint, near, arginfo_class_Gtk4_GraphenePoint_near, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint, to_vec2, arginfo_class_Gtk4_GraphenePoint_to_vec2, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint, zero, arginfo_class_Gtk4_GraphenePoint_zero, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GraphenePoint3D_methods[] = {
	ZEND_ME(Gtk4_GraphenePoint3D, __construct, arginfo_class_Gtk4_GraphenePoint3D___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint3D, alloc, arginfo_class_Gtk4_GraphenePoint3D_alloc, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GraphenePoint3D, cross, arginfo_class_Gtk4_GraphenePoint3D_cross, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint3D, dot, arginfo_class_Gtk4_GraphenePoint3D_dot, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint3D, equal, arginfo_class_Gtk4_GraphenePoint3D_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint3D, init, arginfo_class_Gtk4_GraphenePoint3D_init, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint3D, init_from_point, arginfo_class_Gtk4_GraphenePoint3D_init_from_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint3D, init_from_vec3, arginfo_class_Gtk4_GraphenePoint3D_init_from_vec3, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint3D, interpolate, arginfo_class_Gtk4_GraphenePoint3D_interpolate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint3D, length, arginfo_class_Gtk4_GraphenePoint3D_length, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint3D, near, arginfo_class_Gtk4_GraphenePoint3D_near, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint3D, normalize, arginfo_class_Gtk4_GraphenePoint3D_normalize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint3D, normalize_viewport, arginfo_class_Gtk4_GraphenePoint3D_normalize_viewport, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint3D, scale, arginfo_class_Gtk4_GraphenePoint3D_scale, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint3D, to_vec3, arginfo_class_Gtk4_GraphenePoint3D_to_vec3, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint3D, zero, arginfo_class_Gtk4_GraphenePoint3D_zero, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GrapheneRect_methods[] = {
	ZEND_ME(Gtk4_GrapheneRect, __construct, arginfo_class_Gtk4_GrapheneRect___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GrapheneRect, contains_point, arginfo_class_Gtk4_GrapheneRect_contains_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, contains_rect, arginfo_class_Gtk4_GrapheneRect_contains_rect, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, equal, arginfo_class_Gtk4_GrapheneRect_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, expand, arginfo_class_Gtk4_GrapheneRect_expand, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, get_area, arginfo_class_Gtk4_GrapheneRect_get_area, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, get_bottom_left, arginfo_class_Gtk4_GrapheneRect_get_bottom_left, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, get_bottom_right, arginfo_class_Gtk4_GrapheneRect_get_bottom_right, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, get_center, arginfo_class_Gtk4_GrapheneRect_get_center, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, get_height, arginfo_class_Gtk4_GrapheneRect_get_height, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, get_top_left, arginfo_class_Gtk4_GrapheneRect_get_top_left, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, get_top_right, arginfo_class_Gtk4_GrapheneRect_get_top_right, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, get_width, arginfo_class_Gtk4_GrapheneRect_get_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, get_x, arginfo_class_Gtk4_GrapheneRect_get_x, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, get_y, arginfo_class_Gtk4_GrapheneRect_get_y, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, init, arginfo_class_Gtk4_GrapheneRect_init, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, init_from_rect, arginfo_class_Gtk4_GrapheneRect_init_from_rect, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, interpolate, arginfo_class_Gtk4_GrapheneRect_interpolate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, intersection, arginfo_class_Gtk4_GrapheneRect_intersection, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, round_extents, arginfo_class_Gtk4_GrapheneRect_round_extents, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, scale, arginfo_class_Gtk4_GrapheneRect_scale, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, union, arginfo_class_Gtk4_GrapheneRect_union, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, alloc, arginfo_class_Gtk4_GrapheneRect_alloc, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneRect, zero, arginfo_class_Gtk4_GrapheneRect_zero, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneRect, inset, arginfo_class_Gtk4_GrapheneRect_inset, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, normalize, arginfo_class_Gtk4_GrapheneRect_normalize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneRect, offset, arginfo_class_Gtk4_GrapheneRect_offset, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GrapheneSize_methods[] = {
	ZEND_ME(Gtk4_GrapheneSize, __construct, arginfo_class_Gtk4_GrapheneSize___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneSize, alloc, arginfo_class_Gtk4_GrapheneSize_alloc, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneSize, equal, arginfo_class_Gtk4_GrapheneSize_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneSize, init, arginfo_class_Gtk4_GrapheneSize_init, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneSize, init_from_size, arginfo_class_Gtk4_GrapheneSize_init_from_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneSize, interpolate, arginfo_class_Gtk4_GrapheneSize_interpolate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneSize, scale, arginfo_class_Gtk4_GrapheneSize_scale, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneSize, zero, arginfo_class_Gtk4_GrapheneSize_zero, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GrapheneVec2_methods[] = {
	ZEND_ME(Gtk4_GrapheneVec2, __construct, arginfo_class_Gtk4_GrapheneVec2___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GrapheneVec2, alloc, arginfo_class_Gtk4_GrapheneVec2_alloc, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec2, add, arginfo_class_Gtk4_GrapheneVec2_add, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, divide, arginfo_class_Gtk4_GrapheneVec2_divide, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, dot, arginfo_class_Gtk4_GrapheneVec2_dot, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, equal, arginfo_class_Gtk4_GrapheneVec2_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, get_x, arginfo_class_Gtk4_GrapheneVec2_get_x, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, get_y, arginfo_class_Gtk4_GrapheneVec2_get_y, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, init, arginfo_class_Gtk4_GrapheneVec2_init, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, init_from_vec2, arginfo_class_Gtk4_GrapheneVec2_init_from_vec2, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, interpolate, arginfo_class_Gtk4_GrapheneVec2_interpolate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, length, arginfo_class_Gtk4_GrapheneVec2_length, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, max, arginfo_class_Gtk4_GrapheneVec2_max, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, min, arginfo_class_Gtk4_GrapheneVec2_min, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, multiply, arginfo_class_Gtk4_GrapheneVec2_multiply, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, near, arginfo_class_Gtk4_GrapheneVec2_near, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, negate, arginfo_class_Gtk4_GrapheneVec2_negate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, normalize, arginfo_class_Gtk4_GrapheneVec2_normalize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, scale, arginfo_class_Gtk4_GrapheneVec2_scale, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, subtract, arginfo_class_Gtk4_GrapheneVec2_subtract, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec2, one, arginfo_class_Gtk4_GrapheneVec2_one, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec2, x_axis, arginfo_class_Gtk4_GrapheneVec2_x_axis, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec2, y_axis, arginfo_class_Gtk4_GrapheneVec2_y_axis, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec2, zero, arginfo_class_Gtk4_GrapheneVec2_zero, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GrapheneVec3_methods[] = {
	ZEND_ME(Gtk4_GrapheneVec3, __construct, arginfo_class_Gtk4_GrapheneVec3___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GrapheneVec3, alloc, arginfo_class_Gtk4_GrapheneVec3_alloc, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec3, add, arginfo_class_Gtk4_GrapheneVec3_add, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, cross, arginfo_class_Gtk4_GrapheneVec3_cross, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, divide, arginfo_class_Gtk4_GrapheneVec3_divide, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, dot, arginfo_class_Gtk4_GrapheneVec3_dot, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, equal, arginfo_class_Gtk4_GrapheneVec3_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, get_x, arginfo_class_Gtk4_GrapheneVec3_get_x, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, get_xy, arginfo_class_Gtk4_GrapheneVec3_get_xy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, get_xy0, arginfo_class_Gtk4_GrapheneVec3_get_xy0, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, get_xyz0, arginfo_class_Gtk4_GrapheneVec3_get_xyz0, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, get_xyz1, arginfo_class_Gtk4_GrapheneVec3_get_xyz1, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, get_xyzw, arginfo_class_Gtk4_GrapheneVec3_get_xyzw, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, get_y, arginfo_class_Gtk4_GrapheneVec3_get_y, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, get_z, arginfo_class_Gtk4_GrapheneVec3_get_z, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, init, arginfo_class_Gtk4_GrapheneVec3_init, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, init_from_vec3, arginfo_class_Gtk4_GrapheneVec3_init_from_vec3, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, interpolate, arginfo_class_Gtk4_GrapheneVec3_interpolate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, length, arginfo_class_Gtk4_GrapheneVec3_length, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, max, arginfo_class_Gtk4_GrapheneVec3_max, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, min, arginfo_class_Gtk4_GrapheneVec3_min, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, multiply, arginfo_class_Gtk4_GrapheneVec3_multiply, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, near, arginfo_class_Gtk4_GrapheneVec3_near, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, negate, arginfo_class_Gtk4_GrapheneVec3_negate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, normalize, arginfo_class_Gtk4_GrapheneVec3_normalize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, scale, arginfo_class_Gtk4_GrapheneVec3_scale, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, subtract, arginfo_class_Gtk4_GrapheneVec3_subtract, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec3, one, arginfo_class_Gtk4_GrapheneVec3_one, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec3, x_axis, arginfo_class_Gtk4_GrapheneVec3_x_axis, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec3, y_axis, arginfo_class_Gtk4_GrapheneVec3_y_axis, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec3, z_axis, arginfo_class_Gtk4_GrapheneVec3_z_axis, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec3, zero, arginfo_class_Gtk4_GrapheneVec3_zero, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GrapheneVec4_methods[] = {
	ZEND_ME(Gtk4_GrapheneVec4, __construct, arginfo_class_Gtk4_GrapheneVec4___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GrapheneVec4, alloc, arginfo_class_Gtk4_GrapheneVec4_alloc, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec4, add, arginfo_class_Gtk4_GrapheneVec4_add, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, divide, arginfo_class_Gtk4_GrapheneVec4_divide, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, dot, arginfo_class_Gtk4_GrapheneVec4_dot, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, equal, arginfo_class_Gtk4_GrapheneVec4_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, get_w, arginfo_class_Gtk4_GrapheneVec4_get_w, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, get_x, arginfo_class_Gtk4_GrapheneVec4_get_x, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, get_xy, arginfo_class_Gtk4_GrapheneVec4_get_xy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, get_xyz, arginfo_class_Gtk4_GrapheneVec4_get_xyz, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, get_y, arginfo_class_Gtk4_GrapheneVec4_get_y, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, get_z, arginfo_class_Gtk4_GrapheneVec4_get_z, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, init, arginfo_class_Gtk4_GrapheneVec4_init, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, init_from_vec2, arginfo_class_Gtk4_GrapheneVec4_init_from_vec2, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, init_from_vec3, arginfo_class_Gtk4_GrapheneVec4_init_from_vec3, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, init_from_vec4, arginfo_class_Gtk4_GrapheneVec4_init_from_vec4, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, interpolate, arginfo_class_Gtk4_GrapheneVec4_interpolate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, length, arginfo_class_Gtk4_GrapheneVec4_length, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, max, arginfo_class_Gtk4_GrapheneVec4_max, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, min, arginfo_class_Gtk4_GrapheneVec4_min, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, multiply, arginfo_class_Gtk4_GrapheneVec4_multiply, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, near, arginfo_class_Gtk4_GrapheneVec4_near, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, negate, arginfo_class_Gtk4_GrapheneVec4_negate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, normalize, arginfo_class_Gtk4_GrapheneVec4_normalize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, scale, arginfo_class_Gtk4_GrapheneVec4_scale, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, subtract, arginfo_class_Gtk4_GrapheneVec4_subtract, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GrapheneVec4, one, arginfo_class_Gtk4_GrapheneVec4_one, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec4, w_axis, arginfo_class_Gtk4_GrapheneVec4_w_axis, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec4, x_axis, arginfo_class_Gtk4_GrapheneVec4_x_axis, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec4, y_axis, arginfo_class_Gtk4_GrapheneVec4_y_axis, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec4, z_axis, arginfo_class_Gtk4_GrapheneVec4_z_axis, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GrapheneVec4, zero, arginfo_class_Gtk4_GrapheneVec4_zero, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static zend_class_entry *register_class_Gtk4_GrapheneMatrix(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GrapheneMatrix", class_Gtk4_GrapheneMatrix_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GraphenePoint(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GraphenePoint", class_Gtk4_GraphenePoint_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GraphenePoint3D(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GraphenePoint3D", class_Gtk4_GraphenePoint3D_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GrapheneRect(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GrapheneRect", class_Gtk4_GrapheneRect_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GrapheneSize(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GrapheneSize", class_Gtk4_GrapheneSize_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GrapheneVec2(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GrapheneVec2", class_Gtk4_GrapheneVec2_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GrapheneVec3(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GrapheneVec3", class_Gtk4_GrapheneVec3_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GrapheneVec4(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GrapheneVec4", class_Gtk4_GrapheneVec4_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}
