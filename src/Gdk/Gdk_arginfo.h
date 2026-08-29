/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 23d005a7fb0d8401482b03b1520b15c6c4799e23 */

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

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkTexture_compute_concrete_size, 0, 4, IS_ARRAY, 0)
	ZEND_ARG_TYPE_INFO(0, specified_width, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, specified_height, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, default_width, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, default_height, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

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
ZEND_METHOD(Gtk4_GdkPaintable, compute_concrete_size);
ZEND_METHOD(Gtk4_GdkPaintable, invalidate_contents);
ZEND_METHOD(Gtk4_GdkPaintable, invalidate_size);

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

static zend_class_entry *register_class_Gtk4_GdkPaintable(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkPaintable", class_Gtk4_GdkPaintable_methods);
	class_entry = zend_register_internal_interface(&ce);

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

static zend_class_entry *register_class_Gtk4_GdkTexture(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GdkPaintable)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkTexture", class_Gtk4_GdkTexture_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GdkPaintable);

	return class_entry;
}
