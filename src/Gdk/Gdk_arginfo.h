/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: b79d5c48fb73792f0dfbed5f402b73d6d35d053f */

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GdkDisplay___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkDisplay_get_default, 0, 0, Gtk4\\GdkDisplay, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkDisplay_open, 0, 0, Gtk4\\GdkDisplay, 1)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, display_name, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkDisplay_beep, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkDisplay_close arginfo_class_Gtk4_GdkDisplay_beep

#define arginfo_class_Gtk4_GdkDisplay_flush arginfo_class_Gtk4_GdkDisplay_beep

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkDisplay_get_monitors, 0, 0, Gtk4\\GListModel, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkDisplay_get_name, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkDisplay_is_closed, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkDisplay_is_composited arginfo_class_Gtk4_GdkDisplay_is_closed

#define arginfo_class_Gtk4_GdkDisplay_is_rgba arginfo_class_Gtk4_GdkDisplay_is_closed

#define arginfo_class_Gtk4_GdkDisplay_prepare_gl arginfo_class_Gtk4_GdkDisplay_is_closed

#define arginfo_class_Gtk4_GdkDisplay_supports_input_shapes arginfo_class_Gtk4_GdkDisplay_is_closed

#define arginfo_class_Gtk4_GdkDisplay_supports_shadow_width arginfo_class_Gtk4_GdkDisplay_is_closed

#define arginfo_class_Gtk4_GdkDisplay_sync arginfo_class_Gtk4_GdkDisplay_beep

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkPaintable_get_current_image, 0, 0, Gtk4\\GdkPaintable, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPaintable_get_flags, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPaintable_get_intrinsic_aspect_ratio, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkPaintable_get_intrinsic_height arginfo_class_Gtk4_GdkPaintable_get_flags

#define arginfo_class_Gtk4_GdkPaintable_get_intrinsic_width arginfo_class_Gtk4_GdkPaintable_get_flags

