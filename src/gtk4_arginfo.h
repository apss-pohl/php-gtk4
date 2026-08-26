/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 3baa732bbff5bfb4ee5c9787beecad2a31994c18 */

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
	ZEND_ARG_TYPE_INFO(0, handler_id, IS_LONG, 0)
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
	ZEND_ARG_TYPE_INFO(0, interval_ms, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, callback, IS_CALLABLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GLib_source_remove, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, source_id, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GMainLoop___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMainLoop_run, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GMainLoop_quit arginfo_class_Gtk4_GMainLoop_run

#define arginfo_class_Gtk4_GMainLoop_is_running arginfo_class_Gtk4_GParamSpec_is_readable

#define arginfo_class_Gtk4_GError_getDomain arginfo_class_Gtk4_GParamSpec_get_name

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkTexture_new_from_filename, 0, 1, Gtk4\\GdkTexture, 0)
	ZEND_ARG_TYPE_INFO(0, path, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkTexture_new_from_bytes, 0, 1, Gtk4\\GdkTexture, 0)
	ZEND_ARG_TYPE_INFO(0, bytes, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkTexture_get_width arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_GdkTexture_get_height arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_GdkTexture_save_to_png_bytes arginfo_class_Gtk4_GParamSpec_get_name

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkTexture_save_to_png, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, path, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_PhpValue___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, value, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PhpValue_get_value arginfo_class_Gtk4_GParamSpec_get_default_value

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PhpValue_set_value, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, value, IS_MIXED, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GListModel_get_item_type arginfo_class_Gtk4_GParamSpec_get_name

#define arginfo_class_Gtk4_GListModel_get_n_items arginfo_class_Gtk4_GParamSpec_get_flags

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GListModel_get_item, 0, 1, Gtk4\\GObject, 1)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GListStore___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, item_type, IS_STRING, 0, "Gtk4\\GObject::class")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GListStore_get_item_type arginfo_class_Gtk4_GParamSpec_get_name

#define arginfo_class_Gtk4_GListStore_get_n_items arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_GListStore_get_item arginfo_class_Gtk4_GListModel_get_item

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GListStore_append, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, item, Gtk4\\GObject, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GListStore_insert, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, item, Gtk4\\GObject, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GListStore_remove, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GListStore_remove_all arginfo_class_Gtk4_GMainLoop_run

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GListStore_find, 0, 1, IS_LONG, 1)
	ZEND_ARG_OBJ_INFO(0, item, Gtk4\\GObject, 0)
ZEND_END_ARG_INFO()

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
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameter_type, IS_STRING, 1, "null")
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
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, application_id, IS_STRING, 1, "null")
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

#define arginfo_class_Gtk4_GtkApplication_get_windows arginfo_class_Gtk4_GActionGroup_list_actions

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

#define arginfo_class_Gtk4_GtkWidget_get_size_request arginfo_class_Gtk4_GActionGroup_list_actions

