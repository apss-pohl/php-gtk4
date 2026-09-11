/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 51bb6da828e9e01c7327be4fc3ed530f7d8308ef */

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GObject___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

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

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GObject_list_signals, 0, 0, IS_ARRAY, 0)
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

#define arginfo_class_Gtk4_Gtk_get_major_version arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_Gtk_get_minor_version arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_Gtk_get_micro_version arginfo_class_Gtk4_GParamSpec_get_flags

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_Gtk_check_version, 0, 3, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, required_major, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, required_minor, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, required_micro, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_Gtk_set_exception_handler, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, handler, IS_CALLABLE, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_Gtk_set_exception_mode, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, mode, Gtk4\\ExceptionMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_Gtk_get_exception_mode, 0, 0, Gtk4\\ExceptionMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_Gtk_add_provider_for_display, 0, 2, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, display, Gtk4\\GdkDisplay, 0)
	ZEND_ARG_OBJ_INFO(0, provider, Gtk4\\GtkStyleProvider, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, priority, IS_LONG, 0, "Gtk4\\GtkStyleProviderPriority::APPLICATION")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_Gtk_remove_provider_for_display, 0, 2, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, display, Gtk4\\GdkDisplay, 0)
	ZEND_ARG_OBJ_INFO(0, provider, Gtk4\\GtkStyleProvider, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_Gtk_testing_iterate_nested, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, iterations, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_Gtk_testing_run_dispose, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, object, Gtk4\\GtkWidget, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GLib_idle_add, 0, 1, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, callback, IS_CALLABLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GLib_timeout_add, 0, 2, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, interval_ms, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, callback, IS_CALLABLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GLib_io_add_watch, 0, 3, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, stream, IS_MIXED, 0)
	ZEND_ARG_TYPE_INFO(0, condition, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, callback, IS_CALLABLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GLib_source_remove, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, source_id, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GLib_main_context_iteration, 0, 0, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, may_block, _IS_BOOL, 0, "false")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GLib_set_prgname, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, prgname, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GLib_get_prgname arginfo_class_Gtk4_GParamSpec_get_nick

#define arginfo_class_Gtk4_GMainLoop___construct arginfo_class_Gtk4_GObject___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMainLoop_run, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GMainLoop_quit arginfo_class_Gtk4_GMainLoop_run

#define arginfo_class_Gtk4_GMainLoop_is_running arginfo_class_Gtk4_GParamSpec_is_readable

#define arginfo_class_Gtk4_GError_getDomain arginfo_class_Gtk4_GParamSpec_get_name

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_PhpValue___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, value, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PhpValue_get_value arginfo_class_Gtk4_GParamSpec_get_default_value

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PhpValue_set_value, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, value, IS_MIXED, 0)
ZEND_END_ARG_INFO()

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

