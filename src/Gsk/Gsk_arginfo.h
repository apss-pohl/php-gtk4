/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: acd1202c5997605b76b8da7dac7f0d419d4c93e2 */

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