#define arginfo_class_Gtk4_GtkWidget_list_mnemonic_labels arginfo_class_Gtk4_GActionGroup_list_actions

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
	ZEND_ARG_TYPE_INFO(0, css_class, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_remove_css_class arginfo_class_Gtk4_GtkWidget_add_css_class

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_has_css_class, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, css_class, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_get_css_classes arginfo_class_Gtk4_GActionGroup_list_actions

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_css_classes, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, classes, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_activate_action, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameter, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_halign, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, align, Gtk4\\GtkAlign, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkWidget_get_halign, 0, 0, Gtk4\\GtkAlign, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_set_valign arginfo_class_Gtk4_GtkWidget_set_halign

#define arginfo_class_Gtk4_GtkWidget_get_valign arginfo_class_Gtk4_GtkWidget_get_halign

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_hexpand, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, expand, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_get_hexpand arginfo_class_Gtk4_GParamSpec_is_readable

#define arginfo_class_Gtk4_GtkWidget_set_vexpand arginfo_class_Gtk4_GtkWidget_set_hexpand

#define arginfo_class_Gtk4_GtkWidget_get_vexpand arginfo_class_Gtk4_GParamSpec_is_readable

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

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkBox___construct, 0, 0, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, orientation, Gtk4\\GtkOrientation, 0, "Gtk4\\GtkOrientation::Horizontal")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, spacing, IS_LONG, 0, "0")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkBox_append, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GtkWidget, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkBox_prepend arginfo_class_Gtk4_GtkBox_append

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkBox_insert_child_after, 0, 2, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GtkWidget, 0)
	ZEND_ARG_OBJ_INFO(0, sibling, Gtk4\\GtkWidget, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkBox_remove arginfo_class_Gtk4_GtkBox_append

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkBox_set_spacing, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, spacing, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkBox_get_spacing arginfo_class_Gtk4_GParamSpec_get_flags

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkBox_set_homogeneous, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, homogeneous, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkBox_get_homogeneous arginfo_class_Gtk4_GParamSpec_is_readable

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkBox_set_orientation, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, orientation, Gtk4\\GtkOrientation, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkBox_get_orientation, 0, 0, Gtk4\\GtkOrientation, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkBox_get_children arginfo_class_Gtk4_GActionGroup_list_actions

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

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_get_selection_bounds, 0, 0, IS_ARRAY, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_select_region, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, start, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, end, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_CairoContext_set_source_rgb, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, red, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, green, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, blue, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_CairoContext_set_source_rgba, 0, 4, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, red, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, green, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, blue, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, alpha, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_CairoContext_set_source_color, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, color, Gtk4\\GdkRGBA, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_CairoContext_set_line_width, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_CairoContext_move_to, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_CairoContext_line_to arginfo_class_Gtk4_CairoContext_move_to

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_CairoContext_rectangle, 0, 4, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_CairoContext_arc, 0, 5, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, xc, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, yc, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, radius, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, angle1, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, angle2, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_CairoContext_close_path arginfo_class_Gtk4_GMainLoop_run

#define arginfo_class_Gtk4_CairoContext_fill arginfo_class_Gtk4_GMainLoop_run

#define arginfo_class_Gtk4_CairoContext_fill_preserve arginfo_class_Gtk4_GMainLoop_run

#define arginfo_class_Gtk4_CairoContext_stroke arginfo_class_Gtk4_GMainLoop_run

#define arginfo_class_Gtk4_CairoContext_stroke_preserve arginfo_class_Gtk4_GMainLoop_run

#define arginfo_class_Gtk4_CairoContext_paint arginfo_class_Gtk4_GMainLoop_run

#define arginfo_class_Gtk4_CairoContext_save arginfo_class_Gtk4_GMainLoop_run

#define arginfo_class_Gtk4_CairoContext_restore arginfo_class_Gtk4_GMainLoop_run

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_CairoContext_translate, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, tx, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, ty, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_CairoContext_scale, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, sx, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, sy, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_CairoContext_rotate, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, angle, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_CairoContext_set_font_size, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, size, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_CairoContext_show_text arginfo_class_Gtk4_GtkLabel_set_text

#define arginfo_class_Gtk4_GtkDrawingArea___construct arginfo_class_Gtk4_GMainLoop___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkDrawingArea_set_draw_func, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, draw_func, IS_CALLABLE, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkDrawingArea_set_content_width, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkDrawingArea_get_content_width arginfo_class_Gtk4_GParamSpec_get_flags

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkDrawingArea_set_content_height, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkDrawingArea_get_content_height arginfo_class_Gtk4_GParamSpec_get_flags

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkFilter_changed, 0, 0, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, change, Gtk4\\GtkFilterChange, 0, "Gtk4\\GtkFilterChange::Different")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkCustomFilter___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, match_func, IS_CALLABLE, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkCustomFilter_set_filter_func, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, match_func, IS_CALLABLE, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkFilterListModel___construct, 0, 0, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, model, Gtk4\\GListModel, 1, "null")
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, filter, Gtk4\\GtkFilter, 1, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkFilterListModel_get_item_type arginfo_class_Gtk4_GParamSpec_get_name

#define arginfo_class_Gtk4_GtkFilterListModel_get_n_items arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_GtkFilterListModel_get_item arginfo_class_Gtk4_GListModel_get_item

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkFilterListModel_set_filter, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, filter, Gtk4\\GtkFilter, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkFilterListModel_get_filter, 0, 0, Gtk4\\GtkFilter, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkFilterListModel_set_model, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, model, Gtk4\\GListModel, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkFilterListModel_get_model, 0, 0, Gtk4\\GListModel, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkSorter_changed, 0, 0, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, change, Gtk4\\GtkSorterChange, 0, "Gtk4\\GtkSorterChange::Different")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkCustomSorter___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, compare, IS_CALLABLE, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkCustomSorter_set_sort_func, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, compare, IS_CALLABLE, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkSortListModel___construct, 0, 0, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, model, Gtk4\\GListModel, 1, "null")
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, sorter, Gtk4\\GtkSorter, 1, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkSortListModel_get_item_type arginfo_class_Gtk4_GParamSpec_get_name

#define arginfo_class_Gtk4_GtkSortListModel_get_n_items arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_GtkSortListModel_get_item arginfo_class_Gtk4_GListModel_get_item

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkSortListModel_set_sorter, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, sorter, Gtk4\\GtkSorter, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkSortListModel_get_sorter, 0, 0, Gtk4\\GtkSorter, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkSortListModel_set_model arginfo_class_Gtk4_GtkFilterListModel_set_model

#define arginfo_class_Gtk4_GtkSortListModel_get_model arginfo_class_Gtk4_GtkFilterListModel_get_model

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

