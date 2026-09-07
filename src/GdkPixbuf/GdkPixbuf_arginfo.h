/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 55c91a591725f9d42932a4c3c463b8d35cc91b17 */

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GdkPixbuf___construct, 0, 0, 5)
	ZEND_ARG_OBJ_INFO(0, colorspace, Gtk4\\GdkColorspace, 0)
	ZEND_ARG_TYPE_INFO(0, has_alpha, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, bits_per_sample, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_new_from_bytes, 0, 7, Gtk4\\GdkPixbuf, 0)
	ZEND_ARG_TYPE_INFO(0, data, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO(0, colorspace, Gtk4\\GdkColorspace, 0)
	ZEND_ARG_TYPE_INFO(0, has_alpha, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, bits_per_sample, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, rowstride, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_new_from_file, 0, 1, Gtk4\\GdkPixbuf, 0)
	ZEND_ARG_TYPE_INFO(0, filename, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_new_from_file_at_scale, 0, 4, Gtk4\\GdkPixbuf, 0)
	ZEND_ARG_TYPE_INFO(0, filename, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, preserve_aspect_ratio, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_new_from_file_at_size, 0, 3, Gtk4\\GdkPixbuf, 0)
	ZEND_ARG_TYPE_INFO(0, filename, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_new_from_resource, 0, 1, Gtk4\\GdkPixbuf, 0)
	ZEND_ARG_TYPE_INFO(0, resource_path, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_new_from_resource_at_scale, 0, 4, Gtk4\\GdkPixbuf, 0)
	ZEND_ARG_TYPE_INFO(0, resource_path, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, preserve_aspect_ratio, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_new_from_stream_finish, 0, 1, Gtk4\\GdkPixbuf, 0)
	ZEND_ARG_OBJ_INFO(0, async_result, Gtk4\\GAsyncResult, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_new_from_xpm_data, 0, 1, Gtk4\\GdkPixbuf, 0)
	ZEND_ARG_TYPE_INFO(0, data, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_calculate_rowstride, 0, 5, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, colorspace, Gtk4\\GdkColorspace, 0)
	ZEND_ARG_TYPE_INFO(0, has_alpha, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, bits_per_sample, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_get_file_info_async, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, filename, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO(0, cancellable, Gtk4\\GCancellable, 1)
	ZEND_ARG_TYPE_INFO(0, callback, IS_CALLABLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_get_formats, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_init_modules, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, path, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_save_to_stream_finish, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, async_result, Gtk4\\GAsyncResult, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_add_alpha, 0, 4, Gtk4\\GdkPixbuf, 0)
	ZEND_ARG_TYPE_INFO(0, substitute_color, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, r, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, g, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, b, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_apply_embedded_orientation, 0, 0, Gtk4\\GdkPixbuf, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_composite, 0, 11, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, dest, Gtk4\\GdkPixbuf, 0)
	ZEND_ARG_TYPE_INFO(0, dest_x, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, dest_y, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, dest_width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, dest_height, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, offset_x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, offset_y, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, scale_x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, scale_y, IS_DOUBLE, 0)
	ZEND_ARG_OBJ_INFO(0, interp_type, Gtk4\\GdkInterpType, 0)
	ZEND_ARG_TYPE_INFO(0, overall_alpha, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_composite_color_simple, 0, 7, Gtk4\\GdkPixbuf, 1)
	ZEND_ARG_TYPE_INFO(0, dest_width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, dest_height, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, interp_type, Gtk4\\GdkInterpType, 0)
	ZEND_ARG_TYPE_INFO(0, overall_alpha, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, check_size, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, color1, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, color2, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkPixbuf_copy arginfo_class_Gtk4_GdkPixbuf_apply_embedded_orientation

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_copy_area, 0, 7, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, src_x, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, src_y, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, dest_pixbuf, Gtk4\\GdkPixbuf, 0)
	ZEND_ARG_TYPE_INFO(0, dest_x, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, dest_y, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_copy_options, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, dest_pixbuf, Gtk4\\GdkPixbuf, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_fill, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, pixel, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_flip, 0, 1, Gtk4\\GdkPixbuf, 1)
	ZEND_ARG_TYPE_INFO(0, horizontal, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_get_bits_per_sample, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkPixbuf_get_byte_length arginfo_class_Gtk4_GdkPixbuf_get_bits_per_sample

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_get_colorspace, 0, 0, Gtk4\\GdkColorspace, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_get_has_alpha, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkPixbuf_get_height arginfo_class_Gtk4_GdkPixbuf_get_bits_per_sample

#define arginfo_class_Gtk4_GdkPixbuf_get_n_channels arginfo_class_Gtk4_GdkPixbuf_get_bits_per_sample

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_get_option, 0, 1, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkPixbuf_get_rowstride arginfo_class_Gtk4_GdkPixbuf_get_bits_per_sample

#define arginfo_class_Gtk4_GdkPixbuf_get_width arginfo_class_Gtk4_GdkPixbuf_get_bits_per_sample

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_new_subpixbuf, 0, 4, Gtk4\\GdkPixbuf, 0)
	ZEND_ARG_TYPE_INFO(0, src_x, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, src_y, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_read_pixel_bytes, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_remove_option, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_rotate_simple, 0, 1, Gtk4\\GdkPixbuf, 1)
	ZEND_ARG_OBJ_INFO(0, angle, Gtk4\\GdkPixbufRotation, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_saturate_and_pixelate, 0, 3, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, dest, Gtk4\\GdkPixbuf, 0)
	ZEND_ARG_TYPE_INFO(0, saturation, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, pixelate, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_scale, 0, 10, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, dest, Gtk4\\GdkPixbuf, 0)
	ZEND_ARG_TYPE_INFO(0, dest_x, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, dest_y, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, dest_width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, dest_height, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, offset_x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, offset_y, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, scale_x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, scale_y, IS_DOUBLE, 0)
	ZEND_ARG_OBJ_INFO(0, interp_type, Gtk4\\GdkInterpType, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_scale_simple, 0, 3, Gtk4\\GdkPixbuf, 1)
	ZEND_ARG_TYPE_INFO(0, dest_width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, dest_height, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, interp_type, Gtk4\\GdkInterpType, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_set_option, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, value, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_get_file_info, 0, 1, IS_ARRAY, 1)
	ZEND_ARG_TYPE_INFO(0, filename, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkPixbuf_get_options arginfo_class_Gtk4_GdkPixbuf_get_formats

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_save_to_bufferv, 0, 1, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, type, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, options, IS_ARRAY, 0, "[]")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbuf_savev, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, filename, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, type, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, options, IS_ARRAY, 0, "[]")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GdkPixbufAnimation___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbufAnimation_new_from_file, 0, 1, Gtk4\\GdkPixbufAnimation, 0)
	ZEND_ARG_TYPE_INFO(0, filename, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbufAnimation_new_from_resource, 0, 1, Gtk4\\GdkPixbufAnimation, 0)
	ZEND_ARG_TYPE_INFO(0, resource_path, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbufAnimation_new_from_stream_finish, 0, 1, Gtk4\\GdkPixbufAnimation, 0)
	ZEND_ARG_OBJ_INFO(0, async_result, Gtk4\\GAsyncResult, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkPixbufAnimation_get_height arginfo_class_Gtk4_GdkPixbuf_get_bits_per_sample

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbufAnimation_get_static_image, 0, 0, Gtk4\\GdkPixbuf, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkPixbufAnimation_get_width arginfo_class_Gtk4_GdkPixbuf_get_bits_per_sample

#define arginfo_class_Gtk4_GdkPixbufAnimation_is_static_image arginfo_class_Gtk4_GdkPixbuf_get_has_alpha

#define arginfo_class_Gtk4_GdkPixbufAnimationIter___construct arginfo_class_Gtk4_GdkPixbufAnimation___construct

#define arginfo_class_Gtk4_GdkPixbufAnimationIter_get_delay_time arginfo_class_Gtk4_GdkPixbuf_get_bits_per_sample

#define arginfo_class_Gtk4_GdkPixbufAnimationIter_get_pixbuf arginfo_class_Gtk4_GdkPixbufAnimation_get_static_image

#define arginfo_class_Gtk4_GdkPixbufAnimationIter_on_currently_loading_frame arginfo_class_Gtk4_GdkPixbuf_get_has_alpha

#define arginfo_class_Gtk4_GdkPixbufFormat___construct arginfo_class_Gtk4_GdkPixbufAnimation___construct

#define arginfo_class_Gtk4_GdkPixbufFormat_get_description arginfo_class_Gtk4_GdkPixbuf_read_pixel_bytes

#define arginfo_class_Gtk4_GdkPixbufFormat_get_extensions arginfo_class_Gtk4_GdkPixbuf_get_formats

#define arginfo_class_Gtk4_GdkPixbufFormat_get_license arginfo_class_Gtk4_GdkPixbuf_read_pixel_bytes

#define arginfo_class_Gtk4_GdkPixbufFormat_get_mime_types arginfo_class_Gtk4_GdkPixbuf_get_formats

#define arginfo_class_Gtk4_GdkPixbufFormat_get_name arginfo_class_Gtk4_GdkPixbuf_read_pixel_bytes

#define arginfo_class_Gtk4_GdkPixbufFormat_is_disabled arginfo_class_Gtk4_GdkPixbuf_get_has_alpha

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbufFormat_is_save_option_supported, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, option_key, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkPixbufFormat_is_scalable arginfo_class_Gtk4_GdkPixbuf_get_has_alpha

#define arginfo_class_Gtk4_GdkPixbufFormat_is_writable arginfo_class_Gtk4_GdkPixbuf_get_has_alpha

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbufFormat_set_disabled, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, disabled, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkPixbufLoader___construct arginfo_class_Gtk4_GdkPixbufAnimation___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbufLoader_new_with_mime_type, 0, 1, Gtk4\\GdkPixbufLoader, 0)
	ZEND_ARG_TYPE_INFO(0, mime_type, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbufLoader_new_with_type, 0, 1, Gtk4\\GdkPixbufLoader, 0)
	ZEND_ARG_TYPE_INFO(0, image_type, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkPixbufLoader_close arginfo_class_Gtk4_GdkPixbuf_get_has_alpha

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbufLoader_get_animation, 0, 0, Gtk4\\GdkPixbufAnimation, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPixbufLoader_get_format, 0, 0, Gtk4\\GdkPixbufFormat, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkPixbufLoader_get_pixbuf arginfo_class_Gtk4_GdkPixbuf_apply_embedded_orientation

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbufLoader_set_size, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbufLoader_write_bytes, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, buffer, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbufLoader_vfunc_area_prepared, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPixbufLoader_vfunc_area_updated, 0, 4, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, x, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkPixbufLoader_vfunc_closed arginfo_class_Gtk4_GdkPixbufLoader_vfunc_area_prepared

#define arginfo_class_Gtk4_GdkPixbufLoader_vfunc_size_prepared arginfo_class_Gtk4_GdkPixbufLoader_set_size

ZEND_METHOD(Gtk4_GdkPixbuf, __construct);
ZEND_METHOD(Gtk4_GdkPixbuf, new_from_bytes);
ZEND_METHOD(Gtk4_GdkPixbuf, new_from_file);
ZEND_METHOD(Gtk4_GdkPixbuf, new_from_file_at_scale);
ZEND_METHOD(Gtk4_GdkPixbuf, new_from_file_at_size);
ZEND_METHOD(Gtk4_GdkPixbuf, new_from_resource);
ZEND_METHOD(Gtk4_GdkPixbuf, new_from_resource_at_scale);
ZEND_METHOD(Gtk4_GdkPixbuf, new_from_stream_finish);
ZEND_METHOD(Gtk4_GdkPixbuf, new_from_xpm_data);
ZEND_METHOD(Gtk4_GdkPixbuf, calculate_rowstride);
ZEND_METHOD(Gtk4_GdkPixbuf, get_file_info_async);
ZEND_METHOD(Gtk4_GdkPixbuf, get_formats);
ZEND_METHOD(Gtk4_GdkPixbuf, init_modules);
ZEND_METHOD(Gtk4_GdkPixbuf, save_to_stream_finish);
ZEND_METHOD(Gtk4_GdkPixbuf, add_alpha);
ZEND_METHOD(Gtk4_GdkPixbuf, apply_embedded_orientation);
ZEND_METHOD(Gtk4_GdkPixbuf, composite);
ZEND_METHOD(Gtk4_GdkPixbuf, composite_color_simple);
ZEND_METHOD(Gtk4_GdkPixbuf, copy);
ZEND_METHOD(Gtk4_GdkPixbuf, copy_area);
ZEND_METHOD(Gtk4_GdkPixbuf, copy_options);
ZEND_METHOD(Gtk4_GdkPixbuf, fill);
ZEND_METHOD(Gtk4_GdkPixbuf, flip);
ZEND_METHOD(Gtk4_GdkPixbuf, get_bits_per_sample);
ZEND_METHOD(Gtk4_GdkPixbuf, get_byte_length);
ZEND_METHOD(Gtk4_GdkPixbuf, get_colorspace);
ZEND_METHOD(Gtk4_GdkPixbuf, get_has_alpha);
ZEND_METHOD(Gtk4_GdkPixbuf, get_height);
ZEND_METHOD(Gtk4_GdkPixbuf, get_n_channels);
ZEND_METHOD(Gtk4_GdkPixbuf, get_option);
ZEND_METHOD(Gtk4_GdkPixbuf, get_rowstride);
ZEND_METHOD(Gtk4_GdkPixbuf, get_width);
ZEND_METHOD(Gtk4_GdkPixbuf, new_subpixbuf);
ZEND_METHOD(Gtk4_GdkPixbuf, read_pixel_bytes);
ZEND_METHOD(Gtk4_GdkPixbuf, remove_option);
ZEND_METHOD(Gtk4_GdkPixbuf, rotate_simple);
ZEND_METHOD(Gtk4_GdkPixbuf, saturate_and_pixelate);
ZEND_METHOD(Gtk4_GdkPixbuf, scale);
ZEND_METHOD(Gtk4_GdkPixbuf, scale_simple);
ZEND_METHOD(Gtk4_GdkPixbuf, set_option);
ZEND_METHOD(Gtk4_GdkPixbuf, get_file_info);
ZEND_METHOD(Gtk4_GdkPixbuf, get_options);
ZEND_METHOD(Gtk4_GdkPixbuf, save_to_bufferv);
ZEND_METHOD(Gtk4_GdkPixbuf, savev);
ZEND_METHOD(Gtk4_GdkPixbufAnimation, __construct);
ZEND_METHOD(Gtk4_GdkPixbufAnimation, new_from_file);
ZEND_METHOD(Gtk4_GdkPixbufAnimation, new_from_resource);
ZEND_METHOD(Gtk4_GdkPixbufAnimation, new_from_stream_finish);
ZEND_METHOD(Gtk4_GdkPixbufAnimation, get_height);
ZEND_METHOD(Gtk4_GdkPixbufAnimation, get_static_image);
ZEND_METHOD(Gtk4_GdkPixbufAnimation, get_width);
ZEND_METHOD(Gtk4_GdkPixbufAnimation, is_static_image);
ZEND_METHOD(Gtk4_GdkPixbufAnimationIter, __construct);
ZEND_METHOD(Gtk4_GdkPixbufAnimationIter, get_delay_time);
ZEND_METHOD(Gtk4_GdkPixbufAnimationIter, get_pixbuf);
ZEND_METHOD(Gtk4_GdkPixbufAnimationIter, on_currently_loading_frame);
ZEND_METHOD(Gtk4_GdkPixbufFormat, __construct);
ZEND_METHOD(Gtk4_GdkPixbufFormat, get_description);
ZEND_METHOD(Gtk4_GdkPixbufFormat, get_extensions);
ZEND_METHOD(Gtk4_GdkPixbufFormat, get_license);
ZEND_METHOD(Gtk4_GdkPixbufFormat, get_mime_types);
ZEND_METHOD(Gtk4_GdkPixbufFormat, get_name);
ZEND_METHOD(Gtk4_GdkPixbufFormat, is_disabled);
ZEND_METHOD(Gtk4_GdkPixbufFormat, is_save_option_supported);
ZEND_METHOD(Gtk4_GdkPixbufFormat, is_scalable);
ZEND_METHOD(Gtk4_GdkPixbufFormat, is_writable);
ZEND_METHOD(Gtk4_GdkPixbufFormat, set_disabled);
ZEND_METHOD(Gtk4_GdkPixbufLoader, __construct);
ZEND_METHOD(Gtk4_GdkPixbufLoader, new_with_mime_type);
ZEND_METHOD(Gtk4_GdkPixbufLoader, new_with_type);
ZEND_METHOD(Gtk4_GdkPixbufLoader, close);
ZEND_METHOD(Gtk4_GdkPixbufLoader, get_animation);
ZEND_METHOD(Gtk4_GdkPixbufLoader, get_format);
ZEND_METHOD(Gtk4_GdkPixbufLoader, get_pixbuf);
ZEND_METHOD(Gtk4_GdkPixbufLoader, set_size);
ZEND_METHOD(Gtk4_GdkPixbufLoader, write_bytes);
ZEND_METHOD(Gtk4_GdkPixbufLoader, vfunc_area_prepared);
ZEND_METHOD(Gtk4_GdkPixbufLoader, vfunc_area_updated);
ZEND_METHOD(Gtk4_GdkPixbufLoader, vfunc_closed);
ZEND_METHOD(Gtk4_GdkPixbufLoader, vfunc_size_prepared);

static const zend_function_entry class_Gtk4_GdkPixbuf_methods[] = {
	ZEND_ME(Gtk4_GdkPixbuf, __construct, arginfo_class_Gtk4_GdkPixbuf___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, new_from_bytes, arginfo_class_Gtk4_GdkPixbuf_new_from_bytes, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbuf, new_from_file, arginfo_class_Gtk4_GdkPixbuf_new_from_file, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbuf, new_from_file_at_scale, arginfo_class_Gtk4_GdkPixbuf_new_from_file_at_scale, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbuf, new_from_file_at_size, arginfo_class_Gtk4_GdkPixbuf_new_from_file_at_size, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbuf, new_from_resource, arginfo_class_Gtk4_GdkPixbuf_new_from_resource, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbuf, new_from_resource_at_scale, arginfo_class_Gtk4_GdkPixbuf_new_from_resource_at_scale, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbuf, new_from_stream_finish, arginfo_class_Gtk4_GdkPixbuf_new_from_stream_finish, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbuf, new_from_xpm_data, arginfo_class_Gtk4_GdkPixbuf_new_from_xpm_data, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbuf, calculate_rowstride, arginfo_class_Gtk4_GdkPixbuf_calculate_rowstride, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbuf, get_file_info_async, arginfo_class_Gtk4_GdkPixbuf_get_file_info_async, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbuf, get_formats, arginfo_class_Gtk4_GdkPixbuf_get_formats, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbuf, init_modules, arginfo_class_Gtk4_GdkPixbuf_init_modules, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbuf, save_to_stream_finish, arginfo_class_Gtk4_GdkPixbuf_save_to_stream_finish, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbuf, add_alpha, arginfo_class_Gtk4_GdkPixbuf_add_alpha, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, apply_embedded_orientation, arginfo_class_Gtk4_GdkPixbuf_apply_embedded_orientation, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, composite, arginfo_class_Gtk4_GdkPixbuf_composite, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, composite_color_simple, arginfo_class_Gtk4_GdkPixbuf_composite_color_simple, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, copy, arginfo_class_Gtk4_GdkPixbuf_copy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, copy_area, arginfo_class_Gtk4_GdkPixbuf_copy_area, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, copy_options, arginfo_class_Gtk4_GdkPixbuf_copy_options, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, fill, arginfo_class_Gtk4_GdkPixbuf_fill, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, flip, arginfo_class_Gtk4_GdkPixbuf_flip, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, get_bits_per_sample, arginfo_class_Gtk4_GdkPixbuf_get_bits_per_sample, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, get_byte_length, arginfo_class_Gtk4_GdkPixbuf_get_byte_length, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, get_colorspace, arginfo_class_Gtk4_GdkPixbuf_get_colorspace, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, get_has_alpha, arginfo_class_Gtk4_GdkPixbuf_get_has_alpha, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, get_height, arginfo_class_Gtk4_GdkPixbuf_get_height, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, get_n_channels, arginfo_class_Gtk4_GdkPixbuf_get_n_channels, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, get_option, arginfo_class_Gtk4_GdkPixbuf_get_option, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, get_rowstride, arginfo_class_Gtk4_GdkPixbuf_get_rowstride, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, get_width, arginfo_class_Gtk4_GdkPixbuf_get_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, new_subpixbuf, arginfo_class_Gtk4_GdkPixbuf_new_subpixbuf, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, read_pixel_bytes, arginfo_class_Gtk4_GdkPixbuf_read_pixel_bytes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, remove_option, arginfo_class_Gtk4_GdkPixbuf_remove_option, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, rotate_simple, arginfo_class_Gtk4_GdkPixbuf_rotate_simple, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, saturate_and_pixelate, arginfo_class_Gtk4_GdkPixbuf_saturate_and_pixelate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, scale, arginfo_class_Gtk4_GdkPixbuf_scale, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, scale_simple, arginfo_class_Gtk4_GdkPixbuf_scale_simple, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, set_option, arginfo_class_Gtk4_GdkPixbuf_set_option, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, get_file_info, arginfo_class_Gtk4_GdkPixbuf_get_file_info, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbuf, get_options, arginfo_class_Gtk4_GdkPixbuf_get_options, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, save_to_bufferv, arginfo_class_Gtk4_GdkPixbuf_save_to_bufferv, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbuf, savev, arginfo_class_Gtk4_GdkPixbuf_savev, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkPixbufAnimation_methods[] = {
	ZEND_ME(Gtk4_GdkPixbufAnimation, __construct, arginfo_class_Gtk4_GdkPixbufAnimation___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GdkPixbufAnimation, new_from_file, arginfo_class_Gtk4_GdkPixbufAnimation_new_from_file, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbufAnimation, new_from_resource, arginfo_class_Gtk4_GdkPixbufAnimation_new_from_resource, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbufAnimation, new_from_stream_finish, arginfo_class_Gtk4_GdkPixbufAnimation_new_from_stream_finish, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbufAnimation, get_height, arginfo_class_Gtk4_GdkPixbufAnimation_get_height, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufAnimation, get_static_image, arginfo_class_Gtk4_GdkPixbufAnimation_get_static_image, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufAnimation, get_width, arginfo_class_Gtk4_GdkPixbufAnimation_get_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufAnimation, is_static_image, arginfo_class_Gtk4_GdkPixbufAnimation_is_static_image, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkPixbufAnimationIter_methods[] = {
	ZEND_ME(Gtk4_GdkPixbufAnimationIter, __construct, arginfo_class_Gtk4_GdkPixbufAnimationIter___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GdkPixbufAnimationIter, get_delay_time, arginfo_class_Gtk4_GdkPixbufAnimationIter_get_delay_time, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufAnimationIter, get_pixbuf, arginfo_class_Gtk4_GdkPixbufAnimationIter_get_pixbuf, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufAnimationIter, on_currently_loading_frame, arginfo_class_Gtk4_GdkPixbufAnimationIter_on_currently_loading_frame, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkPixbufFormat_methods[] = {
	ZEND_ME(Gtk4_GdkPixbufFormat, __construct, arginfo_class_Gtk4_GdkPixbufFormat___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GdkPixbufFormat, get_description, arginfo_class_Gtk4_GdkPixbufFormat_get_description, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufFormat, get_extensions, arginfo_class_Gtk4_GdkPixbufFormat_get_extensions, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufFormat, get_license, arginfo_class_Gtk4_GdkPixbufFormat_get_license, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufFormat, get_mime_types, arginfo_class_Gtk4_GdkPixbufFormat_get_mime_types, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufFormat, get_name, arginfo_class_Gtk4_GdkPixbufFormat_get_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufFormat, is_disabled, arginfo_class_Gtk4_GdkPixbufFormat_is_disabled, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufFormat, is_save_option_supported, arginfo_class_Gtk4_GdkPixbufFormat_is_save_option_supported, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufFormat, is_scalable, arginfo_class_Gtk4_GdkPixbufFormat_is_scalable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufFormat, is_writable, arginfo_class_Gtk4_GdkPixbufFormat_is_writable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufFormat, set_disabled, arginfo_class_Gtk4_GdkPixbufFormat_set_disabled, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkPixbufLoader_methods[] = {
	ZEND_ME(Gtk4_GdkPixbufLoader, __construct, arginfo_class_Gtk4_GdkPixbufLoader___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufLoader, new_with_mime_type, arginfo_class_Gtk4_GdkPixbufLoader_new_with_mime_type, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbufLoader, new_with_type, arginfo_class_Gtk4_GdkPixbufLoader_new_with_type, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkPixbufLoader, close, arginfo_class_Gtk4_GdkPixbufLoader_close, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufLoader, get_animation, arginfo_class_Gtk4_GdkPixbufLoader_get_animation, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufLoader, get_format, arginfo_class_Gtk4_GdkPixbufLoader_get_format, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufLoader, get_pixbuf, arginfo_class_Gtk4_GdkPixbufLoader_get_pixbuf, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufLoader, set_size, arginfo_class_Gtk4_GdkPixbufLoader_set_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufLoader, write_bytes, arginfo_class_Gtk4_GdkPixbufLoader_write_bytes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufLoader, vfunc_area_prepared, arginfo_class_Gtk4_GdkPixbufLoader_vfunc_area_prepared, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufLoader, vfunc_area_updated, arginfo_class_Gtk4_GdkPixbufLoader_vfunc_area_updated, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufLoader, vfunc_closed, arginfo_class_Gtk4_GdkPixbufLoader_vfunc_closed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPixbufLoader, vfunc_size_prepared, arginfo_class_Gtk4_GdkPixbufLoader_vfunc_size_prepared, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static zend_class_entry *register_class_Gtk4_GdkColorspace(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GdkColorspace", IS_LONG, NULL);

	zval enum_case_Rgb_value;
	ZVAL_LONG(&enum_case_Rgb_value, 0);
	zend_enum_add_case_cstr(class_entry, "Rgb", &enum_case_Rgb_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkInterpType(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GdkInterpType", IS_LONG, NULL);

	zval enum_case_Nearest_value;
	ZVAL_LONG(&enum_case_Nearest_value, 0);
	zend_enum_add_case_cstr(class_entry, "Nearest", &enum_case_Nearest_value);

	zval enum_case_Tiles_value;
	ZVAL_LONG(&enum_case_Tiles_value, 1);
	zend_enum_add_case_cstr(class_entry, "Tiles", &enum_case_Tiles_value);

	zval enum_case_Bilinear_value;
	ZVAL_LONG(&enum_case_Bilinear_value, 2);
	zend_enum_add_case_cstr(class_entry, "Bilinear", &enum_case_Bilinear_value);

	zval enum_case_Hyper_value;
	ZVAL_LONG(&enum_case_Hyper_value, 3);
	zend_enum_add_case_cstr(class_entry, "Hyper", &enum_case_Hyper_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkPixbuf(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkPixbuf", class_Gtk4_GdkPixbuf_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkPixbufAnimation(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkPixbufAnimation", class_Gtk4_GdkPixbufAnimation_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkPixbufAnimationIter(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkPixbufAnimationIter", class_Gtk4_GdkPixbufAnimationIter_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkPixbufFormat(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkPixbufFormat", class_Gtk4_GdkPixbufFormat_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkPixbufLoader(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkPixbufLoader", class_Gtk4_GdkPixbufLoader_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkPixbufRotation(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GdkPixbufRotation", IS_LONG, NULL);

	zval enum_case_None_value;
	ZVAL_LONG(&enum_case_None_value, 0);
	zend_enum_add_case_cstr(class_entry, "None", &enum_case_None_value);

	zval enum_case_Counterclockwise_value;
	ZVAL_LONG(&enum_case_Counterclockwise_value, 90);
	zend_enum_add_case_cstr(class_entry, "Counterclockwise", &enum_case_Counterclockwise_value);

	zval enum_case_Upsidedown_value;
	ZVAL_LONG(&enum_case_Upsidedown_value, 180);
	zend_enum_add_case_cstr(class_entry, "Upsidedown", &enum_case_Upsidedown_value);

	zval enum_case_Clockwise_value;
	ZVAL_LONG(&enum_case_Clockwise_value, 270);
	zend_enum_add_case_cstr(class_entry, "Clockwise", &enum_case_Clockwise_value);

	return class_entry;
}