#define arginfo_class_Gtk4_GdkPaintableObject___construct arginfo_class_Gtk4_GdkDisplay___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkPaintableObject_compute_concrete_size, 0, 4, IS_ARRAY, 0)
	ZEND_ARG_TYPE_INFO(0, specified_width, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, specified_height, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, default_width, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, default_height, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkPaintableObject_get_current_image arginfo_class_Gtk4_GdkPaintable_get_current_image

#define arginfo_class_Gtk4_GdkPaintableObject_get_flags arginfo_class_Gtk4_GdkPaintable_get_flags

#define arginfo_class_Gtk4_GdkPaintableObject_get_intrinsic_aspect_ratio arginfo_class_Gtk4_GdkPaintable_get_intrinsic_aspect_ratio

#define arginfo_class_Gtk4_GdkPaintableObject_get_intrinsic_height arginfo_class_Gtk4_GdkPaintable_get_flags

#define arginfo_class_Gtk4_GdkPaintableObject_get_intrinsic_width arginfo_class_Gtk4_GdkPaintable_get_flags

#define arginfo_class_Gtk4_GdkPaintableObject_invalidate_contents arginfo_class_Gtk4_GdkDisplay_beep

#define arginfo_class_Gtk4_GdkPaintableObject_invalidate_size arginfo_class_Gtk4_GdkDisplay_beep

#define arginfo_class_Gtk4_GdkTexture___construct arginfo_class_Gtk4_GdkDisplay___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkTexture_new_from_bytes, 0, 1, Gtk4\\GdkTexture, 0)
	ZEND_ARG_TYPE_INFO(0, bytes, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkTexture_new_from_filename, 0, 1, Gtk4\\GdkTexture, 0)
	ZEND_ARG_TYPE_INFO(0, path, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkTexture_get_format, 0, 0, Gtk4\\GdkMemoryFormat, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkTexture_get_height arginfo_class_Gtk4_GdkPaintable_get_flags

#define arginfo_class_Gtk4_GdkTexture_get_width arginfo_class_Gtk4_GdkPaintable_get_flags

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkTexture_save_to_png, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, filename, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkTexture_save_to_png_bytes arginfo_class_Gtk4_GdkDisplay_get_name

#define arginfo_class_Gtk4_GdkTexture_save_to_tiff arginfo_class_Gtk4_GdkTexture_save_to_png

#define arginfo_class_Gtk4_GdkTexture_save_to_tiff_bytes arginfo_class_Gtk4_GdkDisplay_get_name

#define arginfo_class_Gtk4_GdkTexture_compute_concrete_size arginfo_class_Gtk4_GdkPaintableObject_compute_concrete_size

#define arginfo_class_Gtk4_GdkTexture_get_current_image arginfo_class_Gtk4_GdkPaintable_get_current_image

#define arginfo_class_Gtk4_GdkTexture_get_flags arginfo_class_Gtk4_GdkPaintable_get_flags

#define arginfo_class_Gtk4_GdkTexture_get_intrinsic_aspect_ratio arginfo_class_Gtk4_GdkPaintable_get_intrinsic_aspect_ratio

#define arginfo_class_Gtk4_GdkTexture_get_intrinsic_height arginfo_class_Gtk4_GdkPaintable_get_flags

#define arginfo_class_Gtk4_GdkTexture_get_intrinsic_width arginfo_class_Gtk4_GdkPaintable_get_flags

#define arginfo_class_Gtk4_GdkTexture_invalidate_contents arginfo_class_Gtk4_GdkDisplay_beep

#define arginfo_class_Gtk4_GdkTexture_invalidate_size arginfo_class_Gtk4_GdkDisplay_beep

ZEND_METHOD(Gtk4_GdkDisplay, __construct);
ZEND_METHOD(Gtk4_GdkDisplay, get_default);
ZEND_METHOD(Gtk4_GdkDisplay, open);
ZEND_METHOD(Gtk4_GdkDisplay, beep);
ZEND_METHOD(Gtk4_GdkDisplay, close);
ZEND_METHOD(Gtk4_GdkDisplay, flush);
ZEND_METHOD(Gtk4_GdkDisplay, get_monitors);
ZEND_METHOD(Gtk4_GdkDisplay, get_name);
ZEND_METHOD(Gtk4_GdkDisplay, is_closed);
ZEND_METHOD(Gtk4_GdkDisplay, is_composited);
ZEND_METHOD(Gtk4_GdkDisplay, is_rgba);
ZEND_METHOD(Gtk4_GdkDisplay, prepare_gl);
ZEND_METHOD(Gtk4_GdkDisplay, supports_input_shapes);
ZEND_METHOD(Gtk4_GdkDisplay, supports_shadow_width);
ZEND_METHOD(Gtk4_GdkDisplay, sync);
ZEND_METHOD(Gtk4_GdkPaintableObject, __construct);
ZEND_METHOD(Gtk4_GdkPaintable, compute_concrete_size);
ZEND_METHOD(Gtk4_GdkPaintable, invalidate_contents);
ZEND_METHOD(Gtk4_GdkPaintable, invalidate_size);
ZEND_METHOD(Gtk4_GdkTexture, __construct);
ZEND_METHOD(Gtk4_GdkTexture, new_from_bytes);
ZEND_METHOD(Gtk4_GdkTexture, new_from_filename);
ZEND_METHOD(Gtk4_GdkTexture, get_format);
ZEND_METHOD(Gtk4_GdkTexture, get_height);
ZEND_METHOD(Gtk4_GdkTexture, get_width);
ZEND_METHOD(Gtk4_GdkTexture, save_to_png);
ZEND_METHOD(Gtk4_GdkTexture, save_to_png_bytes);
ZEND_METHOD(Gtk4_GdkTexture, save_to_tiff);
ZEND_METHOD(Gtk4_GdkTexture, save_to_tiff_bytes);

static const zend_function_entry class_Gtk4_GdkDisplay_methods[] = {
	ZEND_ME(Gtk4_GdkDisplay, __construct, arginfo_class_Gtk4_GdkDisplay___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GdkDisplay, get_default, arginfo_class_Gtk4_GdkDisplay_get_default, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkDisplay, open, arginfo_class_Gtk4_GdkDisplay_open, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkDisplay, beep, arginfo_class_Gtk4_GdkDisplay_beep, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkDisplay, close, arginfo_class_Gtk4_GdkDisplay_close, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkDisplay, flush, arginfo_class_Gtk4_GdkDisplay_flush, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkDisplay, get_monitors, arginfo_class_Gtk4_GdkDisplay_get_monitors, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkDisplay, get_name, arginfo_class_Gtk4_GdkDisplay_get_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkDisplay, is_closed, arginfo_class_Gtk4_GdkDisplay_is_closed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkDisplay, is_composited, arginfo_class_Gtk4_GdkDisplay_is_composited, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkDisplay, is_rgba, arginfo_class_Gtk4_GdkDisplay_is_rgba, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkDisplay, prepare_gl, arginfo_class_Gtk4_GdkDisplay_prepare_gl, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkDisplay, supports_input_shapes, arginfo_class_Gtk4_GdkDisplay_supports_input_shapes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkDisplay, supports_shadow_width, arginfo_class_Gtk4_GdkDisplay_supports_shadow_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkDisplay, sync, arginfo_class_Gtk4_GdkDisplay_sync, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkPaintable_methods[] = {
	ZEND_RAW_FENTRY("get_current_image", NULL, arginfo_class_Gtk4_GdkPaintable_get_current_image, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_flags", NULL, arginfo_class_Gtk4_GdkPaintable_get_flags, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_intrinsic_aspect_ratio", NULL, arginfo_class_Gtk4_GdkPaintable_get_intrinsic_aspect_ratio, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_intrinsic_height", NULL, arginfo_class_Gtk4_GdkPaintable_get_intrinsic_height, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_intrinsic_width", NULL, arginfo_class_Gtk4_GdkPaintable_get_intrinsic_width, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkPaintableObject_methods[] = {
	ZEND_ME(Gtk4_GdkPaintableObject, __construct, arginfo_class_Gtk4_GdkPaintableObject___construct, ZEND_ACC_PRIVATE)
	ZEND_RAW_FENTRY("compute_concrete_size", zim_Gtk4_GdkPaintable_compute_concrete_size, arginfo_class_Gtk4_GdkPaintableObject_compute_concrete_size, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_current_image", zim_Gtk4_GdkPaintable_get_current_image, arginfo_class_Gtk4_GdkPaintableObject_get_current_image, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_flags", zim_Gtk4_GdkPaintable_get_flags, arginfo_class_Gtk4_GdkPaintableObject_get_flags, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_intrinsic_aspect_ratio", zim_Gtk4_GdkPaintable_get_intrinsic_aspect_ratio, arginfo_class_Gtk4_GdkPaintableObject_get_intrinsic_aspect_ratio, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_intrinsic_height", zim_Gtk4_GdkPaintable_get_intrinsic_height, arginfo_class_Gtk4_GdkPaintableObject_get_intrinsic_height, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_intrinsic_width", zim_Gtk4_GdkPaintable_get_intrinsic_width, arginfo_class_Gtk4_GdkPaintableObject_get_intrinsic_width, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("invalidate_contents", zim_Gtk4_GdkPaintable_invalidate_contents, arginfo_class_Gtk4_GdkPaintableObject_invalidate_contents, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("invalidate_size", zim_Gtk4_GdkPaintable_invalidate_size, arginfo_class_Gtk4_GdkPaintableObject_invalidate_size, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkTexture_methods[] = {
	ZEND_ME(Gtk4_GdkTexture, __construct, arginfo_class_Gtk4_GdkTexture___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GdkTexture, new_from_bytes, arginfo_class_Gtk4_GdkTexture_new_from_bytes, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkTexture, new_from_filename, arginfo_class_Gtk4_GdkTexture_new_from_filename, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkTexture, get_format, arginfo_class_Gtk4_GdkTexture_get_format, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, get_height, arginfo_class_Gtk4_GdkTexture_get_height, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, get_width, arginfo_class_Gtk4_GdkTexture_get_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, save_to_png, arginfo_class_Gtk4_GdkTexture_save_to_png, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, save_to_png_bytes, arginfo_class_Gtk4_GdkTexture_save_to_png_bytes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, save_to_tiff, arginfo_class_Gtk4_GdkTexture_save_to_tiff, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, save_to_tiff_bytes, arginfo_class_Gtk4_GdkTexture_save_to_tiff_bytes, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("compute_concrete_size", zim_Gtk4_GdkPaintable_compute_concrete_size, arginfo_class_Gtk4_GdkTexture_compute_concrete_size, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_current_image", zim_Gtk4_GdkPaintable_get_current_image, arginfo_class_Gtk4_GdkTexture_get_current_image, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_flags", zim_Gtk4_GdkPaintable_get_flags, arginfo_class_Gtk4_GdkTexture_get_flags, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_intrinsic_aspect_ratio", zim_Gtk4_GdkPaintable_get_intrinsic_aspect_ratio, arginfo_class_Gtk4_GdkTexture_get_intrinsic_aspect_ratio, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_intrinsic_height", zim_Gtk4_GdkPaintable_get_intrinsic_height, arginfo_class_Gtk4_GdkTexture_get_intrinsic_height, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_intrinsic_width", zim_Gtk4_GdkPaintable_get_intrinsic_width, arginfo_class_Gtk4_GdkTexture_get_intrinsic_width, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("invalidate_contents", zim_Gtk4_GdkPaintable_invalidate_contents, arginfo_class_Gtk4_GdkTexture_invalidate_contents, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("invalidate_size", zim_Gtk4_GdkPaintable_invalidate_size, arginfo_class_Gtk4_GdkTexture_invalidate_size, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static zend_class_entry *register_class_Gtk4_GdkCrossingMode(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GdkCrossingMode", IS_LONG, NULL);

	zval enum_case_Normal_value;
	ZVAL_LONG(&enum_case_Normal_value, 0);
	zend_enum_add_case_cstr(class_entry, "Normal", &enum_case_Normal_value);

	zval enum_case_Grab_value;
	ZVAL_LONG(&enum_case_Grab_value, 1);
	zend_enum_add_case_cstr(class_entry, "Grab", &enum_case_Grab_value);

	zval enum_case_Ungrab_value;
	ZVAL_LONG(&enum_case_Ungrab_value, 2);
	zend_enum_add_case_cstr(class_entry, "Ungrab", &enum_case_Ungrab_value);

	zval enum_case_GtkGrab_value;
	ZVAL_LONG(&enum_case_GtkGrab_value, 3);
	zend_enum_add_case_cstr(class_entry, "GtkGrab", &enum_case_GtkGrab_value);

	zval enum_case_GtkUngrab_value;
	ZVAL_LONG(&enum_case_GtkUngrab_value, 4);
	zend_enum_add_case_cstr(class_entry, "GtkUngrab", &enum_case_GtkUngrab_value);

	zval enum_case_StateChanged_value;
	ZVAL_LONG(&enum_case_StateChanged_value, 5);
	zend_enum_add_case_cstr(class_entry, "StateChanged", &enum_case_StateChanged_value);

	zval enum_case_TouchBegin_value;
	ZVAL_LONG(&enum_case_TouchBegin_value, 6);
	zend_enum_add_case_cstr(class_entry, "TouchBegin", &enum_case_TouchBegin_value);

	zval enum_case_TouchEnd_value;
	ZVAL_LONG(&enum_case_TouchEnd_value, 7);
	zend_enum_add_case_cstr(class_entry, "TouchEnd", &enum_case_TouchEnd_value);

	zval enum_case_DeviceSwitch_value;
	ZVAL_LONG(&enum_case_DeviceSwitch_value, 8);
	zend_enum_add_case_cstr(class_entry, "DeviceSwitch", &enum_case_DeviceSwitch_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkDisplay(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkDisplay", class_Gtk4_GdkDisplay_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkDragAction(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkDragAction", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_COPY_value;
	ZVAL_LONG(&const_COPY_value, 1);
	zend_string *const_COPY_name = zend_string_init_interned("COPY", sizeof("COPY") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_COPY_name, &const_COPY_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_COPY_name);

	zval const_MOVE_value;
	ZVAL_LONG(&const_MOVE_value, 2);
	zend_string *const_MOVE_name = zend_string_init_interned("MOVE", sizeof("MOVE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_MOVE_name, &const_MOVE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_MOVE_name);

	zval const_LINK_value;
	ZVAL_LONG(&const_LINK_value, 4);
	zend_string *const_LINK_name = zend_string_init_interned("LINK", sizeof("LINK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_LINK_name, &const_LINK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_LINK_name);

	zval const_ASK_value;
	ZVAL_LONG(&const_ASK_value, 8);
	zend_string *const_ASK_name = zend_string_init_interned("ASK", sizeof("ASK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_ASK_name, &const_ASK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_ASK_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkEventType(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GdkEventType", IS_LONG, NULL);

	zval enum_case_Delete_value;
	ZVAL_LONG(&enum_case_Delete_value, 0);
	zend_enum_add_case_cstr(class_entry, "Delete", &enum_case_Delete_value);

	zval enum_case_MotionNotify_value;
	ZVAL_LONG(&enum_case_MotionNotify_value, 1);
	zend_enum_add_case_cstr(class_entry, "MotionNotify", &enum_case_MotionNotify_value);

	zval enum_case_ButtonPress_value;
	ZVAL_LONG(&enum_case_ButtonPress_value, 2);
	zend_enum_add_case_cstr(class_entry, "ButtonPress", &enum_case_ButtonPress_value);

	zval enum_case_ButtonRelease_value;
	ZVAL_LONG(&enum_case_ButtonRelease_value, 3);
	zend_enum_add_case_cstr(class_entry, "ButtonRelease", &enum_case_ButtonRelease_value);

	zval enum_case_KeyPress_value;
	ZVAL_LONG(&enum_case_KeyPress_value, 4);
	zend_enum_add_case_cstr(class_entry, "KeyPress", &enum_case_KeyPress_value);

	zval enum_case_KeyRelease_value;
	ZVAL_LONG(&enum_case_KeyRelease_value, 5);
	zend_enum_add_case_cstr(class_entry, "KeyRelease", &enum_case_KeyRelease_value);

	zval enum_case_EnterNotify_value;
	ZVAL_LONG(&enum_case_EnterNotify_value, 6);
	zend_enum_add_case_cstr(class_entry, "EnterNotify", &enum_case_EnterNotify_value);

	zval enum_case_LeaveNotify_value;
	ZVAL_LONG(&enum_case_LeaveNotify_value, 7);
	zend_enum_add_case_cstr(class_entry, "LeaveNotify", &enum_case_LeaveNotify_value);

	zval enum_case_FocusChange_value;
	ZVAL_LONG(&enum_case_FocusChange_value, 8);
	zend_enum_add_case_cstr(class_entry, "FocusChange", &enum_case_FocusChange_value);

	zval enum_case_ProximityIn_value;
	ZVAL_LONG(&enum_case_ProximityIn_value, 9);
	zend_enum_add_case_cstr(class_entry, "ProximityIn", &enum_case_ProximityIn_value);

	zval enum_case_ProximityOut_value;
	ZVAL_LONG(&enum_case_ProximityOut_value, 10);
	zend_enum_add_case_cstr(class_entry, "ProximityOut", &enum_case_ProximityOut_value);

	zval enum_case_DragEnter_value;
	ZVAL_LONG(&enum_case_DragEnter_value, 11);
	zend_enum_add_case_cstr(class_entry, "DragEnter", &enum_case_DragEnter_value);

	zval enum_case_DragLeave_value;
	ZVAL_LONG(&enum_case_DragLeave_value, 12);
	zend_enum_add_case_cstr(class_entry, "DragLeave", &enum_case_DragLeave_value);

	zval enum_case_DragMotion_value;
	ZVAL_LONG(&enum_case_DragMotion_value, 13);
	zend_enum_add_case_cstr(class_entry, "DragMotion", &enum_case_DragMotion_value);

	zval enum_case_DropStart_value;
	ZVAL_LONG(&enum_case_DropStart_value, 14);
	zend_enum_add_case_cstr(class_entry, "DropStart", &enum_case_DropStart_value);

	zval enum_case_Scroll_value;
	ZVAL_LONG(&enum_case_Scroll_value, 15);
	zend_enum_add_case_cstr(class_entry, "Scroll", &enum_case_Scroll_value);

	zval enum_case_GrabBroken_value;
	ZVAL_LONG(&enum_case_GrabBroken_value, 16);
	zend_enum_add_case_cstr(class_entry, "GrabBroken", &enum_case_GrabBroken_value);

	zval enum_case_TouchBegin_value;
	ZVAL_LONG(&enum_case_TouchBegin_value, 17);
	zend_enum_add_case_cstr(class_entry, "TouchBegin", &enum_case_TouchBegin_value);

	zval enum_case_TouchUpdate_value;
	ZVAL_LONG(&enum_case_TouchUpdate_value, 18);
	zend_enum_add_case_cstr(class_entry, "TouchUpdate", &enum_case_TouchUpdate_value);

	zval enum_case_TouchEnd_value;
	ZVAL_LONG(&enum_case_TouchEnd_value, 19);
	zend_enum_add_case_cstr(class_entry, "TouchEnd", &enum_case_TouchEnd_value);

	zval enum_case_TouchCancel_value;
	ZVAL_LONG(&enum_case_TouchCancel_value, 20);
	zend_enum_add_case_cstr(class_entry, "TouchCancel", &enum_case_TouchCancel_value);

	zval enum_case_TouchpadSwipe_value;
	ZVAL_LONG(&enum_case_TouchpadSwipe_value, 21);
	zend_enum_add_case_cstr(class_entry, "TouchpadSwipe", &enum_case_TouchpadSwipe_value);

	zval enum_case_TouchpadPinch_value;
	ZVAL_LONG(&enum_case_TouchpadPinch_value, 22);
	zend_enum_add_case_cstr(class_entry, "TouchpadPinch", &enum_case_TouchpadPinch_value);

	zval enum_case_PadButtonPress_value;
	ZVAL_LONG(&enum_case_PadButtonPress_value, 23);
	zend_enum_add_case_cstr(class_entry, "PadButtonPress", &enum_case_PadButtonPress_value);

	zval enum_case_PadButtonRelease_value;
	ZVAL_LONG(&enum_case_PadButtonRelease_value, 24);
	zend_enum_add_case_cstr(class_entry, "PadButtonRelease", &enum_case_PadButtonRelease_value);

	zval enum_case_PadRing_value;
	ZVAL_LONG(&enum_case_PadRing_value, 25);
	zend_enum_add_case_cstr(class_entry, "PadRing", &enum_case_PadRing_value);

	zval enum_case_PadStrip_value;
	ZVAL_LONG(&enum_case_PadStrip_value, 26);
	zend_enum_add_case_cstr(class_entry, "PadStrip", &enum_case_PadStrip_value);

	zval enum_case_PadGroupMode_value;
	ZVAL_LONG(&enum_case_PadGroupMode_value, 27);
	zend_enum_add_case_cstr(class_entry, "PadGroupMode", &enum_case_PadGroupMode_value);

	zval enum_case_TouchpadHold_value;
	ZVAL_LONG(&enum_case_TouchpadHold_value, 28);
	zend_enum_add_case_cstr(class_entry, "TouchpadHold", &enum_case_TouchpadHold_value);

	zval enum_case_EventLast_value;
	ZVAL_LONG(&enum_case_EventLast_value, 29);
	zend_enum_add_case_cstr(class_entry, "EventLast", &enum_case_EventLast_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkKeyMatch(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GdkKeyMatch", IS_LONG, NULL);

	zval enum_case_None_value;
	ZVAL_LONG(&enum_case_None_value, 0);
	zend_enum_add_case_cstr(class_entry, "None", &enum_case_None_value);

	zval enum_case_Partial_value;
	ZVAL_LONG(&enum_case_Partial_value, 1);
	zend_enum_add_case_cstr(class_entry, "Partial", &enum_case_Partial_value);

	zval enum_case_Exact_value;
	ZVAL_LONG(&enum_case_Exact_value, 2);
	zend_enum_add_case_cstr(class_entry, "Exact", &enum_case_Exact_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkMemoryFormat(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GdkMemoryFormat", IS_LONG, NULL);

	zval enum_case_B8g8r8a8Premultiplied_value;
	ZVAL_LONG(&enum_case_B8g8r8a8Premultiplied_value, 0);
	zend_enum_add_case_cstr(class_entry, "B8g8r8a8Premultiplied", &enum_case_B8g8r8a8Premultiplied_value);

	zval enum_case_A8r8g8b8Premultiplied_value;
	ZVAL_LONG(&enum_case_A8r8g8b8Premultiplied_value, 1);
	zend_enum_add_case_cstr(class_entry, "A8r8g8b8Premultiplied", &enum_case_A8r8g8b8Premultiplied_value);

	zval enum_case_R8g8b8a8Premultiplied_value;
	ZVAL_LONG(&enum_case_R8g8b8a8Premultiplied_value, 2);
	zend_enum_add_case_cstr(class_entry, "R8g8b8a8Premultiplied", &enum_case_R8g8b8a8Premultiplied_value);

	zval enum_case_B8g8r8a8_value;
	ZVAL_LONG(&enum_case_B8g8r8a8_value, 3);
	zend_enum_add_case_cstr(class_entry, "B8g8r8a8", &enum_case_B8g8r8a8_value);

	zval enum_case_A8r8g8b8_value;
	ZVAL_LONG(&enum_case_A8r8g8b8_value, 4);
	zend_enum_add_case_cstr(class_entry, "A8r8g8b8", &enum_case_A8r8g8b8_value);

	zval enum_case_R8g8b8a8_value;
	ZVAL_LONG(&enum_case_R8g8b8a8_value, 5);
	zend_enum_add_case_cstr(class_entry, "R8g8b8a8", &enum_case_R8g8b8a8_value);

	zval enum_case_A8b8g8r8_value;
	ZVAL_LONG(&enum_case_A8b8g8r8_value, 6);
	zend_enum_add_case_cstr(class_entry, "A8b8g8r8", &enum_case_A8b8g8r8_value);

	zval enum_case_R8g8b8_value;
	ZVAL_LONG(&enum_case_R8g8b8_value, 7);
	zend_enum_add_case_cstr(class_entry, "R8g8b8", &enum_case_R8g8b8_value);

	zval enum_case_B8g8r8_value;
	ZVAL_LONG(&enum_case_B8g8r8_value, 8);
	zend_enum_add_case_cstr(class_entry, "B8g8r8", &enum_case_B8g8r8_value);

	zval enum_case_R16g16b16_value;
	ZVAL_LONG(&enum_case_R16g16b16_value, 9);
	zend_enum_add_case_cstr(class_entry, "R16g16b16", &enum_case_R16g16b16_value);

	zval enum_case_R16g16b16a16Premultiplied_value;
	ZVAL_LONG(&enum_case_R16g16b16a16Premultiplied_value, 10);
	zend_enum_add_case_cstr(class_entry, "R16g16b16a16Premultiplied", &enum_case_R16g16b16a16Premultiplied_value);

	zval enum_case_R16g16b16a16_value;
	ZVAL_LONG(&enum_case_R16g16b16a16_value, 11);
	zend_enum_add_case_cstr(class_entry, "R16g16b16a16", &enum_case_R16g16b16a16_value);

	zval enum_case_R16g16b16Float_value;
	ZVAL_LONG(&enum_case_R16g16b16Float_value, 12);
	zend_enum_add_case_cstr(class_entry, "R16g16b16Float", &enum_case_R16g16b16Float_value);

	zval enum_case_R16g16b16a16FloatPremultiplied_value;
	ZVAL_LONG(&enum_case_R16g16b16a16FloatPremultiplied_value, 13);
	zend_enum_add_case_cstr(class_entry, "R16g16b16a16FloatPremultiplied", &enum_case_R16g16b16a16FloatPremultiplied_value);

	zval enum_case_R16g16b16a16Float_value;
	ZVAL_LONG(&enum_case_R16g16b16a16Float_value, 14);
	zend_enum_add_case_cstr(class_entry, "R16g16b16a16Float", &enum_case_R16g16b16a16Float_value);

	zval enum_case_R32g32b32Float_value;
	ZVAL_LONG(&enum_case_R32g32b32Float_value, 15);
	zend_enum_add_case_cstr(class_entry, "R32g32b32Float", &enum_case_R32g32b32Float_value);

	zval enum_case_R32g32b32a32FloatPremultiplied_value;
	ZVAL_LONG(&enum_case_R32g32b32a32FloatPremultiplied_value, 16);
	zend_enum_add_case_cstr(class_entry, "R32g32b32a32FloatPremultiplied", &enum_case_R32g32b32a32FloatPremultiplied_value);

	zval enum_case_R32g32b32a32Float_value;
	ZVAL_LONG(&enum_case_R32g32b32a32Float_value, 17);
	zend_enum_add_case_cstr(class_entry, "R32g32b32a32Float", &enum_case_R32g32b32a32Float_value);

	zval enum_case_G8a8Premultiplied_value;
	ZVAL_LONG(&enum_case_G8a8Premultiplied_value, 18);
	zend_enum_add_case_cstr(class_entry, "G8a8Premultiplied", &enum_case_G8a8Premultiplied_value);

	zval enum_case_G8a8_value;
	ZVAL_LONG(&enum_case_G8a8_value, 19);
	zend_enum_add_case_cstr(class_entry, "G8a8", &enum_case_G8a8_value);

	zval enum_case_G8_value;
	ZVAL_LONG(&enum_case_G8_value, 20);
	zend_enum_add_case_cstr(class_entry, "G8", &enum_case_G8_value);

	zval enum_case_G16a16Premultiplied_value;
	ZVAL_LONG(&enum_case_G16a16Premultiplied_value, 21);
	zend_enum_add_case_cstr(class_entry, "G16a16Premultiplied", &enum_case_G16a16Premultiplied_value);

	zval enum_case_G16a16_value;
	ZVAL_LONG(&enum_case_G16a16_value, 22);
	zend_enum_add_case_cstr(class_entry, "G16a16", &enum_case_G16a16_value);

	zval enum_case_G16_value;
	ZVAL_LONG(&enum_case_G16_value, 23);
	zend_enum_add_case_cstr(class_entry, "G16", &enum_case_G16_value);

	zval enum_case_A8_value;
	ZVAL_LONG(&enum_case_A8_value, 24);
	zend_enum_add_case_cstr(class_entry, "A8", &enum_case_A8_value);

	zval enum_case_A16_value;
	ZVAL_LONG(&enum_case_A16_value, 25);
	zend_enum_add_case_cstr(class_entry, "A16", &enum_case_A16_value);

	zval enum_case_A16Float_value;
	ZVAL_LONG(&enum_case_A16Float_value, 26);
	zend_enum_add_case_cstr(class_entry, "A16Float", &enum_case_A16Float_value);

	zval enum_case_A32Float_value;
	ZVAL_LONG(&enum_case_A32Float_value, 27);
	zend_enum_add_case_cstr(class_entry, "A32Float", &enum_case_A32Float_value);

	zval enum_case_A8b8g8r8Premultiplied_value;
	ZVAL_LONG(&enum_case_A8b8g8r8Premultiplied_value, 28);
	zend_enum_add_case_cstr(class_entry, "A8b8g8r8Premultiplied", &enum_case_A8b8g8r8Premultiplied_value);

	zval enum_case_B8g8r8x8_value;
	ZVAL_LONG(&enum_case_B8g8r8x8_value, 29);
	zend_enum_add_case_cstr(class_entry, "B8g8r8x8", &enum_case_B8g8r8x8_value);

	zval enum_case_X8r8g8b8_value;
	ZVAL_LONG(&enum_case_X8r8g8b8_value, 30);
	zend_enum_add_case_cstr(class_entry, "X8r8g8b8", &enum_case_X8r8g8b8_value);

	zval enum_case_R8g8b8x8_value;
	ZVAL_LONG(&enum_case_R8g8b8x8_value, 31);
	zend_enum_add_case_cstr(class_entry, "R8g8b8x8", &enum_case_R8g8b8x8_value);

	zval enum_case_X8b8g8r8_value;
	ZVAL_LONG(&enum_case_X8b8g8r8_value, 32);
	zend_enum_add_case_cstr(class_entry, "X8b8g8r8", &enum_case_X8b8g8r8_value);

	zval enum_case_NFormats_value;
	ZVAL_LONG(&enum_case_NFormats_value, 33);
	zend_enum_add_case_cstr(class_entry, "NFormats", &enum_case_NFormats_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkModifierType(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkModifierType", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_NO_MODIFIER_MASK_value;
	ZVAL_LONG(&const_NO_MODIFIER_MASK_value, 0);
	zend_string *const_NO_MODIFIER_MASK_name = zend_string_init_interned("NO_MODIFIER_MASK", sizeof("NO_MODIFIER_MASK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NO_MODIFIER_MASK_name, &const_NO_MODIFIER_MASK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NO_MODIFIER_MASK_name);

	zval const_SHIFT_MASK_value;
	ZVAL_LONG(&const_SHIFT_MASK_value, 1);
	zend_string *const_SHIFT_MASK_name = zend_string_init_interned("SHIFT_MASK", sizeof("SHIFT_MASK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_SHIFT_MASK_name, &const_SHIFT_MASK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_SHIFT_MASK_name);

	zval const_LOCK_MASK_value;
	ZVAL_LONG(&const_LOCK_MASK_value, 2);
	zend_string *const_LOCK_MASK_name = zend_string_init_interned("LOCK_MASK", sizeof("LOCK_MASK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_LOCK_MASK_name, &const_LOCK_MASK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_LOCK_MASK_name);

	zval const_CONTROL_MASK_value;
	ZVAL_LONG(&const_CONTROL_MASK_value, 4);
	zend_string *const_CONTROL_MASK_name = zend_string_init_interned("CONTROL_MASK", sizeof("CONTROL_MASK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_CONTROL_MASK_name, &const_CONTROL_MASK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_CONTROL_MASK_name);

	zval const_ALT_MASK_value;
	ZVAL_LONG(&const_ALT_MASK_value, 8);
	zend_string *const_ALT_MASK_name = zend_string_init_interned("ALT_MASK", sizeof("ALT_MASK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_ALT_MASK_name, &const_ALT_MASK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_ALT_MASK_name);

	zval const_BUTTON1_MASK_value;
	ZVAL_LONG(&const_BUTTON1_MASK_value, 256);
	zend_string *const_BUTTON1_MASK_name = zend_string_init_interned("BUTTON1_MASK", sizeof("BUTTON1_MASK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_BUTTON1_MASK_name, &const_BUTTON1_MASK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_BUTTON1_MASK_name);

	zval const_BUTTON2_MASK_value;
	ZVAL_LONG(&const_BUTTON2_MASK_value, 512);
	zend_string *const_BUTTON2_MASK_name = zend_string_init_interned("BUTTON2_MASK", sizeof("BUTTON2_MASK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_BUTTON2_MASK_name, &const_BUTTON2_MASK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_BUTTON2_MASK_name);

	zval const_BUTTON3_MASK_value;
	ZVAL_LONG(&const_BUTTON3_MASK_value, 1024);
	zend_string *const_BUTTON3_MASK_name = zend_string_init_interned("BUTTON3_MASK", sizeof("BUTTON3_MASK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_BUTTON3_MASK_name, &const_BUTTON3_MASK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_BUTTON3_MASK_name);

	zval const_BUTTON4_MASK_value;
	ZVAL_LONG(&const_BUTTON4_MASK_value, 2048);
	zend_string *const_BUTTON4_MASK_name = zend_string_init_interned("BUTTON4_MASK", sizeof("BUTTON4_MASK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_BUTTON4_MASK_name, &const_BUTTON4_MASK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_BUTTON4_MASK_name);

	zval const_BUTTON5_MASK_value;
	ZVAL_LONG(&const_BUTTON5_MASK_value, 4096);
	zend_string *const_BUTTON5_MASK_name = zend_string_init_interned("BUTTON5_MASK", sizeof("BUTTON5_MASK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_BUTTON5_MASK_name, &const_BUTTON5_MASK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_BUTTON5_MASK_name);

	zval const_SUPER_MASK_value;
	ZVAL_LONG(&const_SUPER_MASK_value, 67108864);
	zend_string *const_SUPER_MASK_name = zend_string_init_interned("SUPER_MASK", sizeof("SUPER_MASK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_SUPER_MASK_name, &const_SUPER_MASK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_SUPER_MASK_name);

	zval const_HYPER_MASK_value;
	ZVAL_LONG(&const_HYPER_MASK_value, 134217728);
	zend_string *const_HYPER_MASK_name = zend_string_init_interned("HYPER_MASK", sizeof("HYPER_MASK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_HYPER_MASK_name, &const_HYPER_MASK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_HYPER_MASK_name);

	zval const_META_MASK_value;
	ZVAL_LONG(&const_META_MASK_value, 268435456);
	zend_string *const_META_MASK_name = zend_string_init_interned("META_MASK", sizeof("META_MASK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_META_MASK_name, &const_META_MASK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_META_MASK_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkNotifyType(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GdkNotifyType", IS_LONG, NULL);

	zval enum_case_Ancestor_value;
	ZVAL_LONG(&enum_case_Ancestor_value, 0);
	zend_enum_add_case_cstr(class_entry, "Ancestor", &enum_case_Ancestor_value);

	zval enum_case_Virtual_value;
	ZVAL_LONG(&enum_case_Virtual_value, 1);
	zend_enum_add_case_cstr(class_entry, "Virtual", &enum_case_Virtual_value);

	zval enum_case_Inferior_value;
	ZVAL_LONG(&enum_case_Inferior_value, 2);
	zend_enum_add_case_cstr(class_entry, "Inferior", &enum_case_Inferior_value);

	zval enum_case_Nonlinear_value;
	ZVAL_LONG(&enum_case_Nonlinear_value, 3);
	zend_enum_add_case_cstr(class_entry, "Nonlinear", &enum_case_Nonlinear_value);

	zval enum_case_NonlinearVirtual_value;
	ZVAL_LONG(&enum_case_NonlinearVirtual_value, 4);
	zend_enum_add_case_cstr(class_entry, "NonlinearVirtual", &enum_case_NonlinearVirtual_value);

	zval enum_case_Unknown_value;
	ZVAL_LONG(&enum_case_Unknown_value, 5);
	zend_enum_add_case_cstr(class_entry, "Unknown", &enum_case_Unknown_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkPaintable(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkPaintable", class_Gtk4_GdkPaintable_methods);
	class_entry = zend_register_internal_interface(&ce);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkPaintableObject(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GdkPaintable)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkPaintableObject", class_Gtk4_GdkPaintableObject_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GdkPaintable);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkPaintableFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkPaintableFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_SIZE_value;
	ZVAL_LONG(&const_SIZE_value, 1);
	zend_string *const_SIZE_name = zend_string_init_interned("SIZE", sizeof("SIZE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_SIZE_name, &const_SIZE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_SIZE_name);

	zval const_CONTENTS_value;
	ZVAL_LONG(&const_CONTENTS_value, 2);
	zend_string *const_CONTENTS_name = zend_string_init_interned("CONTENTS", sizeof("CONTENTS") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_CONTENTS_name, &const_CONTENTS_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_CONTENTS_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkScrollDirection(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GdkScrollDirection", IS_LONG, NULL);

	zval enum_case_Up_value;
	ZVAL_LONG(&enum_case_Up_value, 0);
	zend_enum_add_case_cstr(class_entry, "Up", &enum_case_Up_value);

	zval enum_case_Down_value;
	ZVAL_LONG(&enum_case_Down_value, 1);
	zend_enum_add_case_cstr(class_entry, "Down", &enum_case_Down_value);

	zval enum_case_Left_value;
	ZVAL_LONG(&enum_case_Left_value, 2);
	zend_enum_add_case_cstr(class_entry, "Left", &enum_case_Left_value);

	zval enum_case_Right_value;
	ZVAL_LONG(&enum_case_Right_value, 3);
	zend_enum_add_case_cstr(class_entry, "Right", &enum_case_Right_value);

	zval enum_case_Smooth_value;
	ZVAL_LONG(&enum_case_Smooth_value, 4);
	zend_enum_add_case_cstr(class_entry, "Smooth", &enum_case_Smooth_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkScrollUnit(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GdkScrollUnit", IS_LONG, NULL);

	zval enum_case_Wheel_value;
	ZVAL_LONG(&enum_case_Wheel_value, 0);
	zend_enum_add_case_cstr(class_entry, "Wheel", &enum_case_Wheel_value);

	zval enum_case_Surface_value;
	ZVAL_LONG(&enum_case_Surface_value, 1);
	zend_enum_add_case_cstr(class_entry, "Surface", &enum_case_Surface_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkTexture(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GdkPaintable)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkTexture", class_Gtk4_GdkTexture_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GdkPaintable);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkTouchpadGesturePhase(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GdkTouchpadGesturePhase", IS_LONG, NULL);

	zval enum_case_Begin_value;
	ZVAL_LONG(&enum_case_Begin_value, 0);
	zend_enum_add_case_cstr(class_entry, "Begin", &enum_case_Begin_value);

	zval enum_case_Update_value;
	ZVAL_LONG(&enum_case_Update_value, 1);
	zend_enum_add_case_cstr(class_entry, "Update", &enum_case_Update_value);

	zval enum_case_End_value;
	ZVAL_LONG(&enum_case_End_value, 2);
	zend_enum_add_case_cstr(class_entry, "End", &enum_case_End_value);

	zval enum_case_Cancel_value;
	ZVAL_LONG(&enum_case_Cancel_value, 3);
	zend_enum_add_case_cstr(class_entry, "Cancel", &enum_case_Cancel_value);

	return class_entry;
}