#define arginfo_class_Gtk4_GtkWindow_get_default_size arginfo_class_Gtk4_GActionGroup_list_actions

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
ZEND_METHOD(Gtk4_GError, getDomain);
ZEND_METHOD(Gtk4_GdkTexture, new_from_filename);
ZEND_METHOD(Gtk4_GdkTexture, new_from_bytes);
ZEND_METHOD(Gtk4_GdkTexture, get_width);
ZEND_METHOD(Gtk4_GdkTexture, get_height);
ZEND_METHOD(Gtk4_GdkTexture, save_to_png_bytes);
ZEND_METHOD(Gtk4_GdkTexture, save_to_png);
ZEND_METHOD(Gtk4_PhpValue, __construct);
ZEND_METHOD(Gtk4_PhpValue, get_value);
ZEND_METHOD(Gtk4_PhpValue, set_value);
ZEND_METHOD(Gtk4_GListStore, __construct);
ZEND_METHOD(Gtk4_GListStore, append);
ZEND_METHOD(Gtk4_GListStore, insert);
ZEND_METHOD(Gtk4_GListStore, remove);
ZEND_METHOD(Gtk4_GListStore, remove_all);
ZEND_METHOD(Gtk4_GListStore, find);
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
ZEND_METHOD(Gtk4_GtkApplication, get_windows);
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
ZEND_METHOD(Gtk4_GtkWidget, get_size_request);
ZEND_METHOD(Gtk4_GtkWidget, list_mnemonic_labels);
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
ZEND_METHOD(Gtk4_GtkWidget, set_halign);
ZEND_METHOD(Gtk4_GtkWidget, get_halign);
ZEND_METHOD(Gtk4_GtkWidget, set_valign);
ZEND_METHOD(Gtk4_GtkWidget, get_valign);
ZEND_METHOD(Gtk4_GtkWidget, set_hexpand);
ZEND_METHOD(Gtk4_GtkWidget, get_hexpand);
ZEND_METHOD(Gtk4_GtkWidget, set_vexpand);
ZEND_METHOD(Gtk4_GtkWidget, get_vexpand);
ZEND_METHOD(Gtk4_GtkWidget, queue_draw);
ZEND_METHOD(Gtk4_GtkButton, __construct);
ZEND_METHOD(Gtk4_GtkButton, set_label);
ZEND_METHOD(Gtk4_GtkButton, get_label);
ZEND_METHOD(Gtk4_GtkButton, set_child);
ZEND_METHOD(Gtk4_GtkButton, get_child);
ZEND_METHOD(Gtk4_GtkBox, __construct);
ZEND_METHOD(Gtk4_GtkBox, append);
ZEND_METHOD(Gtk4_GtkBox, prepend);
ZEND_METHOD(Gtk4_GtkBox, insert_child_after);
ZEND_METHOD(Gtk4_GtkBox, remove);
ZEND_METHOD(Gtk4_GtkBox, set_spacing);
ZEND_METHOD(Gtk4_GtkBox, get_spacing);
ZEND_METHOD(Gtk4_GtkBox, set_homogeneous);
ZEND_METHOD(Gtk4_GtkBox, get_homogeneous);
ZEND_METHOD(Gtk4_GtkBox, set_orientation);
ZEND_METHOD(Gtk4_GtkBox, get_orientation);
ZEND_METHOD(Gtk4_GtkBox, get_children);
ZEND_METHOD(Gtk4_GtkLabel, __construct);
ZEND_METHOD(Gtk4_GtkLabel, set_text);
ZEND_METHOD(Gtk4_GtkLabel, get_text);
ZEND_METHOD(Gtk4_GtkLabel, set_markup);
ZEND_METHOD(Gtk4_GtkLabel, set_selectable);
ZEND_METHOD(Gtk4_GtkLabel, get_selectable);
ZEND_METHOD(Gtk4_GtkLabel, get_selection_bounds);
ZEND_METHOD(Gtk4_GtkLabel, select_region);
ZEND_METHOD(Gtk4_CairoContext, set_source_rgb);
ZEND_METHOD(Gtk4_CairoContext, set_source_rgba);
ZEND_METHOD(Gtk4_CairoContext, set_source_color);
ZEND_METHOD(Gtk4_CairoContext, set_line_width);
ZEND_METHOD(Gtk4_CairoContext, move_to);
ZEND_METHOD(Gtk4_CairoContext, line_to);
ZEND_METHOD(Gtk4_CairoContext, rectangle);
ZEND_METHOD(Gtk4_CairoContext, arc);
ZEND_METHOD(Gtk4_CairoContext, close_path);
ZEND_METHOD(Gtk4_CairoContext, fill);
ZEND_METHOD(Gtk4_CairoContext, fill_preserve);
ZEND_METHOD(Gtk4_CairoContext, stroke);
ZEND_METHOD(Gtk4_CairoContext, stroke_preserve);
ZEND_METHOD(Gtk4_CairoContext, paint);
ZEND_METHOD(Gtk4_CairoContext, save);
ZEND_METHOD(Gtk4_CairoContext, restore);
ZEND_METHOD(Gtk4_CairoContext, translate);
ZEND_METHOD(Gtk4_CairoContext, scale);
ZEND_METHOD(Gtk4_CairoContext, rotate);
ZEND_METHOD(Gtk4_CairoContext, set_font_size);
ZEND_METHOD(Gtk4_CairoContext, show_text);
ZEND_METHOD(Gtk4_GtkDrawingArea, __construct);
ZEND_METHOD(Gtk4_GtkDrawingArea, set_draw_func);
ZEND_METHOD(Gtk4_GtkDrawingArea, set_content_width);
ZEND_METHOD(Gtk4_GtkDrawingArea, get_content_width);
ZEND_METHOD(Gtk4_GtkDrawingArea, set_content_height);
ZEND_METHOD(Gtk4_GtkDrawingArea, get_content_height);
ZEND_METHOD(Gtk4_GtkFilter, changed);
ZEND_METHOD(Gtk4_GtkCustomFilter, __construct);
ZEND_METHOD(Gtk4_GtkCustomFilter, set_filter_func);
ZEND_METHOD(Gtk4_GtkFilterListModel, __construct);
ZEND_METHOD(Gtk4_GtkFilterListModel, set_filter);
ZEND_METHOD(Gtk4_GtkFilterListModel, get_filter);
ZEND_METHOD(Gtk4_GtkFilterListModel, set_model);
ZEND_METHOD(Gtk4_GtkFilterListModel, get_model);
ZEND_METHOD(Gtk4_GtkSorter, changed);
ZEND_METHOD(Gtk4_GtkCustomSorter, __construct);
ZEND_METHOD(Gtk4_GtkCustomSorter, set_sort_func);
ZEND_METHOD(Gtk4_GtkSortListModel, __construct);
ZEND_METHOD(Gtk4_GtkSortListModel, set_sorter);
ZEND_METHOD(Gtk4_GtkSortListModel, get_sorter);
ZEND_METHOD(Gtk4_GtkSortListModel, set_model);
ZEND_METHOD(Gtk4_GtkSortListModel, get_model);
ZEND_METHOD(Gtk4_GtkWindow, __construct);
ZEND_METHOD(Gtk4_GtkWindow, set_application);
ZEND_METHOD(Gtk4_GtkWindow, get_application);
ZEND_METHOD(Gtk4_GtkWindow, set_title);
ZEND_METHOD(Gtk4_GtkWindow, get_title);
ZEND_METHOD(Gtk4_GtkWindow, set_default_size);
ZEND_METHOD(Gtk4_GtkWindow, get_default_size);
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

