/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: ffdd2bec4e60279e0cb8bff24668c675cceeeafa */

static zend_class_entry *register_class_Gtk4_PangoEllipsizeMode(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\PangoEllipsizeMode", IS_LONG, NULL);

	zval enum_case_None_value;
	ZVAL_LONG(&enum_case_None_value, 0);
	zend_enum_add_case_cstr(class_entry, "None", &enum_case_None_value);

	zval enum_case_Start_value;
	ZVAL_LONG(&enum_case_Start_value, 1);
	zend_enum_add_case_cstr(class_entry, "Start", &enum_case_Start_value);

	zval enum_case_Middle_value;
	ZVAL_LONG(&enum_case_Middle_value, 2);
	zend_enum_add_case_cstr(class_entry, "Middle", &enum_case_Middle_value);

	zval enum_case_End_value;
	ZVAL_LONG(&enum_case_End_value, 3);
	zend_enum_add_case_cstr(class_entry, "End", &enum_case_End_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoWrapMode(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\PangoWrapMode", IS_LONG, NULL);

	zval enum_case_Word_value;
	ZVAL_LONG(&enum_case_Word_value, 0);
	zend_enum_add_case_cstr(class_entry, "Word", &enum_case_Word_value);

	zval enum_case_Char_value;
	ZVAL_LONG(&enum_case_Char_value, 1);
	zend_enum_add_case_cstr(class_entry, "Char", &enum_case_Char_value);

	zval enum_case_WordChar_value;
	ZVAL_LONG(&enum_case_WordChar_value, 2);
	zend_enum_add_case_cstr(class_entry, "WordChar", &enum_case_WordChar_value);

	return class_entry;
}