#define arginfo_class_Gtk4_PangoRectangle___construct arginfo_class_Gtk4_GdkRectangle___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoRectangle_to_pixels, 0, 0, Gtk4\\PangoRectangle, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GskRoundedRect___construct, 0, 0, 1)
	ZEND_ARG_OBJ_INFO(0, bounds, Gtk4\\GrapheneRect, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, top_left, IS_DOUBLE, 0, "0.0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, top_right, IS_DOUBLE, 0, "0.0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, bottom_right, IS_DOUBLE, 0, "0.0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, bottom_left, IS_DOUBLE, 0, "0.0")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskRoundedRect_get_bounds, 0, 0, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskRoundedRect_get_corner, 0, 1, Gtk4\\GrapheneSize, 0)
	ZEND_ARG_OBJ_INFO(0, corner, Gtk4\\GskCorner, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskRoundedRect_is_rectilinear arginfo_class_Gtk4_GParamSpec_is_readable

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskRoundedRect_contains_point, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, point, Gtk4\\GraphenePoint, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskRoundedRect_contains_rect, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, rect, Gtk4\\GrapheneRect, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GskRoundedRect_intersects_rect arginfo_class_Gtk4_GskRoundedRect_contains_rect

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskRoundedRect_normalize, 0, 0, Gtk4\\GskRoundedRect, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskRoundedRect_offset, 0, 2, Gtk4\\GskRoundedRect, 0)
	ZEND_ARG_TYPE_INFO(0, dx, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, dy, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GskRoundedRect_shrink, 0, 4, Gtk4\\GskRoundedRect, 0)
	ZEND_ARG_TYPE_INFO(0, top, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, right, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, bottom, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, left, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GskRoundedRect_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, other, Gtk4\\GskRoundedRect, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkEvent_get_event_type, 0, 0, Gtk4\\GdkEventType, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkEvent_get_time arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_GdkEvent_get_modifier_state arginfo_class_Gtk4_GParamSpec_get_flags

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkEvent_get_position, 0, 0, IS_ARRAY, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkEvent_get_pointer_emulated arginfo_class_Gtk4_GParamSpec_is_readable

#define arginfo_class_Gtk4_GdkEvent_triggers_context_menu arginfo_class_Gtk4_GParamSpec_is_readable

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkEvent_get_display, 0, 0, Gtk4\\GdkDisplay, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkKeyEvent_get_keyval arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_GdkKeyEvent_get_keycode arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_GdkKeyEvent_get_consumed_modifiers arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_GdkKeyEvent_get_layout arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_GdkKeyEvent_get_level arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_GdkKeyEvent_is_modifier arginfo_class_Gtk4_GParamSpec_is_readable

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkKeyEvent_matches, 0, 2, Gtk4\\GdkKeyMatch, 0)
	ZEND_ARG_TYPE_INFO(0, keyval, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, modifiers, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkKeyEvent_get_match arginfo_class_Gtk4_GdkEvent_get_position

#define arginfo_class_Gtk4_GdkButtonEvent_get_button arginfo_class_Gtk4_GParamSpec_get_flags

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkScrollEvent_get_direction, 0, 0, Gtk4\\GdkScrollDirection, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkScrollEvent_get_deltas arginfo_class_Gtk4_GObject_list_signals

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkScrollEvent_get_unit, 0, 0, Gtk4\\GdkScrollUnit, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkScrollEvent_is_stop arginfo_class_Gtk4_GParamSpec_is_readable

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkCrossingEvent_get_mode, 0, 0, Gtk4\\GdkCrossingMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkCrossingEvent_get_detail, 0, 0, Gtk4\\GdkNotifyType, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkCrossingEvent_get_focus arginfo_class_Gtk4_GParamSpec_is_readable

#define arginfo_class_Gtk4_GdkFocusEvent_get_in arginfo_class_Gtk4_GParamSpec_is_readable

#define arginfo_class_Gtk4_GdkTouchEvent_get_emulating_pointer arginfo_class_Gtk4_GParamSpec_is_readable

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GdkTouchpadEvent_get_gesture_phase, 0, 0, Gtk4\\GdkTouchpadGesturePhase, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkTouchpadEvent_get_n_fingers arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_GdkTouchpadEvent_get_deltas arginfo_class_Gtk4_GObject_list_signals

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GdkTouchpadEvent_get_pinch_angle_delta, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GdkTouchpadEvent_get_pinch_scale arginfo_class_Gtk4_GdkTouchpadEvent_get_pinch_angle_delta

#define arginfo_class_Gtk4_GdkPadEvent_get_axis_value arginfo_class_Gtk4_GObject_list_signals

#define arginfo_class_Gtk4_GdkPadEvent_get_button arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_GdkPadEvent_get_group_mode arginfo_class_Gtk4_GObject_list_signals

#define arginfo_class_Gtk4_GdkGrabBrokenEvent_get_implicit arginfo_class_Gtk4_GParamSpec_is_readable

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

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_CairoContext_show_text, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, text, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_CairoContext_set_source_surface, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, surface, Gtk4\\CairoSurface, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, x, IS_DOUBLE, 0, "0.0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, y, IS_DOUBLE, 0, "0.0")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_CairoSurface_get_width arginfo_class_Gtk4_GParamSpec_get_flags

#define arginfo_class_Gtk4_CairoSurface_get_height arginfo_class_Gtk4_GParamSpec_get_flags

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_CairoSurface_write_to_png, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, filename, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkCssSection_to_string arginfo_class_Gtk4_GParamSpec_get_name

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkCssSection_get_parent, 0, 0, Gtk4\\GtkCssSection, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkCssSection_get_start_location arginfo_class_Gtk4_GObject_list_signals

#define arginfo_class_Gtk4_GtkCssSection_get_end_location arginfo_class_Gtk4_GObject_list_signals

ZEND_METHOD(Gtk4_GObject, __construct);
ZEND_METHOD(Gtk4_GObject, connect);
ZEND_METHOD(Gtk4_GObject, connect_after);
ZEND_METHOD(Gtk4_GObject, emit);
ZEND_METHOD(Gtk4_GObject, handler_disconnect);
ZEND_METHOD(Gtk4_GObject, list_signals);
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
ZEND_METHOD(Gtk4_Gtk, get_major_version);
ZEND_METHOD(Gtk4_Gtk, get_minor_version);
ZEND_METHOD(Gtk4_Gtk, get_micro_version);
ZEND_METHOD(Gtk4_Gtk, check_version);
ZEND_METHOD(Gtk4_Gtk, set_exception_handler);
ZEND_METHOD(Gtk4_Gtk, set_exception_mode);
ZEND_METHOD(Gtk4_Gtk, get_exception_mode);
ZEND_METHOD(Gtk4_Gtk, add_provider_for_display);
ZEND_METHOD(Gtk4_Gtk, remove_provider_for_display);
ZEND_METHOD(Gtk4_Gtk, testing_iterate_nested);
ZEND_METHOD(Gtk4_Gtk, testing_run_dispose);
ZEND_METHOD(Gtk4_GLib, idle_add);
ZEND_METHOD(Gtk4_GLib, timeout_add);
ZEND_METHOD(Gtk4_GLib, io_add_watch);
ZEND_METHOD(Gtk4_GLib, source_remove);
ZEND_METHOD(Gtk4_GLib, main_context_iteration);
ZEND_METHOD(Gtk4_GLib, set_prgname);
ZEND_METHOD(Gtk4_GLib, get_prgname);
ZEND_METHOD(Gtk4_GMainLoop, __construct);
ZEND_METHOD(Gtk4_GMainLoop, run);
ZEND_METHOD(Gtk4_GMainLoop, quit);
ZEND_METHOD(Gtk4_GMainLoop, is_running);
ZEND_METHOD(Gtk4_GError, getDomain);
ZEND_METHOD(Gtk4_PhpValue, __construct);
ZEND_METHOD(Gtk4_PhpValue, get_value);
ZEND_METHOD(Gtk4_PhpValue, set_value);
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
ZEND_METHOD(Gtk4_PangoRectangle, __construct);
ZEND_METHOD(Gtk4_PangoRectangle, to_pixels);
ZEND_METHOD(Gtk4_GskRoundedRect, __construct);
ZEND_METHOD(Gtk4_GskRoundedRect, get_bounds);
ZEND_METHOD(Gtk4_GskRoundedRect, get_corner);
ZEND_METHOD(Gtk4_GskRoundedRect, is_rectilinear);
ZEND_METHOD(Gtk4_GskRoundedRect, contains_point);
ZEND_METHOD(Gtk4_GskRoundedRect, contains_rect);
ZEND_METHOD(Gtk4_GskRoundedRect, intersects_rect);
ZEND_METHOD(Gtk4_GskRoundedRect, normalize);
ZEND_METHOD(Gtk4_GskRoundedRect, offset);
ZEND_METHOD(Gtk4_GskRoundedRect, shrink);
ZEND_METHOD(Gtk4_GskRoundedRect, equal);
ZEND_METHOD(Gtk4_GdkEvent, get_event_type);
ZEND_METHOD(Gtk4_GdkEvent, get_time);
ZEND_METHOD(Gtk4_GdkEvent, get_modifier_state);
ZEND_METHOD(Gtk4_GdkEvent, get_position);
ZEND_METHOD(Gtk4_GdkEvent, get_pointer_emulated);
ZEND_METHOD(Gtk4_GdkEvent, triggers_context_menu);
ZEND_METHOD(Gtk4_GdkEvent, get_display);
ZEND_METHOD(Gtk4_GdkKeyEvent, get_keyval);
ZEND_METHOD(Gtk4_GdkKeyEvent, get_keycode);
ZEND_METHOD(Gtk4_GdkKeyEvent, get_consumed_modifiers);
ZEND_METHOD(Gtk4_GdkKeyEvent, get_layout);
ZEND_METHOD(Gtk4_GdkKeyEvent, get_level);
ZEND_METHOD(Gtk4_GdkKeyEvent, is_modifier);
ZEND_METHOD(Gtk4_GdkKeyEvent, matches);
ZEND_METHOD(Gtk4_GdkKeyEvent, get_match);
ZEND_METHOD(Gtk4_GdkButtonEvent, get_button);
ZEND_METHOD(Gtk4_GdkScrollEvent, get_direction);
ZEND_METHOD(Gtk4_GdkScrollEvent, get_deltas);
ZEND_METHOD(Gtk4_GdkScrollEvent, get_unit);
ZEND_METHOD(Gtk4_GdkScrollEvent, is_stop);
ZEND_METHOD(Gtk4_GdkCrossingEvent, get_mode);
ZEND_METHOD(Gtk4_GdkCrossingEvent, get_detail);
ZEND_METHOD(Gtk4_GdkCrossingEvent, get_focus);
ZEND_METHOD(Gtk4_GdkFocusEvent, get_in);
ZEND_METHOD(Gtk4_GdkTouchEvent, get_emulating_pointer);
ZEND_METHOD(Gtk4_GdkTouchpadEvent, get_gesture_phase);
ZEND_METHOD(Gtk4_GdkTouchpadEvent, get_n_fingers);
ZEND_METHOD(Gtk4_GdkTouchpadEvent, get_deltas);
ZEND_METHOD(Gtk4_GdkTouchpadEvent, get_pinch_angle_delta);
ZEND_METHOD(Gtk4_GdkTouchpadEvent, get_pinch_scale);
ZEND_METHOD(Gtk4_GdkPadEvent, get_axis_value);
ZEND_METHOD(Gtk4_GdkPadEvent, get_button);
ZEND_METHOD(Gtk4_GdkPadEvent, get_group_mode);
ZEND_METHOD(Gtk4_GdkGrabBrokenEvent, get_implicit);
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
ZEND_METHOD(Gtk4_CairoContext, set_source_surface);
ZEND_METHOD(Gtk4_CairoSurface, get_width);
ZEND_METHOD(Gtk4_CairoSurface, get_height);
ZEND_METHOD(Gtk4_CairoSurface, write_to_png);
ZEND_METHOD(Gtk4_GtkCssSection, to_string);
ZEND_METHOD(Gtk4_GtkCssSection, get_parent);
ZEND_METHOD(Gtk4_GtkCssSection, get_start_location);
ZEND_METHOD(Gtk4_GtkCssSection, get_end_location);

static const zend_function_entry class_Gtk4_GObject_methods[] = {
	ZEND_ME(Gtk4_GObject, __construct, arginfo_class_Gtk4_GObject___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GObject, connect, arginfo_class_Gtk4_GObject_connect, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GObject, connect_after, arginfo_class_Gtk4_GObject_connect_after, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GObject, emit, arginfo_class_Gtk4_GObject_emit, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GObject, handler_disconnect, arginfo_class_Gtk4_GObject_handler_disconnect, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GObject, list_signals, arginfo_class_Gtk4_GObject_list_signals, ZEND_ACC_PUBLIC)
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
	ZEND_ME(Gtk4_Gtk, get_major_version, arginfo_class_Gtk4_Gtk_get_major_version, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, get_minor_version, arginfo_class_Gtk4_Gtk_get_minor_version, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, get_micro_version, arginfo_class_Gtk4_Gtk_get_micro_version, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, check_version, arginfo_class_Gtk4_Gtk_check_version, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, set_exception_handler, arginfo_class_Gtk4_Gtk_set_exception_handler, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, set_exception_mode, arginfo_class_Gtk4_Gtk_set_exception_mode, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, get_exception_mode, arginfo_class_Gtk4_Gtk_get_exception_mode, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, add_provider_for_display, arginfo_class_Gtk4_Gtk_add_provider_for_display, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, remove_provider_for_display, arginfo_class_Gtk4_Gtk_remove_provider_for_display, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, testing_iterate_nested, arginfo_class_Gtk4_Gtk_testing_iterate_nested, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_Gtk, testing_run_dispose, arginfo_class_Gtk4_Gtk_testing_run_dispose, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GLib_methods[] = {
	ZEND_ME(Gtk4_GLib, idle_add, arginfo_class_Gtk4_GLib_idle_add, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GLib, timeout_add, arginfo_class_Gtk4_GLib_timeout_add, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GLib, io_add_watch, arginfo_class_Gtk4_GLib_io_add_watch, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GLib, source_remove, arginfo_class_Gtk4_GLib_source_remove, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GLib, main_context_iteration, arginfo_class_Gtk4_GLib_main_context_iteration, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GLib, set_prgname, arginfo_class_Gtk4_GLib_set_prgname, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GLib, get_prgname, arginfo_class_Gtk4_GLib_get_prgname, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
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

static const zend_function_entry class_Gtk4_PhpValue_methods[] = {
	ZEND_ME(Gtk4_PhpValue, __construct, arginfo_class_Gtk4_PhpValue___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PhpValue, get_value, arginfo_class_Gtk4_PhpValue_get_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PhpValue, set_value, arginfo_class_Gtk4_PhpValue_set_value, ZEND_ACC_PUBLIC)
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

static const zend_function_entry class_Gtk4_PangoRectangle_methods[] = {
	ZEND_ME(Gtk4_PangoRectangle, __construct, arginfo_class_Gtk4_PangoRectangle___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoRectangle, to_pixels, arginfo_class_Gtk4_PangoRectangle_to_pixels, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GskRoundedRect_methods[] = {
	ZEND_ME(Gtk4_GskRoundedRect, __construct, arginfo_class_Gtk4_GskRoundedRect___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRoundedRect, get_bounds, arginfo_class_Gtk4_GskRoundedRect_get_bounds, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRoundedRect, get_corner, arginfo_class_Gtk4_GskRoundedRect_get_corner, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRoundedRect, is_rectilinear, arginfo_class_Gtk4_GskRoundedRect_is_rectilinear, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRoundedRect, contains_point, arginfo_class_Gtk4_GskRoundedRect_contains_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRoundedRect, contains_rect, arginfo_class_Gtk4_GskRoundedRect_contains_rect, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRoundedRect, intersects_rect, arginfo_class_Gtk4_GskRoundedRect_intersects_rect, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRoundedRect, normalize, arginfo_class_Gtk4_GskRoundedRect_normalize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRoundedRect, offset, arginfo_class_Gtk4_GskRoundedRect_offset, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRoundedRect, shrink, arginfo_class_Gtk4_GskRoundedRect_shrink, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GskRoundedRect, equal, arginfo_class_Gtk4_GskRoundedRect_equal, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkEvent_methods[] = {
	ZEND_ME(Gtk4_GdkEvent, get_event_type, arginfo_class_Gtk4_GdkEvent_get_event_type, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkEvent, get_time, arginfo_class_Gtk4_GdkEvent_get_time, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkEvent, get_modifier_state, arginfo_class_Gtk4_GdkEvent_get_modifier_state, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkEvent, get_position, arginfo_class_Gtk4_GdkEvent_get_position, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkEvent, get_pointer_emulated, arginfo_class_Gtk4_GdkEvent_get_pointer_emulated, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkEvent, triggers_context_menu, arginfo_class_Gtk4_GdkEvent_triggers_context_menu, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkEvent, get_display, arginfo_class_Gtk4_GdkEvent_get_display, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkKeyEvent_methods[] = {
	ZEND_ME(Gtk4_GdkKeyEvent, get_keyval, arginfo_class_Gtk4_GdkKeyEvent_get_keyval, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkKeyEvent, get_keycode, arginfo_class_Gtk4_GdkKeyEvent_get_keycode, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkKeyEvent, get_consumed_modifiers, arginfo_class_Gtk4_GdkKeyEvent_get_consumed_modifiers, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkKeyEvent, get_layout, arginfo_class_Gtk4_GdkKeyEvent_get_layout, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkKeyEvent, get_level, arginfo_class_Gtk4_GdkKeyEvent_get_level, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkKeyEvent, is_modifier, arginfo_class_Gtk4_GdkKeyEvent_is_modifier, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkKeyEvent, matches, arginfo_class_Gtk4_GdkKeyEvent_matches, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkKeyEvent, get_match, arginfo_class_Gtk4_GdkKeyEvent_get_match, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkButtonEvent_methods[] = {
	ZEND_ME(Gtk4_GdkButtonEvent, get_button, arginfo_class_Gtk4_GdkButtonEvent_get_button, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkScrollEvent_methods[] = {
	ZEND_ME(Gtk4_GdkScrollEvent, get_direction, arginfo_class_Gtk4_GdkScrollEvent_get_direction, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkScrollEvent, get_deltas, arginfo_class_Gtk4_GdkScrollEvent_get_deltas, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkScrollEvent, get_unit, arginfo_class_Gtk4_GdkScrollEvent_get_unit, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkScrollEvent, is_stop, arginfo_class_Gtk4_GdkScrollEvent_is_stop, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkCrossingEvent_methods[] = {
	ZEND_ME(Gtk4_GdkCrossingEvent, get_mode, arginfo_class_Gtk4_GdkCrossingEvent_get_mode, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkCrossingEvent, get_detail, arginfo_class_Gtk4_GdkCrossingEvent_get_detail, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkCrossingEvent, get_focus, arginfo_class_Gtk4_GdkCrossingEvent_get_focus, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkFocusEvent_methods[] = {
	ZEND_ME(Gtk4_GdkFocusEvent, get_in, arginfo_class_Gtk4_GdkFocusEvent_get_in, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkTouchEvent_methods[] = {
	ZEND_ME(Gtk4_GdkTouchEvent, get_emulating_pointer, arginfo_class_Gtk4_GdkTouchEvent_get_emulating_pointer, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkTouchpadEvent_methods[] = {
	ZEND_ME(Gtk4_GdkTouchpadEvent, get_gesture_phase, arginfo_class_Gtk4_GdkTouchpadEvent_get_gesture_phase, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTouchpadEvent, get_n_fingers, arginfo_class_Gtk4_GdkTouchpadEvent_get_n_fingers, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTouchpadEvent, get_deltas, arginfo_class_Gtk4_GdkTouchpadEvent_get_deltas, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTouchpadEvent, get_pinch_angle_delta, arginfo_class_Gtk4_GdkTouchpadEvent_get_pinch_angle_delta, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkTouchpadEvent, get_pinch_scale, arginfo_class_Gtk4_GdkTouchpadEvent_get_pinch_scale, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkPadEvent_methods[] = {
	ZEND_ME(Gtk4_GdkPadEvent, get_axis_value, arginfo_class_Gtk4_GdkPadEvent_get_axis_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPadEvent, get_button, arginfo_class_Gtk4_GdkPadEvent_get_button, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GdkPadEvent, get_group_mode, arginfo_class_Gtk4_GdkPadEvent_get_group_mode, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GdkGrabBrokenEvent_methods[] = {
	ZEND_ME(Gtk4_GdkGrabBrokenEvent, get_implicit, arginfo_class_Gtk4_GdkGrabBrokenEvent_get_implicit, ZEND_ACC_PUBLIC)
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
	ZEND_ME(Gtk4_CairoContext, set_source_surface, arginfo_class_Gtk4_CairoContext_set_source_surface, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_CairoSurface_methods[] = {
	ZEND_ME(Gtk4_CairoSurface, get_width, arginfo_class_Gtk4_CairoSurface_get_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoSurface, get_height, arginfo_class_Gtk4_CairoSurface_get_height, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_CairoSurface, write_to_png, arginfo_class_Gtk4_CairoSurface_write_to_png, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkCssSection_methods[] = {
	ZEND_ME(Gtk4_GtkCssSection, to_string, arginfo_class_Gtk4_GtkCssSection_to_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkCssSection, get_parent, arginfo_class_Gtk4_GtkCssSection_get_parent, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkCssSection, get_start_location, arginfo_class_Gtk4_GtkCssSection_get_start_location, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkCssSection, get_end_location, arginfo_class_Gtk4_GtkCssSection_get_end_location, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static void register_gtk4_symbols(int module_number)
{
	REGISTER_STRING_CONSTANT("Gtk4\\VERSION", "0.4.0-dev", CONST_PERSISTENT);
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

static zend_class_entry *register_class_Gtk4_PhpValue(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "PhpValue", class_Gtk4_PhpValue_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

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

static zend_class_entry *register_class_Gtk4_PangoRectangle(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "PangoRectangle", class_Gtk4_PangoRectangle_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GskRoundedRect(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GskRoundedRect", class_Gtk4_GskRoundedRect_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkEvent(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkEvent", class_Gtk4_GdkEvent_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkKeyEvent(zend_class_entry *class_entry_Gtk4_GdkEvent)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkKeyEvent", class_Gtk4_GdkKeyEvent_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GdkEvent, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkButtonEvent(zend_class_entry *class_entry_Gtk4_GdkEvent)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkButtonEvent", class_Gtk4_GdkButtonEvent_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GdkEvent, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkScrollEvent(zend_class_entry *class_entry_Gtk4_GdkEvent)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkScrollEvent", class_Gtk4_GdkScrollEvent_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GdkEvent, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkCrossingEvent(zend_class_entry *class_entry_Gtk4_GdkEvent)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkCrossingEvent", class_Gtk4_GdkCrossingEvent_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GdkEvent, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkFocusEvent(zend_class_entry *class_entry_Gtk4_GdkEvent)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkFocusEvent", class_Gtk4_GdkFocusEvent_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GdkEvent, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkTouchEvent(zend_class_entry *class_entry_Gtk4_GdkEvent)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkTouchEvent", class_Gtk4_GdkTouchEvent_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GdkEvent, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkTouchpadEvent(zend_class_entry *class_entry_Gtk4_GdkEvent)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkTouchpadEvent", class_Gtk4_GdkTouchpadEvent_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GdkEvent, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkPadEvent(zend_class_entry *class_entry_Gtk4_GdkEvent)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkPadEvent", class_Gtk4_GdkPadEvent_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GdkEvent, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkGrabBrokenEvent(zend_class_entry *class_entry_Gtk4_GdkEvent)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkGrabBrokenEvent", class_Gtk4_GdkGrabBrokenEvent_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GdkEvent, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GdkEventSequence(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GdkEventSequence", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GIOCondition(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GIOCondition", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_IN_value;
	ZVAL_LONG(&const_IN_value, 1);
	zend_string *const_IN_name = zend_string_init_interned("IN", sizeof("IN") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_IN_name, &const_IN_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_IN_name);

	zval const_PRI_value;
	ZVAL_LONG(&const_PRI_value, 2);
	zend_string *const_PRI_name = zend_string_init_interned("PRI", sizeof("PRI") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_PRI_name, &const_PRI_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_PRI_name);

	zval const_OUT_value;
	ZVAL_LONG(&const_OUT_value, 4);
	zend_string *const_OUT_name = zend_string_init_interned("OUT", sizeof("OUT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_OUT_name, &const_OUT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_OUT_name);

	zval const_ERR_value;
	ZVAL_LONG(&const_ERR_value, 8);
	zend_string *const_ERR_name = zend_string_init_interned("ERR", sizeof("ERR") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_ERR_name, &const_ERR_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_ERR_name);

	zval const_HUP_value;
	ZVAL_LONG(&const_HUP_value, 16);
	zend_string *const_HUP_name = zend_string_init_interned("HUP", sizeof("HUP") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_HUP_name, &const_HUP_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_HUP_name);

	zval const_NVAL_value;
	ZVAL_LONG(&const_NVAL_value, 32);
	zend_string *const_NVAL_name = zend_string_init_interned("NVAL", sizeof("NVAL") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NVAL_name, &const_NVAL_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NVAL_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_CairoContext(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "CairoContext", class_Gtk4_CairoContext_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_CairoSurface(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "CairoSurface", class_Gtk4_CairoSurface_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkStyleProviderPriority(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkStyleProviderPriority", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_FALLBACK_value;
	ZVAL_LONG(&const_FALLBACK_value, 1);
	zend_string *const_FALLBACK_name = zend_string_init_interned("FALLBACK", sizeof("FALLBACK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_FALLBACK_name, &const_FALLBACK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_FALLBACK_name);

	zval const_THEME_value;
	ZVAL_LONG(&const_THEME_value, 200);
	zend_string *const_THEME_name = zend_string_init_interned("THEME", sizeof("THEME") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_THEME_name, &const_THEME_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_THEME_name);

	zval const_SETTINGS_value;
	ZVAL_LONG(&const_SETTINGS_value, 400);
	zend_string *const_SETTINGS_name = zend_string_init_interned("SETTINGS", sizeof("SETTINGS") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_SETTINGS_name, &const_SETTINGS_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_SETTINGS_name);

	zval const_APPLICATION_value;
	ZVAL_LONG(&const_APPLICATION_value, 600);
	zend_string *const_APPLICATION_name = zend_string_init_interned("APPLICATION", sizeof("APPLICATION") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_APPLICATION_name, &const_APPLICATION_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_APPLICATION_name);

	zval const_USER_value;
	ZVAL_LONG(&const_USER_value, 800);
	zend_string *const_USER_name = zend_string_init_interned("USER", sizeof("USER") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_USER_name, &const_USER_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_USER_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkCssSection(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkCssSection", class_Gtk4_GtkCssSection_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}
