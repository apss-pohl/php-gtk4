/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 753e03c3e60e2d9333d4e998133a1633ecef6b67 */

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GObject_connect, 0, 2, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, signal, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, handler, IS_CALLABLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GObject_connect_after arginfo_class_Gtk4_GObject_connect

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GObject_emit, 0, 1, IS_MIXED, 0)
	ZEND_ARG_TYPE_INFO(0, signal, IS_STRING, 0)
	ZEND_ARG_VARIADIC_TYPE_INFO(0, args, IS_MIXED, 0)
ZEND_END_ARG_INFO()

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

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GParamSpec_get_name, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GParamSpec_get_nick, 0, 0, IS_STRING, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GParamSpec_get_blurb arginfo_class_Gtk4_GParamSpec_get_nick

#define arginfo_class_Gtk4_GParamSpec_get_value_type arginfo_class_Gtk4_GParamSpec_get_name

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GParamSpec_get_flags, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GParamSpec_is_readable, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GParamSpec_is_writable arginfo_class_Gtk4_GParamSpec_is_readable

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GParamSpec_get_default_value, 0, 0, IS_MIXED, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_Gtk_init arginfo_class_Gtk4_GParamSpec_is_readable

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_Gtk_set_exception_handler, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, handler, IS_CALLABLE, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_Gtk_set_exception_mode, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, mode, Gtk4\\ExceptionMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_Gtk_get_exception_mode, 0, 0, Gtk4\\ExceptionMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GLib_idle_add, 0, 1, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, callback, IS_CALLABLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GLib_timeout_add, 0, 2, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, intervalMs, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, callback, IS_CALLABLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GLib_source_remove, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, sourceId, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GMainLoop___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMainLoop_run, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GMainLoop_quit arginfo_class_Gtk4_GMainLoop_run

#define arginfo_class_Gtk4_GMainLoop_is_running arginfo_class_Gtk4_GParamSpec_is_readable

#define arginfo_class_Gtk4_GAction_get_name arginfo_class_Gtk4_GParamSpec_get_name

#define arginfo_class_Gtk4_GAction_get_enabled arginfo_class_Gtk4_GParamSpec_is_readable

#define arginfo_class_Gtk4_GAction_get_parameter_type arginfo_class_Gtk4_GParamSpec_get_nick

#define arginfo_class_Gtk4_GAction_get_state arginfo_class_Gtk4_GParamSpec_get_default_value

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionMap_add_action, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, action, Gtk4\\GAction, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionMap_remove_action, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GActionMap_lookup_action, 0, 1, Gtk4\\GAction, 1)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionGroup_has_action, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionGroup_list_actions, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionGroup_activate_action, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameter, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GSimpleAction___construct, 0, 0, 1)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameterType, IS_STRING, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, state, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GSimpleAction_get_name arginfo_class_Gtk4_GParamSpec_get_name

#define arginfo_class_Gtk4_GSimpleAction_get_enabled arginfo_class_Gtk4_GParamSpec_is_readable

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GSimpleAction_set_enabled, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, enabled, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GSimpleAction_get_parameter_type arginfo_class_Gtk4_GParamSpec_get_nick

#define arginfo_class_Gtk4_GSimpleAction_get_state arginfo_class_Gtk4_GParamSpec_get_default_value

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GSimpleAction_set_state, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, state, IS_MIXED, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GSimpleAction_activate, 0, 0, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameter, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkApplication___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, applicationId, IS_STRING, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, flags, IS_LONG, 0, "0")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_run, 0, 0, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, argv, IS_ARRAY, 0, "[]")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkApplication_quit arginfo_class_Gtk4_GMainLoop_run

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_add_window, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, window, Gtk4\\GtkWindow, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkApplication_get_active_window, 0, 0, Gtk4\\GtkWindow, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkApplication_get_application_id arginfo_class_Gtk4_GParamSpec_get_nick

#define arginfo_class_Gtk4_GtkApplication_add_action arginfo_class_Gtk4_GActionMap_add_action

