/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 028cba2c29021c8aef707cc0354e98d99105f496 */

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GObject_connect, 0, 2, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, signal, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, handler, IS_CALLABLE, 0)
	ZEND_ARG_VARIADIC_TYPE_INFO(0, userData, IS_MIXED, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GObject_connect_after arginfo_class_Gtk4_GObject_connect

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GObject_handler_disconnect, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, handlerId, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GObject_get_property, 0, 1, IS_MIXED, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GObject_set_property, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, value, IS_MIXED, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_Gtk_init, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_Gtk_main, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_Gtk_main_quit arginfo_class_Gtk4_Gtk_main

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_Gtk_set_exception_handler, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, handler, IS_CALLABLE, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkWindow___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_title, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, title, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_get_title, 0, 0, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_default_size, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_child, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GObject, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWindow_present arginfo_class_Gtk4_Gtk_main

#define arginfo_class_Gtk4_GtkWindow_close arginfo_class_Gtk4_Gtk_main

#define arginfo_class_Gtk4_GtkWindow_destroy arginfo_class_Gtk4_Gtk_main

ZEND_METHOD(Gtk4_GObject, connect);
ZEND_METHOD(Gtk4_GObject, connect_after);
ZEND_METHOD(Gtk4_GObject, handler_disconnect);
ZEND_METHOD(Gtk4_GObject, get_property);
ZEND_METHOD(Gtk4_GObject, set_property);
ZEND_METHOD(Gtk4_Gtk, init);
ZEND_METHOD(Gtk4_Gtk, main);
ZEND_METHOD(Gtk4_Gtk, main_quit);
ZEND_METHOD(Gtk4_Gtk, set_exception_handler);
ZEND_METHOD(Gtk4_GtkWindow, __construct);
ZEND_METHOD(Gtk4_GtkWindow, set_title);
ZEND_METHOD(Gtk4_GtkWindow, get_title);
ZEND_METHOD(Gtk4_GtkWindow, set_default_size);
ZEND_METHOD(Gtk4_GtkWindow, set_child);
ZEND_METHOD(Gtk4_GtkWindow, present);
ZEND_METHOD(Gtk4_GtkWindow, close);
ZEND_METHOD(Gtk4_GtkWindow, destroy);

static const zend_function_entry class_Gtk4_GObject_methods[] = {
	ZEND_ME(Gtk4_GObject, connect, arginfo_class_Gtk4_GObject_connect, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GObject, connect_after, arginfo_class_Gtk4_GObject_connect_after, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GObject, handler_disconnect, arginfo_class_Gtk4_GObject_handler_disconnect, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GObject, get_property, arginfo_class_Gtk4_GObject_get_property, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GObject, set_property, arginfo_class_Gtk4_GObject_set_property, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_Gtk_methods[] = {
	ZEND_ME(Gtk4_Gtk, init, arginfo_class_Gtk4_Gtk_init, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, main, arginfo_class_Gtk4_Gtk_main, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, main_quit, arginfo_class_Gtk4_Gtk_main_quit, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, set_exception_handler, arginfo_class_Gtk4_Gtk_set_exception_handler, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkWindow_methods[] = {
	ZEND_ME(Gtk4_GtkWindow, __construct, arginfo_class_Gtk4_GtkWindow___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_title, arginfo_class_Gtk4_GtkWindow_set_title, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_title, arginfo_class_Gtk4_GtkWindow_get_title, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_default_size, arginfo_class_Gtk4_GtkWindow_set_default_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_child, arginfo_class_Gtk4_GtkWindow_set_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, present, arginfo_class_Gtk4_GtkWindow_present, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, close, arginfo_class_Gtk4_GtkWindow_close, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, destroy, arginfo_class_Gtk4_GtkWindow_destroy, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static void register_gtk4_symbols(int module_number)
{
	REGISTER_STRING_CONSTANT("Gtk4\\VERSION", "0.1.0", CONST_PERSISTENT);
	REGISTER_STRING_CONSTANT("Gtk4\\BUILD_INFO", PHPGTK_BUILD_INFO, CONST_PERSISTENT);
	REGISTER_STRING_CONSTANT("Gtk4\\FEATURES", PHPGTK_BUILD_FEATURES, CONST_PERSISTENT);
}

static zend_class_entry *register_class_Gtk4_GObject(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GObject", class_Gtk4_GObject_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_Gtk(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "Gtk", class_Gtk4_Gtk_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkWindow(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkWindow", class_Gtk4_GtkWindow_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}
