/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 722c391e6af122cf1ea143db1ec43a9587c8b7db */

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GdkTexture___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkTexture_new_from_bytes, 0, 1, Gtk4\\GdkTexture, 0)
	ZEND_ARG_TYPE_INFO(0, bytes, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkTexture_new_from_filename, 0, 1, Gtk4\\GdkTexture, 0)
	ZEND_ARG_TYPE_INFO(0, path, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkTexture_get_format, 0, 0, Gtk4\\GdkMemoryFormat, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkTexture_get_height, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkTexture_get_width arginfo_class_Gtk4_GdkTexture_get_height

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkTexture_save_to_png, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, filename, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkTexture_save_to_png_bytes, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkTexture_save_to_tiff arginfo_class_Gtk4_GdkTexture_save_to_png

#define arginfo_class_Gtk4_GdkTexture_save_to_tiff_bytes arginfo_class_Gtk4_GdkTexture_save_to_png_bytes

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

static const zend_function_entry class_Gtk4_GdkTexture_methods[] = {
	ZEND_ME(Gtk4_GdkTexture, __construct, arginfo_class_Gtk4_GdkTexture___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, new_from_bytes, arginfo_class_Gtk4_GdkTexture_new_from_bytes, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkTexture, new_from_filename, arginfo_class_Gtk4_GdkTexture_new_from_filename, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkTexture, get_format, arginfo_class_Gtk4_GdkTexture_get_format, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, get_height, arginfo_class_Gtk4_GdkTexture_get_height, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, get_width, arginfo_class_Gtk4_GdkTexture_get_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, save_to_png, arginfo_class_Gtk4_GdkTexture_save_to_png, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, save_to_png_bytes, arginfo_class_Gtk4_GdkTexture_save_to_png_bytes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, save_to_tiff, arginfo_class_Gtk4_GdkTexture_save_to_tiff, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, save_to_tiff_bytes, arginfo_class_Gtk4_GdkTexture_save_to_tiff_bytes, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

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

static zend_class_entry *register_class_Gtk4_GdkTexture(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkTexture", class_Gtk4_GdkTexture_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}