#define arginfo_class_Gtk4_GtkApplication_remove_action arginfo_class_Gtk4_GActionMap_remove_action

#define arginfo_class_Gtk4_GtkApplication_lookup_action arginfo_class_Gtk4_GActionMap_lookup_action

#define arginfo_class_Gtk4_GtkApplication_has_action arginfo_class_Gtk4_GActionGroup_has_action

#define arginfo_class_Gtk4_GtkApplication_list_actions arginfo_class_Gtk4_GActionGroup_list_actions

#define arginfo_class_Gtk4_GtkApplication_activate_action arginfo_class_Gtk4_GActionGroup_activate_action

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GdkRGBA___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, css, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkRGBA_parse, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, css, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkRGBA_to_string arginfo_class_Gtk4_GParamSpec_get_name

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkRGBA_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, other, Gtk4\\GdkRGBA, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkRGBA_is_opaque arginfo_class_Gtk4_GParamSpec_is_readable

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GdkRectangle___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, x, IS_LONG, 0, "0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, y, IS_LONG, 0, "0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, width, IS_LONG, 0, "0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, height, IS_LONG, 0, "0")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkRectangle_intersect, 0, 1, Gtk4\\GdkRectangle, 1)
	ZEND_ARG_OBJ_INFO(0, other, Gtk4\\GdkRectangle, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkRectangle_union, 0, 1, Gtk4\\GdkRectangle, 0)
	ZEND_ARG_OBJ_INFO(0, other, Gtk4\\GdkRectangle, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkRectangle_contains_point, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, x, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkRectangle_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, other, Gtk4\\GdkRectangle, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_show arginfo_class_Gtk4_GMainLoop_run

#define arginfo_class_Gtk4_GtkWidget_hide arginfo_class_Gtk4_GMainLoop_run

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_visible, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, visible, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_get_visible arginfo_class_Gtk4_GParamSpec_is_readable

#define arginfo_class_Gtk4_GtkWidget_is_visible arginfo_class_Gtk4_GParamSpec_is_readable

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_sensitive, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, sensitive, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_get_sensitive arginfo_class_Gtk4_GParamSpec_is_readable

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_size_request, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkWidget_get_parent, 0, 0, Gtk4\\GtkWidget, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_get_root arginfo_class_Gtk4_GtkWidget_get_parent

#define arginfo_class_Gtk4_GtkWidget_grab_focus arginfo_class_Gtk4_GParamSpec_is_readable

#define arginfo_class_Gtk4_GtkWidget_activate arginfo_class_Gtk4_GParamSpec_is_readable

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_tooltip_text, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, text, IS_STRING, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_get_tooltip_text arginfo_class_Gtk4_GParamSpec_get_nick

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_name, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_get_name arginfo_class_Gtk4_GParamSpec_get_nick

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_add_css_class, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, cssClass, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_remove_css_class arginfo_class_Gtk4_GtkWidget_add_css_class

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_has_css_class, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, cssClass, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_get_css_classes arginfo_class_Gtk4_GActionGroup_list_actions

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_css_classes, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, classes, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_activate_action, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameter, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_queue_draw arginfo_class_Gtk4_GMainLoop_run

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkButton___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, label, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkButton_set_label, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, label, IS_STRING, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkButton_get_label arginfo_class_Gtk4_GParamSpec_get_nick

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkButton_set_child, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GtkWidget, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkButton_get_child arginfo_class_Gtk4_GtkWidget_get_parent

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkLabel___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, text, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_text, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, text, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkLabel_get_text arginfo_class_Gtk4_GParamSpec_get_name

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_markup, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, markup, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_selectable, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, selectable, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkLabel_get_selectable arginfo_class_Gtk4_GParamSpec_is_readable

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkWindow___construct, 0, 0, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, application, Gtk4\\GtkApplication, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_application, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, application, Gtk4\\GtkApplication, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkWindow_get_application, 0, 0, Gtk4\\GtkApplication, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_title, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, title, IS_STRING, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWindow_get_title arginfo_class_Gtk4_GParamSpec_get_nick

#define arginfo_class_Gtk4_GtkWindow_set_default_size arginfo_class_Gtk4_GtkWidget_set_size_request

#define arginfo_class_Gtk4_GtkWindow_set_child arginfo_class_Gtk4_GtkButton_set_child

#define arginfo_class_Gtk4_GtkWindow_get_child arginfo_class_Gtk4_GtkWidget_get_parent

#define arginfo_class_Gtk4_GtkWindow_present arginfo_class_Gtk4_GMainLoop_run

#define arginfo_class_Gtk4_GtkWindow_close arginfo_class_Gtk4_GMainLoop_run

#define arginfo_class_Gtk4_GtkWindow_destroy arginfo_class_Gtk4_GMainLoop_run

ZEND_METHOD(Gtk4_GObject, connect);
ZEND_METHOD(Gtk4_GObject, connect_after);
ZEND_METHOD(Gtk4_GObject, emit);
ZEND_METHOD(Gtk4_GObject, handler_disconnect);
ZEND_METHOD(Gtk4_GObject, get_property);
ZEND_METHOD(Gtk4_GObject, set_property);
ZEND_METHOD(Gtk4_GParamSpec, get_name);
ZEND_METHOD(Gtk4_GParamSpec, get_nick);
ZEND_METHOD(Gtk4_GParamSpec, get_blurb);
ZEND_METHOD(Gtk4_GParamSpec, get_value_type);
ZEND_METHOD(Gtk4_GParamSpec, get_flags);
ZEND_METHOD(Gtk4_GParamSpec, is_readable);
ZEND_METHOD(Gtk4_GParamSpec, is_writable);
ZEND_METHOD(Gtk4_GParamSpec, get_default_value);
ZEND_METHOD(Gtk4_Gtk, init);
ZEND_METHOD(Gtk4_Gtk, set_exception_handler);
ZEND_METHOD(Gtk4_Gtk, set_exception_mode);
ZEND_METHOD(Gtk4_Gtk, get_exception_mode);
ZEND_METHOD(Gtk4_GLib, idle_add);
ZEND_METHOD(Gtk4_GLib, timeout_add);
ZEND_METHOD(Gtk4_GLib, source_remove);
ZEND_METHOD(Gtk4_GMainLoop, __construct);
ZEND_METHOD(Gtk4_GMainLoop, run);
ZEND_METHOD(Gtk4_GMainLoop, quit);
ZEND_METHOD(Gtk4_GMainLoop, is_running);
ZEND_METHOD(Gtk4_GSimpleAction, __construct);
ZEND_METHOD(Gtk4_GSimpleAction, get_name);
ZEND_METHOD(Gtk4_GSimpleAction, get_enabled);
ZEND_METHOD(Gtk4_GSimpleAction, set_enabled);
ZEND_METHOD(Gtk4_GSimpleAction, get_parameter_type);
ZEND_METHOD(Gtk4_GSimpleAction, get_state);
ZEND_METHOD(Gtk4_GSimpleAction, set_state);
ZEND_METHOD(Gtk4_GSimpleAction, activate);
ZEND_METHOD(Gtk4_GtkApplication, __construct);
ZEND_METHOD(Gtk4_GtkApplication, run);
ZEND_METHOD(Gtk4_GtkApplication, quit);
ZEND_METHOD(Gtk4_GtkApplication, add_window);
ZEND_METHOD(Gtk4_GtkApplication, get_active_window);
ZEND_METHOD(Gtk4_GtkApplication, get_application_id);
ZEND_METHOD(Gtk4_GtkApplication, add_action);
ZEND_METHOD(Gtk4_GtkApplication, remove_action);
ZEND_METHOD(Gtk4_GtkApplication, lookup_action);
ZEND_METHOD(Gtk4_GtkApplication, has_action);
ZEND_METHOD(Gtk4_GtkApplication, list_actions);
ZEND_METHOD(Gtk4_GtkApplication, activate_action);
ZEND_METHOD(Gtk4_GdkRGBA, __construct);
ZEND_METHOD(Gtk4_GdkRGBA, parse);
ZEND_METHOD(Gtk4_GdkRGBA, to_string);
ZEND_METHOD(Gtk4_GdkRGBA, equal);
ZEND_METHOD(Gtk4_GdkRGBA, is_opaque);
ZEND_METHOD(Gtk4_GdkRectangle, __construct);
ZEND_METHOD(Gtk4_GdkRectangle, intersect);
ZEND_METHOD(Gtk4_GdkRectangle, union);
ZEND_METHOD(Gtk4_GdkRectangle, contains_point);
ZEND_METHOD(Gtk4_GdkRectangle, equal);
ZEND_METHOD(Gtk4_GtkWidget, show);
ZEND_METHOD(Gtk4_GtkWidget, hide);
ZEND_METHOD(Gtk4_GtkWidget, set_visible);
ZEND_METHOD(Gtk4_GtkWidget, get_visible);
ZEND_METHOD(Gtk4_GtkWidget, is_visible);
ZEND_METHOD(Gtk4_GtkWidget, set_sensitive);
ZEND_METHOD(Gtk4_GtkWidget, get_sensitive);
ZEND_METHOD(Gtk4_GtkWidget, set_size_request);
ZEND_METHOD(Gtk4_GtkWidget, get_parent);
ZEND_METHOD(Gtk4_GtkWidget, get_root);
ZEND_METHOD(Gtk4_GtkWidget, grab_focus);
ZEND_METHOD(Gtk4_GtkWidget, activate);
ZEND_METHOD(Gtk4_GtkWidget, set_tooltip_text);
ZEND_METHOD(Gtk4_GtkWidget, get_tooltip_text);
ZEND_METHOD(Gtk4_GtkWidget, set_name);
ZEND_METHOD(Gtk4_GtkWidget, get_name);
ZEND_METHOD(Gtk4_GtkWidget, add_css_class);
ZEND_METHOD(Gtk4_GtkWidget, remove_css_class);
ZEND_METHOD(Gtk4_GtkWidget, has_css_class);
ZEND_METHOD(Gtk4_GtkWidget, get_css_classes);
ZEND_METHOD(Gtk4_GtkWidget, set_css_classes);
ZEND_METHOD(Gtk4_GtkWidget, activate_action);
ZEND_METHOD(Gtk4_GtkWidget, queue_draw);
ZEND_METHOD(Gtk4_GtkButton, __construct);
ZEND_METHOD(Gtk4_GtkButton, set_label);
ZEND_METHOD(Gtk4_GtkButton, get_label);
ZEND_METHOD(Gtk4_GtkButton, set_child);
ZEND_METHOD(Gtk4_GtkButton, get_child);
ZEND_METHOD(Gtk4_GtkLabel, __construct);
ZEND_METHOD(Gtk4_GtkLabel, set_text);
ZEND_METHOD(Gtk4_GtkLabel, get_text);
ZEND_METHOD(Gtk4_GtkLabel, set_markup);
ZEND_METHOD(Gtk4_GtkLabel, set_selectable);
ZEND_METHOD(Gtk4_GtkLabel, get_selectable);
ZEND_METHOD(Gtk4_GtkWindow, __construct);
ZEND_METHOD(Gtk4_GtkWindow, set_application);
ZEND_METHOD(Gtk4_GtkWindow, get_application);
ZEND_METHOD(Gtk4_GtkWindow, set_title);
ZEND_METHOD(Gtk4_GtkWindow, get_title);
ZEND_METHOD(Gtk4_GtkWindow, set_default_size);
ZEND_METHOD(Gtk4_GtkWindow, set_child);
ZEND_METHOD(Gtk4_GtkWindow, get_child);
ZEND_METHOD(Gtk4_GtkWindow, present);
ZEND_METHOD(Gtk4_GtkWindow, close);
ZEND_METHOD(Gtk4_GtkWindow, destroy);

static const zend_function_entry class_Gtk4_GObject_methods[] = {
	ZEND_ME(Gtk4_GObject, connect, arginfo_class_Gtk4_GObject_connect, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GObject, connect_after, arginfo_class_Gtk4_GObject_connect_after, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GObject, emit, arginfo_class_Gtk4_GObject_emit, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GObject, handler_disconnect, arginfo_class_Gtk4_GObject_handler_disconnect, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GObject, get_property, arginfo_class_Gtk4_GObject_get_property, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GObject, set_property, arginfo_class_Gtk4_GObject_set_property, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GParamSpec_methods[] = {
	ZEND_ME(Gtk4_GParamSpec, get_name, arginfo_class_Gtk4_GParamSpec_get_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GParamSpec, get_nick, arginfo_class_Gtk4_GParamSpec_get_nick, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GParamSpec, get_blurb, arginfo_class_Gtk4_GParamSpec_get_blurb, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GParamSpec, get_value_type, arginfo_class_Gtk4_GParamSpec_get_value_type, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GParamSpec, get_flags, arginfo_class_Gtk4_GParamSpec_get_flags, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GParamSpec, is_readable, arginfo_class_Gtk4_GParamSpec_is_readable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GParamSpec, is_writable, arginfo_class_Gtk4_GParamSpec_is_writable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GParamSpec, get_default_value, arginfo_class_Gtk4_GParamSpec_get_default_value, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_Gtk_methods[] = {
	ZEND_ME(Gtk4_Gtk, init, arginfo_class_Gtk4_Gtk_init, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, set_exception_handler, arginfo_class_Gtk4_Gtk_set_exception_handler, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, set_exception_mode, arginfo_class_Gtk4_Gtk_set_exception_mode, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, get_exception_mode, arginfo_class_Gtk4_Gtk_get_exception_mode, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GLib_methods[] = {
	ZEND_ME(Gtk4_GLib, idle_add, arginfo_class_Gtk4_GLib_idle_add, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GLib, timeout_add, arginfo_class_Gtk4_GLib_timeout_add, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GLib, source_remove, arginfo_class_Gtk4_GLib_source_remove, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GMainLoop_methods[] = {
	ZEND_ME(Gtk4_GMainLoop, __construct, arginfo_class_Gtk4_GMainLoop___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMainLoop, run, arginfo_class_Gtk4_GMainLoop_run, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMainLoop, quit, arginfo_class_Gtk4_GMainLoop_quit, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMainLoop, is_running, arginfo_class_Gtk4_GMainLoop_is_running, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GAction_methods[] = {
	ZEND_RAW_FENTRY("get_name", NULL, arginfo_class_Gtk4_GAction_get_name, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_enabled", NULL, arginfo_class_Gtk4_GAction_get_enabled, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_parameter_type", NULL, arginfo_class_Gtk4_GAction_get_parameter_type, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_state", NULL, arginfo_class_Gtk4_GAction_get_state, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GActionMap_methods[] = {
	ZEND_RAW_FENTRY("add_action", NULL, arginfo_class_Gtk4_GActionMap_add_action, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("remove_action", NULL, arginfo_class_Gtk4_GActionMap_remove_action, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("lookup_action", NULL, arginfo_class_Gtk4_GActionMap_lookup_action, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GActionGroup_methods[] = {
	ZEND_RAW_FENTRY("has_action", NULL, arginfo_class_Gtk4_GActionGroup_has_action, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("list_actions", NULL, arginfo_class_Gtk4_GActionGroup_list_actions, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("activate_action", NULL, arginfo_class_Gtk4_GActionGroup_activate_action, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GSimpleAction_methods[] = {
	ZEND_ME(Gtk4_GSimpleAction, __construct, arginfo_class_Gtk4_GSimpleAction___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GSimpleAction, get_name, arginfo_class_Gtk4_GSimpleAction_get_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GSimpleAction, get_enabled, arginfo_class_Gtk4_GSimpleAction_get_enabled, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GSimpleAction, set_enabled, arginfo_class_Gtk4_GSimpleAction_set_enabled, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GSimpleAction, get_parameter_type, arginfo_class_Gtk4_GSimpleAction_get_parameter_type, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GSimpleAction, get_state, arginfo_class_Gtk4_GSimpleAction_get_state, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GSimpleAction, set_state, arginfo_class_Gtk4_GSimpleAction_set_state, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GSimpleAction, activate, arginfo_class_Gtk4_GSimpleAction_activate, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkApplication_methods[] = {
	ZEND_ME(Gtk4_GtkApplication, __construct, arginfo_class_Gtk4_GtkApplication___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, run, arginfo_class_Gtk4_GtkApplication_run, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, quit, arginfo_class_Gtk4_GtkApplication_quit, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, add_window, arginfo_class_Gtk4_GtkApplication_add_window, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, get_active_window, arginfo_class_Gtk4_GtkApplication_get_active_window, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, get_application_id, arginfo_class_Gtk4_GtkApplication_get_application_id, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, add_action, arginfo_class_Gtk4_GtkApplication_add_action, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, remove_action, arginfo_class_Gtk4_GtkApplication_remove_action, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, lookup_action, arginfo_class_Gtk4_GtkApplication_lookup_action, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, has_action, arginfo_class_Gtk4_GtkApplication_has_action, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, list_actions, arginfo_class_Gtk4_GtkApplication_list_actions, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, activate_action, arginfo_class_Gtk4_GtkApplication_activate_action, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkRGBA_methods[] = {
	ZEND_ME(Gtk4_GdkRGBA, __construct, arginfo_class_Gtk4_GdkRGBA___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkRGBA, parse, arginfo_class_Gtk4_GdkRGBA_parse, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkRGBA, to_string, arginfo_class_Gtk4_GdkRGBA_to_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkRGBA, equal, arginfo_class_Gtk4_GdkRGBA_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkRGBA, is_opaque, arginfo_class_Gtk4_GdkRGBA_is_opaque, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkRectangle_methods[] = {
	ZEND_ME(Gtk4_GdkRectangle, __construct, arginfo_class_Gtk4_GdkRectangle___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkRectangle, intersect, arginfo_class_Gtk4_GdkRectangle_intersect, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkRectangle, union, arginfo_class_Gtk4_GdkRectangle_union, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkRectangle, contains_point, arginfo_class_Gtk4_GdkRectangle_contains_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkRectangle, equal, arginfo_class_Gtk4_GdkRectangle_equal, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkWidget_methods[] = {
	ZEND_ME(Gtk4_GtkWidget, show, arginfo_class_Gtk4_GtkWidget_show, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, hide, arginfo_class_Gtk4_GtkWidget_hide, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_visible, arginfo_class_Gtk4_GtkWidget_set_visible, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_visible, arginfo_class_Gtk4_GtkWidget_get_visible, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, is_visible, arginfo_class_Gtk4_GtkWidget_is_visible, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_sensitive, arginfo_class_Gtk4_GtkWidget_set_sensitive, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_sensitive, arginfo_class_Gtk4_GtkWidget_get_sensitive, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_size_request, arginfo_class_Gtk4_GtkWidget_set_size_request, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_parent, arginfo_class_Gtk4_GtkWidget_get_parent, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_root, arginfo_class_Gtk4_GtkWidget_get_root, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, grab_focus, arginfo_class_Gtk4_GtkWidget_grab_focus, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, activate, arginfo_class_Gtk4_GtkWidget_activate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_tooltip_text, arginfo_class_Gtk4_GtkWidget_set_tooltip_text, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_tooltip_text, arginfo_class_Gtk4_GtkWidget_get_tooltip_text, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_name, arginfo_class_Gtk4_GtkWidget_set_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_name, arginfo_class_Gtk4_GtkWidget_get_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, add_css_class, arginfo_class_Gtk4_GtkWidget_add_css_class, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, remove_css_class, arginfo_class_Gtk4_GtkWidget_remove_css_class, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, has_css_class, arginfo_class_Gtk4_GtkWidget_has_css_class, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_css_classes, arginfo_class_Gtk4_GtkWidget_get_css_classes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_css_classes, arginfo_class_Gtk4_GtkWidget_set_css_classes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, activate_action, arginfo_class_Gtk4_GtkWidget_activate_action, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, queue_draw, arginfo_class_Gtk4_GtkWidget_queue_draw, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkButton_methods[] = {
	ZEND_ME(Gtk4_GtkButton, __construct, arginfo_class_Gtk4_GtkButton___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, set_label, arginfo_class_Gtk4_GtkButton_set_label, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, get_label, arginfo_class_Gtk4_GtkButton_get_label, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, set_child, arginfo_class_Gtk4_GtkButton_set_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, get_child, arginfo_class_Gtk4_GtkButton_get_child, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkLabel_methods[] = {
	ZEND_ME(Gtk4_GtkLabel, __construct, arginfo_class_Gtk4_GtkLabel___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_text, arginfo_class_Gtk4_GtkLabel_set_text, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_text, arginfo_class_Gtk4_GtkLabel_get_text, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_markup, arginfo_class_Gtk4_GtkLabel_set_markup, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_selectable, arginfo_class_Gtk4_GtkLabel_set_selectable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_selectable, arginfo_class_Gtk4_GtkLabel_get_selectable, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkWindow_methods[] = {
	ZEND_ME(Gtk4_GtkWindow, __construct, arginfo_class_Gtk4_GtkWindow___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_application, arginfo_class_Gtk4_GtkWindow_set_application, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_application, arginfo_class_Gtk4_GtkWindow_get_application, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_title, arginfo_class_Gtk4_GtkWindow_set_title, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_title, arginfo_class_Gtk4_GtkWindow_get_title, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_default_size, arginfo_class_Gtk4_GtkWindow_set_default_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_child, arginfo_class_Gtk4_GtkWindow_set_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_child, arginfo_class_Gtk4_GtkWindow_get_child, ZEND_ACC_PUBLIC)
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

static zend_class_entry *register_class_Gtk4_GParamSpec(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GParamSpec", class_Gtk4_GParamSpec_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_ExceptionMode(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\ExceptionMode", IS_LONG, NULL);

	zval enum_case_Log_value;
	ZVAL_LONG(&enum_case_Log_value, 0);
	zend_enum_add_case_cstr(class_entry, "Log", &enum_case_Log_value);

	zval enum_case_Rethrow_value;
	ZVAL_LONG(&enum_case_Rethrow_value, 1);
	zend_enum_add_case_cstr(class_entry, "Rethrow", &enum_case_Rethrow_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_Gtk(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "Gtk", class_Gtk4_Gtk_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GLib(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GLib", class_Gtk4_GLib_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GMainLoop(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GMainLoop", class_Gtk4_GMainLoop_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GAction(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GAction", class_Gtk4_GAction_methods);
	class_entry = zend_register_internal_interface(&ce);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GActionMap(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GActionMap", class_Gtk4_GActionMap_methods);
	class_entry = zend_register_internal_interface(&ce);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GActionGroup(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GActionGroup", class_Gtk4_GActionGroup_methods);
	class_entry = zend_register_internal_interface(&ce);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GSimpleAction(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GAction)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GSimpleAction", class_Gtk4_GSimpleAction_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_NOT_SERIALIZABLE);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GAction);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkApplication(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GActionMap, zend_class_entry *class_entry_Gtk4_GActionGroup)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkApplication", class_Gtk4_GtkApplication_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_NOT_SERIALIZABLE);
	zend_class_implements(class_entry, 2, class_entry_Gtk4_GActionMap, class_entry_Gtk4_GActionGroup);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkRGBA(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkRGBA", class_Gtk4_GdkRGBA_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkRectangle(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkRectangle", class_Gtk4_GdkRectangle_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkWidget(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkWidget", class_Gtk4_GtkWidget_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_ABSTRACT|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkButton(zend_class_entry *class_entry_Gtk4_GtkWidget)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkButton", class_Gtk4_GtkButton_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GtkWidget, ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkLabel(zend_class_entry *class_entry_Gtk4_GtkWidget)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkLabel", class_Gtk4_GtkLabel_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GtkWidget, ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkWindow(zend_class_entry *class_entry_Gtk4_GtkWidget)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkWindow", class_Gtk4_GtkWindow_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GtkWidget, ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}
