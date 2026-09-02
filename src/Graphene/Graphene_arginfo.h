/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: d3675bc09fbd4ae8f027f6fab88c49608760cf49 */

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

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GraphenePoint_interpolate, 0, 2, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GraphenePoint_near, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, b, Gtk4\\GraphenePoint, 0)
	ZEND_ARG_TYPE_INFO(0, epsilon, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GraphenePoint_zero arginfo_class_Gtk4_GraphenePoint_alloc

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GrapheneRect___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

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

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GrapheneRect_get_area, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GrapheneRect_get_bottom_left arginfo_class_Gtk4_GraphenePoint_alloc

#define arginfo_class_Gtk4_GrapheneRect_get_bottom_right arginfo_class_Gtk4_GraphenePoint_alloc

#define arginfo_class_Gtk4_GrapheneRect_get_center arginfo_class_Gtk4_GraphenePoint_alloc

#define arginfo_class_Gtk4_GrapheneRect_get_height arginfo_class_Gtk4_GrapheneRect_get_area

#define arginfo_class_Gtk4_GrapheneRect_get_top_left arginfo_class_Gtk4_GraphenePoint_alloc

#define arginfo_class_Gtk4_GrapheneRect_get_top_right arginfo_class_Gtk4_GraphenePoint_alloc

#define arginfo_class_Gtk4_GrapheneRect_get_width arginfo_class_Gtk4_GrapheneRect_get_area

#define arginfo_class_Gtk4_GrapheneRect_get_x arginfo_class_Gtk4_GrapheneRect_get_area

#define arginfo_class_Gtk4_GrapheneRect_get_y arginfo_class_Gtk4_GrapheneRect_get_area

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

ZEND_METHOD(Gtk4_GraphenePoint, __construct);
ZEND_METHOD(Gtk4_GraphenePoint, alloc);
ZEND_METHOD(Gtk4_GraphenePoint, equal);
ZEND_METHOD(Gtk4_GraphenePoint, init);
ZEND_METHOD(Gtk4_GraphenePoint, init_from_point);
ZEND_METHOD(Gtk4_GraphenePoint, interpolate);
ZEND_METHOD(Gtk4_GraphenePoint, near);
ZEND_METHOD(Gtk4_GraphenePoint, zero);
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

static const zend_function_entry class_Gtk4_GraphenePoint_methods[] = {
	ZEND_ME(Gtk4_GraphenePoint, __construct, arginfo_class_Gtk4_GraphenePoint___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint, alloc, arginfo_class_Gtk4_GraphenePoint_alloc, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GraphenePoint, equal, arginfo_class_Gtk4_GraphenePoint_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint, init, arginfo_class_Gtk4_GraphenePoint_init, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint, init_from_point, arginfo_class_Gtk4_GraphenePoint_init_from_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint, interpolate, arginfo_class_Gtk4_GraphenePoint_interpolate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint, near, arginfo_class_Gtk4_GraphenePoint_near, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GraphenePoint, zero, arginfo_class_Gtk4_GraphenePoint_zero, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
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

static zend_class_entry *register_class_Gtk4_GraphenePoint(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GraphenePoint", class_Gtk4_GraphenePoint_methods);
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