static const zend_function_entry class_Gtk4_GError_methods[] = {
	ZEND_ME(Gtk4_GError, getDomain, arginfo_class_Gtk4_GError_getDomain, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkTexture_methods[] = {
	ZEND_ME(Gtk4_GdkTexture, new_from_filename, arginfo_class_Gtk4_GdkTexture_new_from_filename, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkTexture, new_from_bytes, arginfo_class_Gtk4_GdkTexture_new_from_bytes, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GdkTexture, get_width, arginfo_class_Gtk4_GdkTexture_get_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, get_height, arginfo_class_Gtk4_GdkTexture_get_height, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, save_to_png_bytes, arginfo_class_Gtk4_GdkTexture_save_to_png_bytes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTexture, save_to_png, arginfo_class_Gtk4_GdkTexture_save_to_png, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_PhpValue_methods[] = {
	ZEND_ME(Gtk4_PhpValue, __construct, arginfo_class_Gtk4_PhpValue___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PhpValue, get_value, arginfo_class_Gtk4_PhpValue_get_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PhpValue, set_value, arginfo_class_Gtk4_PhpValue_set_value, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GListModel_methods[] = {
	ZEND_RAW_FENTRY("get_item_type", NULL, arginfo_class_Gtk4_GListModel_get_item_type, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_n_items", NULL, arginfo_class_Gtk4_GListModel_get_n_items, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_item", NULL, arginfo_class_Gtk4_GListModel_get_item, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GListStore_methods[] = {
	ZEND_ME(Gtk4_GListStore, __construct, arginfo_class_Gtk4_GListStore___construct, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("get_item_type", zim_Gtk4_GListModel_get_item_type, arginfo_class_Gtk4_GListStore_get_item_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_n_items", zim_Gtk4_GListModel_get_n_items, arginfo_class_Gtk4_GListStore_get_n_items, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_item", zim_Gtk4_GListModel_get_item, arginfo_class_Gtk4_GListStore_get_item, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_ME(Gtk4_GListStore, append, arginfo_class_Gtk4_GListStore_append, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GListStore, insert, arginfo_class_Gtk4_GListStore_insert, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GListStore, remove, arginfo_class_Gtk4_GListStore_remove, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GListStore, remove_all, arginfo_class_Gtk4_GListStore_remove_all, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GListStore, find, arginfo_class_Gtk4_GListStore_find, ZEND_ACC_PUBLIC)
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
	ZEND_ME(Gtk4_GtkApplication, get_windows, arginfo_class_Gtk4_GtkApplication_get_windows, ZEND_ACC_PUBLIC)
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
	ZEND_ME(Gtk4_GtkWidget, get_size_request, arginfo_class_Gtk4_GtkWidget_get_size_request, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, list_mnemonic_labels, arginfo_class_Gtk4_GtkWidget_list_mnemonic_labels, ZEND_ACC_PUBLIC)
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
	ZEND_ME(Gtk4_GtkWidget, set_halign, arginfo_class_Gtk4_GtkWidget_set_halign, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_halign, arginfo_class_Gtk4_GtkWidget_get_halign, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_valign, arginfo_class_Gtk4_GtkWidget_set_valign, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_valign, arginfo_class_Gtk4_GtkWidget_get_valign, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_hexpand, arginfo_class_Gtk4_GtkWidget_set_hexpand, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_hexpand, arginfo_class_Gtk4_GtkWidget_get_hexpand, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_vexpand, arginfo_class_Gtk4_GtkWidget_set_vexpand, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_vexpand, arginfo_class_Gtk4_GtkWidget_get_vexpand, ZEND_ACC_PUBLIC)
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

static const zend_function_entry class_Gtk4_GtkBox_methods[] = {
	ZEND_ME(Gtk4_GtkBox, __construct, arginfo_class_Gtk4_GtkBox___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, append, arginfo_class_Gtk4_GtkBox_append, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, prepend, arginfo_class_Gtk4_GtkBox_prepend, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, insert_child_after, arginfo_class_Gtk4_GtkBox_insert_child_after, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, remove, arginfo_class_Gtk4_GtkBox_remove, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, set_spacing, arginfo_class_Gtk4_GtkBox_set_spacing, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, get_spacing, arginfo_class_Gtk4_GtkBox_get_spacing, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, set_homogeneous, arginfo_class_Gtk4_GtkBox_set_homogeneous, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, get_homogeneous, arginfo_class_Gtk4_GtkBox_get_homogeneous, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, set_orientation, arginfo_class_Gtk4_GtkBox_set_orientation, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, get_orientation, arginfo_class_Gtk4_GtkBox_get_orientation, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, get_children, arginfo_class_Gtk4_GtkBox_get_children, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkLabel_methods[] = {
	ZEND_ME(Gtk4_GtkLabel, __construct, arginfo_class_Gtk4_GtkLabel___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_text, arginfo_class_Gtk4_GtkLabel_set_text, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_text, arginfo_class_Gtk4_GtkLabel_get_text, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_markup, arginfo_class_Gtk4_GtkLabel_set_markup, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_selectable, arginfo_class_Gtk4_GtkLabel_set_selectable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_selectable, arginfo_class_Gtk4_GtkLabel_get_selectable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_selection_bounds, arginfo_class_Gtk4_GtkLabel_get_selection_bounds, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, select_region, arginfo_class_Gtk4_GtkLabel_select_region, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_CairoContext_methods[] = {
	ZEND_ME(Gtk4_CairoContext, set_source_rgb, arginfo_class_Gtk4_CairoContext_set_source_rgb, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, set_source_rgba, arginfo_class_Gtk4_CairoContext_set_source_rgba, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, set_source_color, arginfo_class_Gtk4_CairoContext_set_source_color, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, set_line_width, arginfo_class_Gtk4_CairoContext_set_line_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, move_to, arginfo_class_Gtk4_CairoContext_move_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, line_to, arginfo_class_Gtk4_CairoContext_line_to, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, rectangle, arginfo_class_Gtk4_CairoContext_rectangle, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, arc, arginfo_class_Gtk4_CairoContext_arc, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, close_path, arginfo_class_Gtk4_CairoContext_close_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, fill, arginfo_class_Gtk4_CairoContext_fill, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, fill_preserve, arginfo_class_Gtk4_CairoContext_fill_preserve, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, stroke, arginfo_class_Gtk4_CairoContext_stroke, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, stroke_preserve, arginfo_class_Gtk4_CairoContext_stroke_preserve, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, paint, arginfo_class_Gtk4_CairoContext_paint, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, save, arginfo_class_Gtk4_CairoContext_save, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, restore, arginfo_class_Gtk4_CairoContext_restore, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, translate, arginfo_class_Gtk4_CairoContext_translate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, scale, arginfo_class_Gtk4_CairoContext_scale, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, rotate, arginfo_class_Gtk4_CairoContext_rotate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, set_font_size, arginfo_class_Gtk4_CairoContext_set_font_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoContext, show_text, arginfo_class_Gtk4_CairoContext_show_text, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkDrawingArea_methods[] = {
	ZEND_ME(Gtk4_GtkDrawingArea, __construct, arginfo_class_Gtk4_GtkDrawingArea___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkDrawingArea, set_draw_func, arginfo_class_Gtk4_GtkDrawingArea_set_draw_func, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkDrawingArea, set_content_width, arginfo_class_Gtk4_GtkDrawingArea_set_content_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkDrawingArea, get_content_width, arginfo_class_Gtk4_GtkDrawingArea_get_content_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkDrawingArea, set_content_height, arginfo_class_Gtk4_GtkDrawingArea_set_content_height, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkDrawingArea, get_content_height, arginfo_class_Gtk4_GtkDrawingArea_get_content_height, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkFilter_methods[] = {
	ZEND_ME(Gtk4_GtkFilter, changed, arginfo_class_Gtk4_GtkFilter_changed, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkCustomFilter_methods[] = {
	ZEND_ME(Gtk4_GtkCustomFilter, __construct, arginfo_class_Gtk4_GtkCustomFilter___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkCustomFilter, set_filter_func, arginfo_class_Gtk4_GtkCustomFilter_set_filter_func, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkFilterListModel_methods[] = {
	ZEND_ME(Gtk4_GtkFilterListModel, __construct, arginfo_class_Gtk4_GtkFilterListModel___construct, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("get_item_type", zim_Gtk4_GListModel_get_item_type, arginfo_class_Gtk4_GtkFilterListModel_get_item_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_n_items", zim_Gtk4_GListModel_get_n_items, arginfo_class_Gtk4_GtkFilterListModel_get_n_items, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_item", zim_Gtk4_GListModel_get_item, arginfo_class_Gtk4_GtkFilterListModel_get_item, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_ME(Gtk4_GtkFilterListModel, set_filter, arginfo_class_Gtk4_GtkFilterListModel_set_filter, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilterListModel, get_filter, arginfo_class_Gtk4_GtkFilterListModel_get_filter, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilterListModel, set_model, arginfo_class_Gtk4_GtkFilterListModel_set_model, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilterListModel, get_model, arginfo_class_Gtk4_GtkFilterListModel_get_model, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkSorter_methods[] = {
	ZEND_ME(Gtk4_GtkSorter, changed, arginfo_class_Gtk4_GtkSorter_changed, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkCustomSorter_methods[] = {
	ZEND_ME(Gtk4_GtkCustomSorter, __construct, arginfo_class_Gtk4_GtkCustomSorter___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkCustomSorter, set_sort_func, arginfo_class_Gtk4_GtkCustomSorter_set_sort_func, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkSortListModel_methods[] = {
	ZEND_ME(Gtk4_GtkSortListModel, __construct, arginfo_class_Gtk4_GtkSortListModel___construct, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("get_item_type", zim_Gtk4_GListModel_get_item_type, arginfo_class_Gtk4_GtkSortListModel_get_item_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_n_items", zim_Gtk4_GListModel_get_n_items, arginfo_class_Gtk4_GtkSortListModel_get_n_items, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_item", zim_Gtk4_GListModel_get_item, arginfo_class_Gtk4_GtkSortListModel_get_item, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_ME(Gtk4_GtkSortListModel, set_sorter, arginfo_class_Gtk4_GtkSortListModel_set_sorter, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSortListModel, get_sorter, arginfo_class_Gtk4_GtkSortListModel_get_sorter, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSortListModel, set_model, arginfo_class_Gtk4_GtkSortListModel_set_model, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSortListModel, get_model, arginfo_class_Gtk4_GtkSortListModel_get_model, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkWindow_methods[] = {
	ZEND_ME(Gtk4_GtkWindow, __construct, arginfo_class_Gtk4_GtkWindow___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_application, arginfo_class_Gtk4_GtkWindow_set_application, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_application, arginfo_class_Gtk4_GtkWindow_get_application, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_title, arginfo_class_Gtk4_GtkWindow_set_title, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_title, arginfo_class_Gtk4_GtkWindow_get_title, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_default_size, arginfo_class_Gtk4_GtkWindow_set_default_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_default_size, arginfo_class_Gtk4_GtkWindow_get_default_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_child, arginfo_class_Gtk4_GtkWindow_set_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_child, arginfo_class_Gtk4_GtkWindow_get_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, present, arginfo_class_Gtk4_GtkWindow_present, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, close, arginfo_class_Gtk4_GtkWindow_close, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, destroy, arginfo_class_Gtk4_GtkWindow_destroy, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static void register_gtk4_symbols(int module_number)
{
	REGISTER_STRING_CONSTANT("Gtk4\\VERSION", "0.1.0-dev", CONST_PERSISTENT);
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

static zend_class_entry *register_class_Gtk4_GtkAlign(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GtkAlign", IS_LONG, NULL);

	zval enum_case_Fill_value;
	ZVAL_LONG(&enum_case_Fill_value, 0);
	zend_enum_add_case_cstr(class_entry, "Fill", &enum_case_Fill_value);

	zval enum_case_Start_value;
	ZVAL_LONG(&enum_case_Start_value, 1);
	zend_enum_add_case_cstr(class_entry, "Start", &enum_case_Start_value);

	zval enum_case_End_value;
	ZVAL_LONG(&enum_case_End_value, 2);
	zend_enum_add_case_cstr(class_entry, "End", &enum_case_End_value);

	zval enum_case_Center_value;
	ZVAL_LONG(&enum_case_Center_value, 3);
	zend_enum_add_case_cstr(class_entry, "Center", &enum_case_Center_value);

	zval enum_case_BaselineFill_value;
	ZVAL_LONG(&enum_case_BaselineFill_value, 4);
	zend_enum_add_case_cstr(class_entry, "BaselineFill", &enum_case_BaselineFill_value);

	zval enum_case_BaselineCenter_value;
	ZVAL_LONG(&enum_case_BaselineCenter_value, 5);
	zend_enum_add_case_cstr(class_entry, "BaselineCenter", &enum_case_BaselineCenter_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkOrientation(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GtkOrientation", IS_LONG, NULL);

	zval enum_case_Horizontal_value;
	ZVAL_LONG(&enum_case_Horizontal_value, 0);
	zend_enum_add_case_cstr(class_entry, "Horizontal", &enum_case_Horizontal_value);

	zval enum_case_Vertical_value;
	ZVAL_LONG(&enum_case_Vertical_value, 1);
	zend_enum_add_case_cstr(class_entry, "Vertical", &enum_case_Vertical_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GApplicationFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GApplicationFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_DEFAULT_FLAGS_value;
	ZVAL_LONG(&const_DEFAULT_FLAGS_value, 0);
	zend_string *const_DEFAULT_FLAGS_name = zend_string_init_interned("DEFAULT_FLAGS", sizeof("DEFAULT_FLAGS") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_DEFAULT_FLAGS_name, &const_DEFAULT_FLAGS_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_DEFAULT_FLAGS_name);

	zval const_IS_SERVICE_value;
	ZVAL_LONG(&const_IS_SERVICE_value, 1);
	zend_string *const_IS_SERVICE_name = zend_string_init_interned("IS_SERVICE", sizeof("IS_SERVICE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_IS_SERVICE_name, &const_IS_SERVICE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_IS_SERVICE_name);

	zval const_IS_LAUNCHER_value;
	ZVAL_LONG(&const_IS_LAUNCHER_value, 2);
	zend_string *const_IS_LAUNCHER_name = zend_string_init_interned("IS_LAUNCHER", sizeof("IS_LAUNCHER") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_IS_LAUNCHER_name, &const_IS_LAUNCHER_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_IS_LAUNCHER_name);

	zval const_HANDLES_OPEN_value;
	ZVAL_LONG(&const_HANDLES_OPEN_value, 4);
	zend_string *const_HANDLES_OPEN_name = zend_string_init_interned("HANDLES_OPEN", sizeof("HANDLES_OPEN") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_HANDLES_OPEN_name, &const_HANDLES_OPEN_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_HANDLES_OPEN_name);

	zval const_HANDLES_COMMAND_LINE_value;
	ZVAL_LONG(&const_HANDLES_COMMAND_LINE_value, 8);
	zend_string *const_HANDLES_COMMAND_LINE_name = zend_string_init_interned("HANDLES_COMMAND_LINE", sizeof("HANDLES_COMMAND_LINE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_HANDLES_COMMAND_LINE_name, &const_HANDLES_COMMAND_LINE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_HANDLES_COMMAND_LINE_name);

	zval const_SEND_ENVIRONMENT_value;
	ZVAL_LONG(&const_SEND_ENVIRONMENT_value, 16);
	zend_string *const_SEND_ENVIRONMENT_name = zend_string_init_interned("SEND_ENVIRONMENT", sizeof("SEND_ENVIRONMENT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_SEND_ENVIRONMENT_name, &const_SEND_ENVIRONMENT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_SEND_ENVIRONMENT_name);

	zval const_NON_UNIQUE_value;
	ZVAL_LONG(&const_NON_UNIQUE_value, 32);
	zend_string *const_NON_UNIQUE_name = zend_string_init_interned("NON_UNIQUE", sizeof("NON_UNIQUE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NON_UNIQUE_name, &const_NON_UNIQUE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NON_UNIQUE_name);

	zval const_CAN_OVERRIDE_APP_ID_value;
	ZVAL_LONG(&const_CAN_OVERRIDE_APP_ID_value, 64);
	zend_string *const_CAN_OVERRIDE_APP_ID_name = zend_string_init_interned("CAN_OVERRIDE_APP_ID", sizeof("CAN_OVERRIDE_APP_ID") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_CAN_OVERRIDE_APP_ID_name, &const_CAN_OVERRIDE_APP_ID_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_CAN_OVERRIDE_APP_ID_name);

	zval const_ALLOW_REPLACEMENT_value;
	ZVAL_LONG(&const_ALLOW_REPLACEMENT_value, 128);
	zend_string *const_ALLOW_REPLACEMENT_name = zend_string_init_interned("ALLOW_REPLACEMENT", sizeof("ALLOW_REPLACEMENT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_ALLOW_REPLACEMENT_name, &const_ALLOW_REPLACEMENT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_ALLOW_REPLACEMENT_name);

	zval const_REPLACE_value;
	ZVAL_LONG(&const_REPLACE_value, 256);
	zend_string *const_REPLACE_name = zend_string_init_interned("REPLACE", sizeof("REPLACE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_REPLACE_name, &const_REPLACE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_REPLACE_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkFilterChange(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GtkFilterChange", IS_LONG, NULL);

	zval enum_case_Different_value;
	ZVAL_LONG(&enum_case_Different_value, 0);
	zend_enum_add_case_cstr(class_entry, "Different", &enum_case_Different_value);

	zval enum_case_LessStrict_value;
	ZVAL_LONG(&enum_case_LessStrict_value, 1);
	zend_enum_add_case_cstr(class_entry, "LessStrict", &enum_case_LessStrict_value);

	zval enum_case_MoreStrict_value;
	ZVAL_LONG(&enum_case_MoreStrict_value, 2);
	zend_enum_add_case_cstr(class_entry, "MoreStrict", &enum_case_MoreStrict_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkSorterChange(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GtkSorterChange", IS_LONG, NULL);

	zval enum_case_Different_value;
	ZVAL_LONG(&enum_case_Different_value, 0);
	zend_enum_add_case_cstr(class_entry, "Different", &enum_case_Different_value);

	zval enum_case_Inverted_value;
	ZVAL_LONG(&enum_case_Inverted_value, 1);
	zend_enum_add_case_cstr(class_entry, "Inverted", &enum_case_Inverted_value);

	zval enum_case_LessStrict_value;
	ZVAL_LONG(&enum_case_LessStrict_value, 2);
	zend_enum_add_case_cstr(class_entry, "LessStrict", &enum_case_LessStrict_value);

	zval enum_case_MoreStrict_value;
	ZVAL_LONG(&enum_case_MoreStrict_value, 3);
	zend_enum_add_case_cstr(class_entry, "MoreStrict", &enum_case_MoreStrict_value);

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

static zend_class_entry *register_class_Gtk4_GError(zend_class_entry *class_entry_RuntimeException)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GError", class_Gtk4_GError_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_RuntimeException, 0);

	zval property_domain_default_value;
	ZVAL_EMPTY_STRING(&property_domain_default_value);
	zend_string *property_domain_name = zend_string_init("domain", sizeof("domain") - 1, 1);
	zend_declare_typed_property(class_entry, property_domain_name, &property_domain_default_value, ZEND_ACC_PROTECTED, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_STRING));
	zend_string_release(property_domain_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkTexture(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkTexture", class_Gtk4_GdkTexture_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PhpValue(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "PhpValue", class_Gtk4_PhpValue_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GListModel(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GListModel", class_Gtk4_GListModel_methods);
	class_entry = zend_register_internal_interface(&ce);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GListStore(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GListModel)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GListStore", class_Gtk4_GListStore_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_NOT_SERIALIZABLE);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GListModel);

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

static zend_class_entry *register_class_Gtk4_GtkBox(zend_class_entry *class_entry_Gtk4_GtkWidget)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkBox", class_Gtk4_GtkBox_methods);
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

static zend_class_entry *register_class_Gtk4_CairoContext(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "CairoContext", class_Gtk4_CairoContext_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkDrawingArea(zend_class_entry *class_entry_Gtk4_GtkWidget)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkDrawingArea", class_Gtk4_GtkDrawingArea_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GtkWidget, ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkFilter(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkFilter", class_Gtk4_GtkFilter_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_ABSTRACT|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkCustomFilter(zend_class_entry *class_entry_Gtk4_GtkFilter)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkCustomFilter", class_Gtk4_GtkCustomFilter_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GtkFilter, ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkFilterListModel(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GListModel)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkFilterListModel", class_Gtk4_GtkFilterListModel_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_NOT_SERIALIZABLE);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GListModel);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkSorter(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkSorter", class_Gtk4_GtkSorter_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_ABSTRACT|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkCustomSorter(zend_class_entry *class_entry_Gtk4_GtkSorter)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkCustomSorter", class_Gtk4_GtkCustomSorter_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GtkSorter, ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkSortListModel(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GListModel)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkSortListModel", class_Gtk4_GtkSortListModel_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_NOT_SERIALIZABLE);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GListModel);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkWindow(zend_class_entry *class_entry_Gtk4_GtkWidget)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkWindow", class_Gtk4_GtkWindow_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GtkWidget, ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}
