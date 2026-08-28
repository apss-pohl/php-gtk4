/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 35902d1ddcd070de16f64b577e8686d39a7ba874 */

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkApplication___construct, 0, 0, 2)
	ZEND_ARG_TYPE_INFO(0, application_id, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_add_window, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, window, Gtk4\\GtkWindow, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_get_accels_for_action, 0, 1, IS_ARRAY, 0)
	ZEND_ARG_TYPE_INFO(0, detailed_action_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_get_actions_for_accel, 0, 1, IS_ARRAY, 0)
	ZEND_ARG_TYPE_INFO(0, accel, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkApplication_get_active_window, 0, 0, Gtk4\\GtkWindow, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkApplication_get_window_by_id, 0, 1, Gtk4\\GtkWindow, 1)
	ZEND_ARG_TYPE_INFO(0, id, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_get_windows, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkApplication_list_action_descriptions arginfo_class_Gtk4_GtkApplication_get_windows

#define arginfo_class_Gtk4_GtkApplication_remove_window arginfo_class_Gtk4_GtkApplication_add_window

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_set_accels_for_action, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, detailed_action_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, accels, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_action_added, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_action_enabled_changed, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, enabled, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkApplication_action_removed arginfo_class_Gtk4_GtkApplication_action_added

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_action_state_changed, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, state, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_activate_action, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameter, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_change_action_state, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, value, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_get_action_enabled, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_get_action_parameter_type, 0, 1, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_get_action_state, 0, 1, IS_MIXED, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkApplication_get_action_state_hint arginfo_class_Gtk4_GtkApplication_get_action_state

#define arginfo_class_Gtk4_GtkApplication_get_action_state_type arginfo_class_Gtk4_GtkApplication_get_action_parameter_type

#define arginfo_class_Gtk4_GtkApplication_has_action arginfo_class_Gtk4_GtkApplication_get_action_enabled

#define arginfo_class_Gtk4_GtkApplication_list_actions arginfo_class_Gtk4_GtkApplication_get_windows

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkApplication_add_action, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, action, Gtk4\\GAction, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkApplication_lookup_action, 0, 1, Gtk4\\GAction, 1)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkApplication_remove_action arginfo_class_Gtk4_GtkApplication_action_added

#define arginfo_class_Gtk4_GtkApplication_vfunc_window_added arginfo_class_Gtk4_GtkApplication_add_window

#define arginfo_class_Gtk4_GtkApplication_vfunc_window_removed arginfo_class_Gtk4_GtkApplication_add_window

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkBox___construct, 0, 0, 2)
	ZEND_ARG_OBJ_INFO(0, orientation, Gtk4\\GtkOrientation, 0)
	ZEND_ARG_TYPE_INFO(0, spacing, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkBox_append, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GtkWidget, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkBox_get_baseline_child, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkBox_get_baseline_position, 0, 0, Gtk4\\GtkBaselinePosition, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkBox_get_homogeneous, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkBox_get_spacing arginfo_class_Gtk4_GtkBox_get_baseline_child

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkBox_insert_child_after, 0, 2, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GtkWidget, 0)
	ZEND_ARG_OBJ_INFO(0, sibling, Gtk4\\GtkWidget, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkBox_prepend arginfo_class_Gtk4_GtkBox_append

#define arginfo_class_Gtk4_GtkBox_remove arginfo_class_Gtk4_GtkBox_append

#define arginfo_class_Gtk4_GtkBox_reorder_child_after arginfo_class_Gtk4_GtkBox_insert_child_after

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkBox_set_baseline_child, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, child, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkBox_set_baseline_position, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, position, Gtk4\\GtkBaselinePosition, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkBox_set_homogeneous, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, homogeneous, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkBox_set_spacing, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, spacing, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkBox_get_children arginfo_class_Gtk4_GtkApplication_get_windows

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkBox_get_orientation, 0, 0, Gtk4\\GtkOrientation, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkBox_set_orientation, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, orientation, Gtk4\\GtkOrientation, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkButton___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkButton_new_from_icon_name, 0, 1, Gtk4\\GtkButton, 0)
	ZEND_ARG_TYPE_INFO(0, icon_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkButton_new_with_label, 0, 1, Gtk4\\GtkButton, 0)
	ZEND_ARG_TYPE_INFO(0, label, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkButton_new_with_mnemonic arginfo_class_Gtk4_GtkButton_new_with_label

#define arginfo_class_Gtk4_GtkButton_get_can_shrink arginfo_class_Gtk4_GtkBox_get_homogeneous

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkButton_get_child, 0, 0, Gtk4\\GtkWidget, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkButton_get_has_frame arginfo_class_Gtk4_GtkBox_get_homogeneous

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkButton_get_icon_name, 0, 0, IS_STRING, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkButton_get_label arginfo_class_Gtk4_GtkButton_get_icon_name

#define arginfo_class_Gtk4_GtkButton_get_use_underline arginfo_class_Gtk4_GtkBox_get_homogeneous

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkButton_set_can_shrink, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, can_shrink, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkButton_set_child, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, child, Gtk4\\GtkWidget, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkButton_set_has_frame, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, has_frame, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkButton_set_icon_name, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, icon_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkButton_set_label, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, label, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkButton_set_use_underline, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, use_underline, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkButton_vfunc_activate, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkButton_vfunc_clicked arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkCssProvider___construct arginfo_class_Gtk4_GtkButton___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkCssProvider_load_from_bytes, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, data, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkCssProvider_load_from_path, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, path, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkCssProvider_load_from_resource, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, resource_path, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkCssProvider_load_from_string, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, string, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkCssProvider_load_named, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, variant, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkCssProvider_to_string, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkCustomFilter___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, match_func, IS_CALLABLE, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkCustomFilter_set_filter_func, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, match_func, IS_CALLABLE, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkCustomSorter___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, compare, IS_CALLABLE, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkCustomSorter_set_sort_func, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, compare, IS_CALLABLE, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkDrawingArea___construct arginfo_class_Gtk4_GtkButton___construct

#define arginfo_class_Gtk4_GtkDrawingArea_get_content_height arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkDrawingArea_get_content_width arginfo_class_Gtk4_GtkBox_get_baseline_child

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkDrawingArea_set_content_height, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkDrawingArea_set_content_width, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkDrawingArea_set_draw_func, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, draw_func, IS_CALLABLE, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkDrawingArea_vfunc_resize, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkFilter___construct arginfo_class_Gtk4_GtkButton___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkFilter_changed, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, change, Gtk4\\GtkFilterChange, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkFilter_get_strictness, 0, 0, Gtk4\\GtkFilterMatch, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkFilter_match, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, item, Gtk4\\GObject, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkFilter_vfunc_get_strictness arginfo_class_Gtk4_GtkFilter_get_strictness

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkFilter_vfunc_match, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, item, Gtk4\\GObject, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkFilterListModel___construct, 0, 0, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, model, Gtk4\\GListModel, 1, "null")
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, filter, Gtk4\\GtkFilter, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkFilterListModel_get_filter, 0, 0, Gtk4\\GtkFilter, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkFilterListModel_get_incremental arginfo_class_Gtk4_GtkBox_get_homogeneous

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkFilterListModel_get_model, 0, 0, Gtk4\\GListModel, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkFilterListModel_get_pending arginfo_class_Gtk4_GtkBox_get_baseline_child

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkFilterListModel_set_filter, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, filter, Gtk4\\GtkFilter, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkFilterListModel_set_incremental, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, incremental, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkFilterListModel_set_model, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, model, Gtk4\\GListModel, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkFilterListModel_get_item_type arginfo_class_Gtk4_GtkCssProvider_to_string

#define arginfo_class_Gtk4_GtkFilterListModel_get_n_items arginfo_class_Gtk4_GtkBox_get_baseline_child

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkFilterListModel_get_item, 0, 1, Gtk4\\GObject, 1)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkFilterListModel_items_changed, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, removed, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, added, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkLabel___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, str, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkLabel_new_with_mnemonic, 0, 0, Gtk4\\GtkLabel, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, str, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkLabel_get_current_uri arginfo_class_Gtk4_GtkButton_get_icon_name

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkLabel_get_ellipsize, 0, 0, Gtk4\\PangoEllipsizeMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkLabel_get_justify, 0, 0, Gtk4\\GtkJustification, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkLabel_get_label arginfo_class_Gtk4_GtkCssProvider_to_string

#define arginfo_class_Gtk4_GtkLabel_get_layout_offsets arginfo_class_Gtk4_GtkApplication_get_windows

#define arginfo_class_Gtk4_GtkLabel_get_lines arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkLabel_get_max_width_chars arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkLabel_get_mnemonic_keyval arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkLabel_get_mnemonic_widget arginfo_class_Gtk4_GtkButton_get_child

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkLabel_get_natural_wrap_mode, 0, 0, Gtk4\\GtkNaturalWrapMode, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkLabel_get_selectable arginfo_class_Gtk4_GtkBox_get_homogeneous

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_get_selection_bounds, 0, 0, IS_ARRAY, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkLabel_get_single_line_mode arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkLabel_get_text arginfo_class_Gtk4_GtkCssProvider_to_string

#define arginfo_class_Gtk4_GtkLabel_get_use_markup arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkLabel_get_use_underline arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkLabel_get_width_chars arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkLabel_get_wrap arginfo_class_Gtk4_GtkBox_get_homogeneous

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkLabel_get_wrap_mode, 0, 0, Gtk4\\PangoWrapMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_get_xalign, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkLabel_get_yalign arginfo_class_Gtk4_GtkLabel_get_xalign

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_select_region, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, start_offset, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, end_offset, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_ellipsize, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, mode, Gtk4\\PangoEllipsizeMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_justify, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, jtype, Gtk4\\GtkJustification, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_label, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, str, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_lines, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, lines, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkLabel_set_markup arginfo_class_Gtk4_GtkLabel_set_label

#define arginfo_class_Gtk4_GtkLabel_set_markup_with_mnemonic arginfo_class_Gtk4_GtkLabel_set_label

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_max_width_chars, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, n_chars, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_mnemonic_widget, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, widget, Gtk4\\GtkWidget, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_natural_wrap_mode, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, wrap_mode, Gtk4\\GtkNaturalWrapMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_selectable, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, setting, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_single_line_mode, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, single_line_mode, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkLabel_set_text arginfo_class_Gtk4_GtkLabel_set_label

#define arginfo_class_Gtk4_GtkLabel_set_text_with_mnemonic arginfo_class_Gtk4_GtkLabel_set_label

#define arginfo_class_Gtk4_GtkLabel_set_use_markup arginfo_class_Gtk4_GtkLabel_set_selectable

#define arginfo_class_Gtk4_GtkLabel_set_use_underline arginfo_class_Gtk4_GtkLabel_set_selectable

#define arginfo_class_Gtk4_GtkLabel_set_width_chars arginfo_class_Gtk4_GtkLabel_set_max_width_chars

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_wrap, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, wrap, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_wrap_mode, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, wrap_mode, Gtk4\\PangoWrapMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_xalign, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, xalign, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkLabel_set_yalign, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, yalign, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkOrientable_get_orientation arginfo_class_Gtk4_GtkBox_get_orientation

#define arginfo_class_Gtk4_GtkOrientable_set_orientation arginfo_class_Gtk4_GtkBox_set_orientation

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkRoot_get_display, 0, 0, Gtk4\\GdkDisplay, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkRoot_get_focus arginfo_class_Gtk4_GtkButton_get_child

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkRoot_set_focus, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, focus, Gtk4\\GtkWidget, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GtkSortListModel___construct, 0, 0, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, model, Gtk4\\GListModel, 1, "null")
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, sorter, Gtk4\\GtkSorter, 1, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkSortListModel_get_incremental arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkSortListModel_get_model arginfo_class_Gtk4_GtkFilterListModel_get_model

#define arginfo_class_Gtk4_GtkSortListModel_get_pending arginfo_class_Gtk4_GtkBox_get_baseline_child

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkSortListModel_get_section_sorter, 0, 0, Gtk4\\GtkSorter, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkSortListModel_get_sorter arginfo_class_Gtk4_GtkSortListModel_get_section_sorter

#define arginfo_class_Gtk4_GtkSortListModel_set_incremental arginfo_class_Gtk4_GtkFilterListModel_set_incremental

#define arginfo_class_Gtk4_GtkSortListModel_set_model arginfo_class_Gtk4_GtkFilterListModel_set_model

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkSortListModel_set_section_sorter, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, sorter, Gtk4\\GtkSorter, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkSortListModel_set_sorter arginfo_class_Gtk4_GtkSortListModel_set_section_sorter

#define arginfo_class_Gtk4_GtkSortListModel_get_item_type arginfo_class_Gtk4_GtkCssProvider_to_string

#define arginfo_class_Gtk4_GtkSortListModel_get_n_items arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkSortListModel_get_item arginfo_class_Gtk4_GtkFilterListModel_get_item

#define arginfo_class_Gtk4_GtkSortListModel_items_changed arginfo_class_Gtk4_GtkFilterListModel_items_changed

#define arginfo_class_Gtk4_GtkSorter___construct arginfo_class_Gtk4_GtkButton___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkSorter_changed, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, change, Gtk4\\GtkSorterChange, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkSorter_compare, 0, 2, Gtk4\\GtkOrdering, 0)
	ZEND_ARG_OBJ_INFO(0, item1, Gtk4\\GObject, 0)
	ZEND_ARG_OBJ_INFO(0, item2, Gtk4\\GObject, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkSorter_get_order, 0, 0, Gtk4\\GtkSorterOrder, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkSorter_vfunc_compare, 0, 2, Gtk4\\GtkOrdering, 0)
	ZEND_ARG_OBJ_INFO(0, item1, Gtk4\\GObject, 1)
	ZEND_ARG_OBJ_INFO(0, item2, Gtk4\\GObject, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkSorter_vfunc_get_order arginfo_class_Gtk4_GtkSorter_get_order

#define arginfo_class_Gtk4_GtkWidget___construct arginfo_class_Gtk4_GtkButton___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkWidget_get_default_direction, 0, 0, Gtk4\\GtkTextDirection, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_default_direction, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, dir, Gtk4\\GtkTextDirection, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_action_set_enabled arginfo_class_Gtk4_GtkApplication_action_enabled_changed

#define arginfo_class_Gtk4_GtkWidget_activate arginfo_class_Gtk4_GtkBox_get_homogeneous

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_activate_action, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, args, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_activate_default arginfo_class_Gtk4_GtkButton_vfunc_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_add_css_class, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, css_class, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_add_mnemonic_label, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, label, Gtk4\\GtkWidget, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_child_focus, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, direction, Gtk4\\GtkDirectionType, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_compute_expand, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, orientation, Gtk4\\GtkOrientation, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_contains, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_drag_check_threshold, 0, 4, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, start_x, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, start_y, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, current_x, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, current_y, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_error_bell arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWidget_get_baseline arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkWidget_get_can_focus arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_get_can_target arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_get_child_visible arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_get_css_classes arginfo_class_Gtk4_GtkApplication_get_windows

#define arginfo_class_Gtk4_GtkWidget_get_css_name arginfo_class_Gtk4_GtkCssProvider_to_string

#define arginfo_class_Gtk4_GtkWidget_get_direction arginfo_class_Gtk4_GtkWidget_get_default_direction

#define arginfo_class_Gtk4_GtkWidget_get_display arginfo_class_Gtk4_GtkRoot_get_display

#define arginfo_class_Gtk4_GtkWidget_get_first_child arginfo_class_Gtk4_GtkButton_get_child

#define arginfo_class_Gtk4_GtkWidget_get_focus_child arginfo_class_Gtk4_GtkButton_get_child

#define arginfo_class_Gtk4_GtkWidget_get_focus_on_click arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_get_focusable arginfo_class_Gtk4_GtkBox_get_homogeneous

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkWidget_get_halign, 0, 0, Gtk4\\GtkAlign, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_get_has_tooltip arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_get_height arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkWidget_get_hexpand arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_get_hexpand_set arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_get_last_child arginfo_class_Gtk4_GtkButton_get_child

#define arginfo_class_Gtk4_GtkWidget_get_mapped arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_get_margin_bottom arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkWidget_get_margin_end arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkWidget_get_margin_start arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkWidget_get_margin_top arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkWidget_get_name arginfo_class_Gtk4_GtkCssProvider_to_string

#define arginfo_class_Gtk4_GtkWidget_get_next_sibling arginfo_class_Gtk4_GtkButton_get_child

#define arginfo_class_Gtk4_GtkWidget_get_opacity arginfo_class_Gtk4_GtkLabel_get_xalign

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkWidget_get_overflow, 0, 0, Gtk4\\GtkOverflow, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_get_parent arginfo_class_Gtk4_GtkButton_get_child

#define arginfo_class_Gtk4_GtkWidget_get_prev_sibling arginfo_class_Gtk4_GtkButton_get_child

#define arginfo_class_Gtk4_GtkWidget_get_realized arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_get_receives_default arginfo_class_Gtk4_GtkBox_get_homogeneous

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkWidget_get_request_mode, 0, 0, Gtk4\\GtkSizeRequestMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkWidget_get_root, 0, 0, Gtk4\\GtkRoot, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_get_scale_factor arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkWidget_get_sensitive arginfo_class_Gtk4_GtkBox_get_homogeneous

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_get_size, 0, 1, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, orientation, Gtk4\\GtkOrientation, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_get_size_request arginfo_class_Gtk4_GtkApplication_get_windows

#define arginfo_class_Gtk4_GtkWidget_get_state_flags arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkWidget_get_tooltip_markup arginfo_class_Gtk4_GtkButton_get_icon_name

#define arginfo_class_Gtk4_GtkWidget_get_tooltip_text arginfo_class_Gtk4_GtkButton_get_icon_name

#define arginfo_class_Gtk4_GtkWidget_get_valign arginfo_class_Gtk4_GtkWidget_get_halign

#define arginfo_class_Gtk4_GtkWidget_get_vexpand arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_get_vexpand_set arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_get_visible arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_get_width arginfo_class_Gtk4_GtkBox_get_baseline_child

#define arginfo_class_Gtk4_GtkWidget_grab_focus arginfo_class_Gtk4_GtkBox_get_homogeneous

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_has_css_class, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, css_class, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_has_default arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_has_focus arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_has_visible_focus arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_in_destruction arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_init_template arginfo_class_Gtk4_GtkButton_vfunc_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_insert_action_group, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO(0, group, Gtk4\\GActionGroup, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_insert_after, 0, 2, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, parent, Gtk4\\GtkWidget, 0)
	ZEND_ARG_OBJ_INFO(0, previous_sibling, Gtk4\\GtkWidget, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_insert_before, 0, 2, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, parent, Gtk4\\GtkWidget, 0)
	ZEND_ARG_OBJ_INFO(0, next_sibling, Gtk4\\GtkWidget, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_is_ancestor, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, ancestor, Gtk4\\GtkWidget, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_is_drawable arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_is_focus arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_is_sensitive arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_is_visible arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_keynav_failed arginfo_class_Gtk4_GtkWidget_child_focus

#define arginfo_class_Gtk4_GtkWidget_list_mnemonic_labels arginfo_class_Gtk4_GtkApplication_get_windows

#define arginfo_class_Gtk4_GtkWidget_map arginfo_class_Gtk4_GtkButton_vfunc_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_measure, 0, 2, IS_ARRAY, 0)
	ZEND_ARG_OBJ_INFO(0, orientation, Gtk4\\GtkOrientation, 0)
	ZEND_ARG_TYPE_INFO(0, for_size, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_mnemonic_activate, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, group_cycling, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkWidget_observe_children, 0, 0, Gtk4\\GListModel, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_observe_controllers arginfo_class_Gtk4_GtkWidget_observe_children

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkWidget_pick, 0, 3, Gtk4\\GtkWidget, 1)
	ZEND_ARG_TYPE_INFO(0, x, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_queue_allocate arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWidget_queue_draw arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWidget_queue_resize arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWidget_realize arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWidget_remove_css_class arginfo_class_Gtk4_GtkWidget_add_css_class

#define arginfo_class_Gtk4_GtkWidget_remove_mnemonic_label arginfo_class_Gtk4_GtkWidget_add_mnemonic_label

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_remove_tick_callback, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, id, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_can_focus, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, can_focus, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_can_target, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, can_target, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_child_visible, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, child_visible, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_css_classes, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, classes, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_cursor_from_name, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_set_direction arginfo_class_Gtk4_GtkWidget_set_default_direction

#define arginfo_class_Gtk4_GtkWidget_set_focus_child arginfo_class_Gtk4_GtkButton_set_child

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_focus_on_click, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, focus_on_click, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_focusable, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, focusable, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_halign, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, align, Gtk4\\GtkAlign, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_has_tooltip, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, has_tooltip, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_hexpand, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, expand, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_hexpand_set, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, set, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_margin_bottom, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, margin, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_set_margin_end arginfo_class_Gtk4_GtkWidget_set_margin_bottom

#define arginfo_class_Gtk4_GtkWidget_set_margin_start arginfo_class_Gtk4_GtkWidget_set_margin_bottom

#define arginfo_class_Gtk4_GtkWidget_set_margin_top arginfo_class_Gtk4_GtkWidget_set_margin_bottom

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_name, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_opacity, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, opacity, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_overflow, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, overflow, Gtk4\\GtkOverflow, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_parent, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, parent, Gtk4\\GtkWidget, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_receives_default, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, receives_default, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_sensitive, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, sensitive, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_set_size_request arginfo_class_Gtk4_GtkDrawingArea_vfunc_resize

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_state_flags, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, clear, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_tooltip_markup, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, markup, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_tooltip_text, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, text, IS_STRING, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_set_valign arginfo_class_Gtk4_GtkWidget_set_halign

#define arginfo_class_Gtk4_GtkWidget_set_vexpand arginfo_class_Gtk4_GtkWidget_set_hexpand

#define arginfo_class_Gtk4_GtkWidget_set_vexpand_set arginfo_class_Gtk4_GtkWidget_set_hexpand_set

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_set_visible, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, visible, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_should_layout arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_trigger_tooltip_query arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWidget_unmap arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWidget_unparent arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWidget_unrealize arginfo_class_Gtk4_GtkButton_vfunc_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_unset_state_flags, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_vfunc_contains arginfo_class_Gtk4_GtkWidget_contains

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_vfunc_direction_changed, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, previous_direction, Gtk4\\GtkTextDirection, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_vfunc_focus arginfo_class_Gtk4_GtkWidget_child_focus

#define arginfo_class_Gtk4_GtkWidget_vfunc_get_request_mode arginfo_class_Gtk4_GtkWidget_get_request_mode

#define arginfo_class_Gtk4_GtkWidget_vfunc_grab_focus arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWidget_vfunc_keynav_failed arginfo_class_Gtk4_GtkWidget_child_focus

#define arginfo_class_Gtk4_GtkWidget_vfunc_map arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWidget_vfunc_measure arginfo_class_Gtk4_GtkWidget_measure

#define arginfo_class_Gtk4_GtkWidget_vfunc_mnemonic_activate arginfo_class_Gtk4_GtkWidget_mnemonic_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_vfunc_move_focus, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, direction, Gtk4\\GtkDirectionType, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_vfunc_realize arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWidget_vfunc_root arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWidget_vfunc_set_focus_child arginfo_class_Gtk4_GtkButton_set_child

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_vfunc_size_allocate, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, baseline, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_vfunc_state_flags_changed, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, previous_state_flags, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWidget_vfunc_system_setting_changed, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, settings, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWidget_vfunc_unmap arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWidget_vfunc_unrealize arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWidget_vfunc_unroot arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWindow___construct arginfo_class_Gtk4_GtkButton___construct

#define arginfo_class_Gtk4_GtkWindow_get_default_icon_name arginfo_class_Gtk4_GtkButton_get_icon_name

#define arginfo_class_Gtk4_GtkWindow_get_toplevels arginfo_class_Gtk4_GtkWidget_observe_children

#define arginfo_class_Gtk4_GtkWindow_list_toplevels arginfo_class_Gtk4_GtkApplication_get_windows

#define arginfo_class_Gtk4_GtkWindow_set_auto_startup_notification arginfo_class_Gtk4_GtkLabel_set_selectable

#define arginfo_class_Gtk4_GtkWindow_set_default_icon_name arginfo_class_Gtk4_GtkWidget_set_name

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_interactive_debugging, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, enable, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWindow_close arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWindow_destroy arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWindow_fullscreen arginfo_class_Gtk4_GtkButton_vfunc_activate

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GtkWindow_get_application, 0, 0, Gtk4\\GtkApplication, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWindow_get_child arginfo_class_Gtk4_GtkButton_get_child

#define arginfo_class_Gtk4_GtkWindow_get_decorated arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWindow_get_default_size arginfo_class_Gtk4_GtkApplication_get_windows

#define arginfo_class_Gtk4_GtkWindow_get_default_widget arginfo_class_Gtk4_GtkButton_get_child

#define arginfo_class_Gtk4_GtkWindow_get_deletable arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWindow_get_destroy_with_parent arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWindow_get_focus arginfo_class_Gtk4_GtkButton_get_child

#define arginfo_class_Gtk4_GtkWindow_get_focus_visible arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWindow_get_handle_menubar_accel arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWindow_get_hide_on_close arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWindow_get_icon_name arginfo_class_Gtk4_GtkButton_get_icon_name

#define arginfo_class_Gtk4_GtkWindow_get_mnemonics_visible arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWindow_get_modal arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWindow_get_resizable arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWindow_get_title arginfo_class_Gtk4_GtkButton_get_icon_name

#define arginfo_class_Gtk4_GtkWindow_get_titlebar arginfo_class_Gtk4_GtkButton_get_child

#define arginfo_class_Gtk4_GtkWindow_get_transient_for arginfo_class_Gtk4_GtkApplication_get_active_window

#define arginfo_class_Gtk4_GtkWindow_has_group arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWindow_is_active arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWindow_is_fullscreen arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWindow_is_maximized arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWindow_is_suspended arginfo_class_Gtk4_GtkBox_get_homogeneous

#define arginfo_class_Gtk4_GtkWindow_maximize arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWindow_minimize arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWindow_present arginfo_class_Gtk4_GtkButton_vfunc_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_application, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, application, Gtk4\\GtkApplication, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWindow_set_child arginfo_class_Gtk4_GtkButton_set_child

#define arginfo_class_Gtk4_GtkWindow_set_decorated arginfo_class_Gtk4_GtkLabel_set_selectable

#define arginfo_class_Gtk4_GtkWindow_set_default_size arginfo_class_Gtk4_GtkDrawingArea_vfunc_resize

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_default_widget, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, default_widget, Gtk4\\GtkWidget, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWindow_set_deletable arginfo_class_Gtk4_GtkLabel_set_selectable

#define arginfo_class_Gtk4_GtkWindow_set_destroy_with_parent arginfo_class_Gtk4_GtkLabel_set_selectable

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_display, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, display, Gtk4\\GdkDisplay, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWindow_set_focus arginfo_class_Gtk4_GtkRoot_set_focus

#define arginfo_class_Gtk4_GtkWindow_set_focus_visible arginfo_class_Gtk4_GtkLabel_set_selectable

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_handle_menubar_accel, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, handle_menubar_accel, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWindow_set_hide_on_close arginfo_class_Gtk4_GtkLabel_set_selectable

#define arginfo_class_Gtk4_GtkWindow_set_icon_name arginfo_class_Gtk4_GtkWidget_set_cursor_from_name

#define arginfo_class_Gtk4_GtkWindow_set_mnemonics_visible arginfo_class_Gtk4_GtkLabel_set_selectable

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_modal, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, modal, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_resizable, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, resizable, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_startup_id, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, startup_id, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_title, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, title, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_titlebar, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, titlebar, Gtk4\\GtkWidget, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_set_transient_for, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, parent, Gtk4\\GtkWindow, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWindow_unfullscreen arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWindow_unmaximize arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWindow_unminimize arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWindow_get_display arginfo_class_Gtk4_GtkRoot_get_display

#define arginfo_class_Gtk4_GtkWindow_vfunc_activate_default arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWindow_vfunc_activate_focus arginfo_class_Gtk4_GtkButton_vfunc_activate

#define arginfo_class_Gtk4_GtkWindow_vfunc_close_request arginfo_class_Gtk4_GtkBox_get_homogeneous

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GtkWindow_vfunc_enable_debugging, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, toggle, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GtkWindow_vfunc_keys_changed arginfo_class_Gtk4_GtkButton_vfunc_activate

ZEND_METHOD(Gtk4_GtkApplication, __construct);
ZEND_METHOD(Gtk4_GtkApplication, add_window);
ZEND_METHOD(Gtk4_GtkApplication, get_accels_for_action);
ZEND_METHOD(Gtk4_GtkApplication, get_actions_for_accel);
ZEND_METHOD(Gtk4_GtkApplication, get_active_window);
ZEND_METHOD(Gtk4_GtkApplication, get_window_by_id);
ZEND_METHOD(Gtk4_GtkApplication, get_windows);
ZEND_METHOD(Gtk4_GtkApplication, list_action_descriptions);
ZEND_METHOD(Gtk4_GtkApplication, remove_window);
ZEND_METHOD(Gtk4_GtkApplication, set_accels_for_action);
ZEND_METHOD(Gtk4_GActionGroup, action_added);
ZEND_METHOD(Gtk4_GActionGroup, action_enabled_changed);
ZEND_METHOD(Gtk4_GActionGroup, action_removed);
ZEND_METHOD(Gtk4_GActionGroup, action_state_changed);
ZEND_METHOD(Gtk4_GActionGroup, activate_action);
ZEND_METHOD(Gtk4_GActionGroup, change_action_state);
ZEND_METHOD(Gtk4_GActionGroup, get_action_enabled);
ZEND_METHOD(Gtk4_GActionGroup, get_action_parameter_type);
ZEND_METHOD(Gtk4_GActionGroup, get_action_state);
ZEND_METHOD(Gtk4_GActionGroup, get_action_state_hint);
ZEND_METHOD(Gtk4_GActionGroup, get_action_state_type);
ZEND_METHOD(Gtk4_GActionGroup, has_action);
ZEND_METHOD(Gtk4_GActionGroup, list_actions);
ZEND_METHOD(Gtk4_GActionMap, add_action);
ZEND_METHOD(Gtk4_GActionMap, lookup_action);
ZEND_METHOD(Gtk4_GActionMap, remove_action);
ZEND_METHOD(Gtk4_GtkApplication, vfunc_window_added);
ZEND_METHOD(Gtk4_GtkApplication, vfunc_window_removed);
ZEND_METHOD(Gtk4_GtkBox, __construct);
ZEND_METHOD(Gtk4_GtkBox, append);
ZEND_METHOD(Gtk4_GtkBox, get_baseline_child);
ZEND_METHOD(Gtk4_GtkBox, get_baseline_position);
ZEND_METHOD(Gtk4_GtkBox, get_homogeneous);
ZEND_METHOD(Gtk4_GtkBox, get_spacing);
ZEND_METHOD(Gtk4_GtkBox, insert_child_after);
ZEND_METHOD(Gtk4_GtkBox, prepend);
ZEND_METHOD(Gtk4_GtkBox, remove);
ZEND_METHOD(Gtk4_GtkBox, reorder_child_after);
ZEND_METHOD(Gtk4_GtkBox, set_baseline_child);
ZEND_METHOD(Gtk4_GtkBox, set_baseline_position);
ZEND_METHOD(Gtk4_GtkBox, set_homogeneous);
ZEND_METHOD(Gtk4_GtkBox, set_spacing);
ZEND_METHOD(Gtk4_GtkBox, get_children);
ZEND_METHOD(Gtk4_GtkOrientable, get_orientation);
ZEND_METHOD(Gtk4_GtkOrientable, set_orientation);
ZEND_METHOD(Gtk4_GtkButton, __construct);
ZEND_METHOD(Gtk4_GtkButton, new_from_icon_name);
ZEND_METHOD(Gtk4_GtkButton, new_with_label);
ZEND_METHOD(Gtk4_GtkButton, new_with_mnemonic);
ZEND_METHOD(Gtk4_GtkButton, get_can_shrink);
ZEND_METHOD(Gtk4_GtkButton, get_child);
ZEND_METHOD(Gtk4_GtkButton, get_has_frame);
ZEND_METHOD(Gtk4_GtkButton, get_icon_name);
ZEND_METHOD(Gtk4_GtkButton, get_label);
ZEND_METHOD(Gtk4_GtkButton, get_use_underline);
ZEND_METHOD(Gtk4_GtkButton, set_can_shrink);
ZEND_METHOD(Gtk4_GtkButton, set_child);
ZEND_METHOD(Gtk4_GtkButton, set_has_frame);
ZEND_METHOD(Gtk4_GtkButton, set_icon_name);
ZEND_METHOD(Gtk4_GtkButton, set_label);
ZEND_METHOD(Gtk4_GtkButton, set_use_underline);
ZEND_METHOD(Gtk4_GtkButton, vfunc_activate);
ZEND_METHOD(Gtk4_GtkButton, vfunc_clicked);
ZEND_METHOD(Gtk4_GtkCssProvider, __construct);
ZEND_METHOD(Gtk4_GtkCssProvider, load_from_bytes);
ZEND_METHOD(Gtk4_GtkCssProvider, load_from_path);
ZEND_METHOD(Gtk4_GtkCssProvider, load_from_resource);
ZEND_METHOD(Gtk4_GtkCssProvider, load_from_string);
ZEND_METHOD(Gtk4_GtkCssProvider, load_named);
ZEND_METHOD(Gtk4_GtkCssProvider, to_string);
ZEND_METHOD(Gtk4_GtkCustomFilter, __construct);
ZEND_METHOD(Gtk4_GtkCustomFilter, set_filter_func);
ZEND_METHOD(Gtk4_GtkCustomSorter, __construct);
ZEND_METHOD(Gtk4_GtkCustomSorter, set_sort_func);
ZEND_METHOD(Gtk4_GtkDrawingArea, __construct);
ZEND_METHOD(Gtk4_GtkDrawingArea, get_content_height);
ZEND_METHOD(Gtk4_GtkDrawingArea, get_content_width);
ZEND_METHOD(Gtk4_GtkDrawingArea, set_content_height);
ZEND_METHOD(Gtk4_GtkDrawingArea, set_content_width);
ZEND_METHOD(Gtk4_GtkDrawingArea, set_draw_func);
ZEND_METHOD(Gtk4_GtkDrawingArea, vfunc_resize);
ZEND_METHOD(Gtk4_GtkFilter, __construct);
ZEND_METHOD(Gtk4_GtkFilter, changed);
ZEND_METHOD(Gtk4_GtkFilter, get_strictness);
ZEND_METHOD(Gtk4_GtkFilter, match);
ZEND_METHOD(Gtk4_GtkFilter, vfunc_get_strictness);
ZEND_METHOD(Gtk4_GtkFilter, vfunc_match);
ZEND_METHOD(Gtk4_GtkFilterListModel, __construct);
ZEND_METHOD(Gtk4_GtkFilterListModel, get_filter);
ZEND_METHOD(Gtk4_GtkFilterListModel, get_incremental);
ZEND_METHOD(Gtk4_GtkFilterListModel, get_model);
ZEND_METHOD(Gtk4_GtkFilterListModel, get_pending);
ZEND_METHOD(Gtk4_GtkFilterListModel, set_filter);
ZEND_METHOD(Gtk4_GtkFilterListModel, set_incremental);
ZEND_METHOD(Gtk4_GtkFilterListModel, set_model);
ZEND_METHOD(Gtk4_GListModel, get_item_type);
ZEND_METHOD(Gtk4_GListModel, get_n_items);
ZEND_METHOD(Gtk4_GListModel, get_item);
ZEND_METHOD(Gtk4_GListModel, items_changed);
ZEND_METHOD(Gtk4_GtkLabel, __construct);
ZEND_METHOD(Gtk4_GtkLabel, new_with_mnemonic);
ZEND_METHOD(Gtk4_GtkLabel, get_current_uri);
ZEND_METHOD(Gtk4_GtkLabel, get_ellipsize);
ZEND_METHOD(Gtk4_GtkLabel, get_justify);
ZEND_METHOD(Gtk4_GtkLabel, get_label);
ZEND_METHOD(Gtk4_GtkLabel, get_layout_offsets);
ZEND_METHOD(Gtk4_GtkLabel, get_lines);
ZEND_METHOD(Gtk4_GtkLabel, get_max_width_chars);
ZEND_METHOD(Gtk4_GtkLabel, get_mnemonic_keyval);
ZEND_METHOD(Gtk4_GtkLabel, get_mnemonic_widget);
ZEND_METHOD(Gtk4_GtkLabel, get_natural_wrap_mode);
ZEND_METHOD(Gtk4_GtkLabel, get_selectable);
ZEND_METHOD(Gtk4_GtkLabel, get_selection_bounds);
ZEND_METHOD(Gtk4_GtkLabel, get_single_line_mode);
ZEND_METHOD(Gtk4_GtkLabel, get_text);
ZEND_METHOD(Gtk4_GtkLabel, get_use_markup);
ZEND_METHOD(Gtk4_GtkLabel, get_use_underline);
ZEND_METHOD(Gtk4_GtkLabel, get_width_chars);
ZEND_METHOD(Gtk4_GtkLabel, get_wrap);
ZEND_METHOD(Gtk4_GtkLabel, get_wrap_mode);
ZEND_METHOD(Gtk4_GtkLabel, get_xalign);
ZEND_METHOD(Gtk4_GtkLabel, get_yalign);
ZEND_METHOD(Gtk4_GtkLabel, select_region);
ZEND_METHOD(Gtk4_GtkLabel, set_ellipsize);
ZEND_METHOD(Gtk4_GtkLabel, set_justify);
ZEND_METHOD(Gtk4_GtkLabel, set_label);
ZEND_METHOD(Gtk4_GtkLabel, set_lines);
ZEND_METHOD(Gtk4_GtkLabel, set_markup);
ZEND_METHOD(Gtk4_GtkLabel, set_markup_with_mnemonic);
ZEND_METHOD(Gtk4_GtkLabel, set_max_width_chars);
ZEND_METHOD(Gtk4_GtkLabel, set_mnemonic_widget);
ZEND_METHOD(Gtk4_GtkLabel, set_natural_wrap_mode);
ZEND_METHOD(Gtk4_GtkLabel, set_selectable);
ZEND_METHOD(Gtk4_GtkLabel, set_single_line_mode);
ZEND_METHOD(Gtk4_GtkLabel, set_text);
ZEND_METHOD(Gtk4_GtkLabel, set_text_with_mnemonic);
ZEND_METHOD(Gtk4_GtkLabel, set_use_markup);
ZEND_METHOD(Gtk4_GtkLabel, set_use_underline);
ZEND_METHOD(Gtk4_GtkLabel, set_width_chars);
ZEND_METHOD(Gtk4_GtkLabel, set_wrap);
ZEND_METHOD(Gtk4_GtkLabel, set_wrap_mode);
ZEND_METHOD(Gtk4_GtkLabel, set_xalign);
ZEND_METHOD(Gtk4_GtkLabel, set_yalign);
ZEND_METHOD(Gtk4_GtkSortListModel, __construct);
ZEND_METHOD(Gtk4_GtkSortListModel, get_incremental);
ZEND_METHOD(Gtk4_GtkSortListModel, get_model);
ZEND_METHOD(Gtk4_GtkSortListModel, get_pending);
ZEND_METHOD(Gtk4_GtkSortListModel, get_section_sorter);
ZEND_METHOD(Gtk4_GtkSortListModel, get_sorter);
ZEND_METHOD(Gtk4_GtkSortListModel, set_incremental);
ZEND_METHOD(Gtk4_GtkSortListModel, set_model);
ZEND_METHOD(Gtk4_GtkSortListModel, set_section_sorter);
ZEND_METHOD(Gtk4_GtkSortListModel, set_sorter);
ZEND_METHOD(Gtk4_GtkSorter, __construct);
ZEND_METHOD(Gtk4_GtkSorter, changed);
ZEND_METHOD(Gtk4_GtkSorter, compare);
ZEND_METHOD(Gtk4_GtkSorter, get_order);
ZEND_METHOD(Gtk4_GtkSorter, vfunc_compare);
ZEND_METHOD(Gtk4_GtkSorter, vfunc_get_order);
ZEND_METHOD(Gtk4_GtkWidget, __construct);
ZEND_METHOD(Gtk4_GtkWidget, get_default_direction);
ZEND_METHOD(Gtk4_GtkWidget, set_default_direction);
ZEND_METHOD(Gtk4_GtkWidget, action_set_enabled);
ZEND_METHOD(Gtk4_GtkWidget, activate);
ZEND_METHOD(Gtk4_GtkWidget, activate_action);
ZEND_METHOD(Gtk4_GtkWidget, activate_default);
ZEND_METHOD(Gtk4_GtkWidget, add_css_class);
ZEND_METHOD(Gtk4_GtkWidget, add_mnemonic_label);
ZEND_METHOD(Gtk4_GtkWidget, child_focus);
ZEND_METHOD(Gtk4_GtkWidget, compute_expand);
ZEND_METHOD(Gtk4_GtkWidget, contains);
ZEND_METHOD(Gtk4_GtkWidget, drag_check_threshold);
ZEND_METHOD(Gtk4_GtkWidget, error_bell);
ZEND_METHOD(Gtk4_GtkWidget, get_baseline);
ZEND_METHOD(Gtk4_GtkWidget, get_can_focus);
ZEND_METHOD(Gtk4_GtkWidget, get_can_target);
ZEND_METHOD(Gtk4_GtkWidget, get_child_visible);
ZEND_METHOD(Gtk4_GtkWidget, get_css_classes);
ZEND_METHOD(Gtk4_GtkWidget, get_css_name);
ZEND_METHOD(Gtk4_GtkWidget, get_direction);
ZEND_METHOD(Gtk4_GtkWidget, get_display);
ZEND_METHOD(Gtk4_GtkWidget, get_first_child);
ZEND_METHOD(Gtk4_GtkWidget, get_focus_child);
ZEND_METHOD(Gtk4_GtkWidget, get_focus_on_click);
ZEND_METHOD(Gtk4_GtkWidget, get_focusable);
ZEND_METHOD(Gtk4_GtkWidget, get_halign);
ZEND_METHOD(Gtk4_GtkWidget, get_has_tooltip);
ZEND_METHOD(Gtk4_GtkWidget, get_height);
ZEND_METHOD(Gtk4_GtkWidget, get_hexpand);
ZEND_METHOD(Gtk4_GtkWidget, get_hexpand_set);
ZEND_METHOD(Gtk4_GtkWidget, get_last_child);
ZEND_METHOD(Gtk4_GtkWidget, get_mapped);
ZEND_METHOD(Gtk4_GtkWidget, get_margin_bottom);
ZEND_METHOD(Gtk4_GtkWidget, get_margin_end);
ZEND_METHOD(Gtk4_GtkWidget, get_margin_start);
ZEND_METHOD(Gtk4_GtkWidget, get_margin_top);
ZEND_METHOD(Gtk4_GtkWidget, get_name);
ZEND_METHOD(Gtk4_GtkWidget, get_next_sibling);
ZEND_METHOD(Gtk4_GtkWidget, get_opacity);
ZEND_METHOD(Gtk4_GtkWidget, get_overflow);
ZEND_METHOD(Gtk4_GtkWidget, get_parent);
ZEND_METHOD(Gtk4_GtkWidget, get_prev_sibling);
ZEND_METHOD(Gtk4_GtkWidget, get_realized);
ZEND_METHOD(Gtk4_GtkWidget, get_receives_default);
ZEND_METHOD(Gtk4_GtkWidget, get_request_mode);
ZEND_METHOD(Gtk4_GtkWidget, get_root);
ZEND_METHOD(Gtk4_GtkWidget, get_scale_factor);
ZEND_METHOD(Gtk4_GtkWidget, get_sensitive);
ZEND_METHOD(Gtk4_GtkWidget, get_size);
ZEND_METHOD(Gtk4_GtkWidget, get_size_request);
ZEND_METHOD(Gtk4_GtkWidget, get_state_flags);
ZEND_METHOD(Gtk4_GtkWidget, get_tooltip_markup);
ZEND_METHOD(Gtk4_GtkWidget, get_tooltip_text);
ZEND_METHOD(Gtk4_GtkWidget, get_valign);
ZEND_METHOD(Gtk4_GtkWidget, get_vexpand);
ZEND_METHOD(Gtk4_GtkWidget, get_vexpand_set);
ZEND_METHOD(Gtk4_GtkWidget, get_visible);
ZEND_METHOD(Gtk4_GtkWidget, get_width);
ZEND_METHOD(Gtk4_GtkWidget, grab_focus);
ZEND_METHOD(Gtk4_GtkWidget, has_css_class);
ZEND_METHOD(Gtk4_GtkWidget, has_default);
ZEND_METHOD(Gtk4_GtkWidget, has_focus);
ZEND_METHOD(Gtk4_GtkWidget, has_visible_focus);
ZEND_METHOD(Gtk4_GtkWidget, in_destruction);
ZEND_METHOD(Gtk4_GtkWidget, init_template);
ZEND_METHOD(Gtk4_GtkWidget, insert_action_group);
ZEND_METHOD(Gtk4_GtkWidget, insert_after);
ZEND_METHOD(Gtk4_GtkWidget, insert_before);
ZEND_METHOD(Gtk4_GtkWidget, is_ancestor);
ZEND_METHOD(Gtk4_GtkWidget, is_drawable);
ZEND_METHOD(Gtk4_GtkWidget, is_focus);
ZEND_METHOD(Gtk4_GtkWidget, is_sensitive);
ZEND_METHOD(Gtk4_GtkWidget, is_visible);
ZEND_METHOD(Gtk4_GtkWidget, keynav_failed);
ZEND_METHOD(Gtk4_GtkWidget, list_mnemonic_labels);
ZEND_METHOD(Gtk4_GtkWidget, map);
ZEND_METHOD(Gtk4_GtkWidget, measure);
ZEND_METHOD(Gtk4_GtkWidget, mnemonic_activate);
ZEND_METHOD(Gtk4_GtkWidget, observe_children);
ZEND_METHOD(Gtk4_GtkWidget, observe_controllers);
ZEND_METHOD(Gtk4_GtkWidget, pick);
ZEND_METHOD(Gtk4_GtkWidget, queue_allocate);
ZEND_METHOD(Gtk4_GtkWidget, queue_draw);
ZEND_METHOD(Gtk4_GtkWidget, queue_resize);
ZEND_METHOD(Gtk4_GtkWidget, realize);
ZEND_METHOD(Gtk4_GtkWidget, remove_css_class);
ZEND_METHOD(Gtk4_GtkWidget, remove_mnemonic_label);
ZEND_METHOD(Gtk4_GtkWidget, remove_tick_callback);
ZEND_METHOD(Gtk4_GtkWidget, set_can_focus);
ZEND_METHOD(Gtk4_GtkWidget, set_can_target);
ZEND_METHOD(Gtk4_GtkWidget, set_child_visible);
ZEND_METHOD(Gtk4_GtkWidget, set_css_classes);
ZEND_METHOD(Gtk4_GtkWidget, set_cursor_from_name);
ZEND_METHOD(Gtk4_GtkWidget, set_direction);
ZEND_METHOD(Gtk4_GtkWidget, set_focus_child);
ZEND_METHOD(Gtk4_GtkWidget, set_focus_on_click);
ZEND_METHOD(Gtk4_GtkWidget, set_focusable);
ZEND_METHOD(Gtk4_GtkWidget, set_halign);
ZEND_METHOD(Gtk4_GtkWidget, set_has_tooltip);
ZEND_METHOD(Gtk4_GtkWidget, set_hexpand);
ZEND_METHOD(Gtk4_GtkWidget, set_hexpand_set);
ZEND_METHOD(Gtk4_GtkWidget, set_margin_bottom);
ZEND_METHOD(Gtk4_GtkWidget, set_margin_end);
ZEND_METHOD(Gtk4_GtkWidget, set_margin_start);
ZEND_METHOD(Gtk4_GtkWidget, set_margin_top);
ZEND_METHOD(Gtk4_GtkWidget, set_name);
ZEND_METHOD(Gtk4_GtkWidget, set_opacity);
ZEND_METHOD(Gtk4_GtkWidget, set_overflow);
ZEND_METHOD(Gtk4_GtkWidget, set_parent);
ZEND_METHOD(Gtk4_GtkWidget, set_receives_default);
ZEND_METHOD(Gtk4_GtkWidget, set_sensitive);
ZEND_METHOD(Gtk4_GtkWidget, set_size_request);
ZEND_METHOD(Gtk4_GtkWidget, set_state_flags);
ZEND_METHOD(Gtk4_GtkWidget, set_tooltip_markup);
ZEND_METHOD(Gtk4_GtkWidget, set_tooltip_text);
ZEND_METHOD(Gtk4_GtkWidget, set_valign);
ZEND_METHOD(Gtk4_GtkWidget, set_vexpand);
ZEND_METHOD(Gtk4_GtkWidget, set_vexpand_set);
ZEND_METHOD(Gtk4_GtkWidget, set_visible);
ZEND_METHOD(Gtk4_GtkWidget, should_layout);
ZEND_METHOD(Gtk4_GtkWidget, trigger_tooltip_query);
ZEND_METHOD(Gtk4_GtkWidget, unmap);
ZEND_METHOD(Gtk4_GtkWidget, unparent);
ZEND_METHOD(Gtk4_GtkWidget, unrealize);
ZEND_METHOD(Gtk4_GtkWidget, unset_state_flags);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_contains);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_direction_changed);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_focus);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_get_request_mode);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_grab_focus);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_keynav_failed);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_map);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_measure);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_mnemonic_activate);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_move_focus);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_realize);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_root);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_set_focus_child);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_size_allocate);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_state_flags_changed);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_system_setting_changed);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_unmap);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_unrealize);
ZEND_METHOD(Gtk4_GtkWidget, vfunc_unroot);
ZEND_METHOD(Gtk4_GtkWindow, __construct);
ZEND_METHOD(Gtk4_GtkWindow, get_default_icon_name);
ZEND_METHOD(Gtk4_GtkWindow, get_toplevels);
ZEND_METHOD(Gtk4_GtkWindow, list_toplevels);
ZEND_METHOD(Gtk4_GtkWindow, set_auto_startup_notification);
ZEND_METHOD(Gtk4_GtkWindow, set_default_icon_name);
ZEND_METHOD(Gtk4_GtkWindow, set_interactive_debugging);
ZEND_METHOD(Gtk4_GtkWindow, close);
ZEND_METHOD(Gtk4_GtkWindow, destroy);
ZEND_METHOD(Gtk4_GtkWindow, fullscreen);
ZEND_METHOD(Gtk4_GtkWindow, get_application);
ZEND_METHOD(Gtk4_GtkWindow, get_child);
ZEND_METHOD(Gtk4_GtkWindow, get_decorated);
ZEND_METHOD(Gtk4_GtkWindow, get_default_size);
ZEND_METHOD(Gtk4_GtkWindow, get_default_widget);
ZEND_METHOD(Gtk4_GtkWindow, get_deletable);
ZEND_METHOD(Gtk4_GtkWindow, get_destroy_with_parent);
ZEND_METHOD(Gtk4_GtkWindow, get_focus);
ZEND_METHOD(Gtk4_GtkWindow, get_focus_visible);
ZEND_METHOD(Gtk4_GtkWindow, get_handle_menubar_accel);
ZEND_METHOD(Gtk4_GtkWindow, get_hide_on_close);
ZEND_METHOD(Gtk4_GtkWindow, get_icon_name);
ZEND_METHOD(Gtk4_GtkWindow, get_mnemonics_visible);
ZEND_METHOD(Gtk4_GtkWindow, get_modal);
ZEND_METHOD(Gtk4_GtkWindow, get_resizable);
ZEND_METHOD(Gtk4_GtkWindow, get_title);
ZEND_METHOD(Gtk4_GtkWindow, get_titlebar);
ZEND_METHOD(Gtk4_GtkWindow, get_transient_for);
ZEND_METHOD(Gtk4_GtkWindow, has_group);
ZEND_METHOD(Gtk4_GtkWindow, is_active);
ZEND_METHOD(Gtk4_GtkWindow, is_fullscreen);
ZEND_METHOD(Gtk4_GtkWindow, is_maximized);
ZEND_METHOD(Gtk4_GtkWindow, is_suspended);
ZEND_METHOD(Gtk4_GtkWindow, maximize);
ZEND_METHOD(Gtk4_GtkWindow, minimize);
ZEND_METHOD(Gtk4_GtkWindow, present);
ZEND_METHOD(Gtk4_GtkWindow, set_application);
ZEND_METHOD(Gtk4_GtkWindow, set_child);
ZEND_METHOD(Gtk4_GtkWindow, set_decorated);
ZEND_METHOD(Gtk4_GtkWindow, set_default_size);
ZEND_METHOD(Gtk4_GtkWindow, set_default_widget);
ZEND_METHOD(Gtk4_GtkWindow, set_deletable);
ZEND_METHOD(Gtk4_GtkWindow, set_destroy_with_parent);
ZEND_METHOD(Gtk4_GtkWindow, set_display);
ZEND_METHOD(Gtk4_GtkWindow, set_focus);
ZEND_METHOD(Gtk4_GtkWindow, set_focus_visible);
ZEND_METHOD(Gtk4_GtkWindow, set_handle_menubar_accel);
ZEND_METHOD(Gtk4_GtkWindow, set_hide_on_close);
ZEND_METHOD(Gtk4_GtkWindow, set_icon_name);
ZEND_METHOD(Gtk4_GtkWindow, set_mnemonics_visible);
ZEND_METHOD(Gtk4_GtkWindow, set_modal);
ZEND_METHOD(Gtk4_GtkWindow, set_resizable);
ZEND_METHOD(Gtk4_GtkWindow, set_startup_id);
ZEND_METHOD(Gtk4_GtkWindow, set_title);
ZEND_METHOD(Gtk4_GtkWindow, set_titlebar);
ZEND_METHOD(Gtk4_GtkWindow, set_transient_for);
ZEND_METHOD(Gtk4_GtkWindow, unfullscreen);
ZEND_METHOD(Gtk4_GtkWindow, unmaximize);
ZEND_METHOD(Gtk4_GtkWindow, unminimize);
ZEND_METHOD(Gtk4_GtkWindow, vfunc_activate_default);
ZEND_METHOD(Gtk4_GtkWindow, vfunc_activate_focus);
ZEND_METHOD(Gtk4_GtkWindow, vfunc_close_request);
ZEND_METHOD(Gtk4_GtkWindow, vfunc_enable_debugging);
ZEND_METHOD(Gtk4_GtkWindow, vfunc_keys_changed);

static const zend_function_entry class_Gtk4_GtkApplication_methods[] = {
	ZEND_ME(Gtk4_GtkApplication, __construct, arginfo_class_Gtk4_GtkApplication___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, add_window, arginfo_class_Gtk4_GtkApplication_add_window, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, get_accels_for_action, arginfo_class_Gtk4_GtkApplication_get_accels_for_action, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, get_actions_for_accel, arginfo_class_Gtk4_GtkApplication_get_actions_for_accel, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, get_active_window, arginfo_class_Gtk4_GtkApplication_get_active_window, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, get_window_by_id, arginfo_class_Gtk4_GtkApplication_get_window_by_id, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, get_windows, arginfo_class_Gtk4_GtkApplication_get_windows, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, list_action_descriptions, arginfo_class_Gtk4_GtkApplication_list_action_descriptions, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, remove_window, arginfo_class_Gtk4_GtkApplication_remove_window, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, set_accels_for_action, arginfo_class_Gtk4_GtkApplication_set_accels_for_action, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("action_added", zim_Gtk4_GActionGroup_action_added, arginfo_class_Gtk4_GtkApplication_action_added, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("action_enabled_changed", zim_Gtk4_GActionGroup_action_enabled_changed, arginfo_class_Gtk4_GtkApplication_action_enabled_changed, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("action_removed", zim_Gtk4_GActionGroup_action_removed, arginfo_class_Gtk4_GtkApplication_action_removed, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("action_state_changed", zim_Gtk4_GActionGroup_action_state_changed, arginfo_class_Gtk4_GtkApplication_action_state_changed, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("activate_action", zim_Gtk4_GActionGroup_activate_action, arginfo_class_Gtk4_GtkApplication_activate_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("change_action_state", zim_Gtk4_GActionGroup_change_action_state, arginfo_class_Gtk4_GtkApplication_change_action_state, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_enabled", zim_Gtk4_GActionGroup_get_action_enabled, arginfo_class_Gtk4_GtkApplication_get_action_enabled, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_parameter_type", zim_Gtk4_GActionGroup_get_action_parameter_type, arginfo_class_Gtk4_GtkApplication_get_action_parameter_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state", zim_Gtk4_GActionGroup_get_action_state, arginfo_class_Gtk4_GtkApplication_get_action_state, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state_hint", zim_Gtk4_GActionGroup_get_action_state_hint, arginfo_class_Gtk4_GtkApplication_get_action_state_hint, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state_type", zim_Gtk4_GActionGroup_get_action_state_type, arginfo_class_Gtk4_GtkApplication_get_action_state_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("has_action", zim_Gtk4_GActionGroup_has_action, arginfo_class_Gtk4_GtkApplication_has_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("list_actions", zim_Gtk4_GActionGroup_list_actions, arginfo_class_Gtk4_GtkApplication_list_actions, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("add_action", zim_Gtk4_GActionMap_add_action, arginfo_class_Gtk4_GtkApplication_add_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("lookup_action", zim_Gtk4_GActionMap_lookup_action, arginfo_class_Gtk4_GtkApplication_lookup_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("remove_action", zim_Gtk4_GActionMap_remove_action, arginfo_class_Gtk4_GtkApplication_remove_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_ME(Gtk4_GtkApplication, vfunc_window_added, arginfo_class_Gtk4_GtkApplication_vfunc_window_added, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkApplication, vfunc_window_removed, arginfo_class_Gtk4_GtkApplication_vfunc_window_removed, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkBox_methods[] = {
	ZEND_ME(Gtk4_GtkBox, __construct, arginfo_class_Gtk4_GtkBox___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, append, arginfo_class_Gtk4_GtkBox_append, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, get_baseline_child, arginfo_class_Gtk4_GtkBox_get_baseline_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, get_baseline_position, arginfo_class_Gtk4_GtkBox_get_baseline_position, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, get_homogeneous, arginfo_class_Gtk4_GtkBox_get_homogeneous, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, get_spacing, arginfo_class_Gtk4_GtkBox_get_spacing, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, insert_child_after, arginfo_class_Gtk4_GtkBox_insert_child_after, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, prepend, arginfo_class_Gtk4_GtkBox_prepend, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, remove, arginfo_class_Gtk4_GtkBox_remove, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, reorder_child_after, arginfo_class_Gtk4_GtkBox_reorder_child_after, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, set_baseline_child, arginfo_class_Gtk4_GtkBox_set_baseline_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, set_baseline_position, arginfo_class_Gtk4_GtkBox_set_baseline_position, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, set_homogeneous, arginfo_class_Gtk4_GtkBox_set_homogeneous, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, set_spacing, arginfo_class_Gtk4_GtkBox_set_spacing, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkBox, get_children, arginfo_class_Gtk4_GtkBox_get_children, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("get_orientation", zim_Gtk4_GtkOrientable_get_orientation, arginfo_class_Gtk4_GtkBox_get_orientation, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("set_orientation", zim_Gtk4_GtkOrientable_set_orientation, arginfo_class_Gtk4_GtkBox_set_orientation, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkButton_methods[] = {
	ZEND_ME(Gtk4_GtkButton, __construct, arginfo_class_Gtk4_GtkButton___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, new_from_icon_name, arginfo_class_Gtk4_GtkButton_new_from_icon_name, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GtkButton, new_with_label, arginfo_class_Gtk4_GtkButton_new_with_label, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GtkButton, new_with_mnemonic, arginfo_class_Gtk4_GtkButton_new_with_mnemonic, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GtkButton, get_can_shrink, arginfo_class_Gtk4_GtkButton_get_can_shrink, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, get_child, arginfo_class_Gtk4_GtkButton_get_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, get_has_frame, arginfo_class_Gtk4_GtkButton_get_has_frame, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, get_icon_name, arginfo_class_Gtk4_GtkButton_get_icon_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, get_label, arginfo_class_Gtk4_GtkButton_get_label, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, get_use_underline, arginfo_class_Gtk4_GtkButton_get_use_underline, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, set_can_shrink, arginfo_class_Gtk4_GtkButton_set_can_shrink, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, set_child, arginfo_class_Gtk4_GtkButton_set_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, set_has_frame, arginfo_class_Gtk4_GtkButton_set_has_frame, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, set_icon_name, arginfo_class_Gtk4_GtkButton_set_icon_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, set_label, arginfo_class_Gtk4_GtkButton_set_label, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, set_use_underline, arginfo_class_Gtk4_GtkButton_set_use_underline, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, vfunc_activate, arginfo_class_Gtk4_GtkButton_vfunc_activate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkButton, vfunc_clicked, arginfo_class_Gtk4_GtkButton_vfunc_clicked, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkCssProvider_methods[] = {
	ZEND_ME(Gtk4_GtkCssProvider, __construct, arginfo_class_Gtk4_GtkCssProvider___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkCssProvider, load_from_bytes, arginfo_class_Gtk4_GtkCssProvider_load_from_bytes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkCssProvider, load_from_path, arginfo_class_Gtk4_GtkCssProvider_load_from_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkCssProvider, load_from_resource, arginfo_class_Gtk4_GtkCssProvider_load_from_resource, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkCssProvider, load_from_string, arginfo_class_Gtk4_GtkCssProvider_load_from_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkCssProvider, load_named, arginfo_class_Gtk4_GtkCssProvider_load_named, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkCssProvider, to_string, arginfo_class_Gtk4_GtkCssProvider_to_string, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkCustomFilter_methods[] = {
	ZEND_ME(Gtk4_GtkCustomFilter, __construct, arginfo_class_Gtk4_GtkCustomFilter___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkCustomFilter, set_filter_func, arginfo_class_Gtk4_GtkCustomFilter_set_filter_func, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkCustomSorter_methods[] = {
	ZEND_ME(Gtk4_GtkCustomSorter, __construct, arginfo_class_Gtk4_GtkCustomSorter___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkCustomSorter, set_sort_func, arginfo_class_Gtk4_GtkCustomSorter_set_sort_func, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkDrawingArea_methods[] = {
	ZEND_ME(Gtk4_GtkDrawingArea, __construct, arginfo_class_Gtk4_GtkDrawingArea___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkDrawingArea, get_content_height, arginfo_class_Gtk4_GtkDrawingArea_get_content_height, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkDrawingArea, get_content_width, arginfo_class_Gtk4_GtkDrawingArea_get_content_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkDrawingArea, set_content_height, arginfo_class_Gtk4_GtkDrawingArea_set_content_height, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkDrawingArea, set_content_width, arginfo_class_Gtk4_GtkDrawingArea_set_content_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkDrawingArea, set_draw_func, arginfo_class_Gtk4_GtkDrawingArea_set_draw_func, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkDrawingArea, vfunc_resize, arginfo_class_Gtk4_GtkDrawingArea_vfunc_resize, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkFilter_methods[] = {
	ZEND_ME(Gtk4_GtkFilter, __construct, arginfo_class_Gtk4_GtkFilter___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilter, changed, arginfo_class_Gtk4_GtkFilter_changed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilter, get_strictness, arginfo_class_Gtk4_GtkFilter_get_strictness, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilter, match, arginfo_class_Gtk4_GtkFilter_match, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilter, vfunc_get_strictness, arginfo_class_Gtk4_GtkFilter_vfunc_get_strictness, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilter, vfunc_match, arginfo_class_Gtk4_GtkFilter_vfunc_match, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkFilterListModel_methods[] = {
	ZEND_ME(Gtk4_GtkFilterListModel, __construct, arginfo_class_Gtk4_GtkFilterListModel___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilterListModel, get_filter, arginfo_class_Gtk4_GtkFilterListModel_get_filter, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilterListModel, get_incremental, arginfo_class_Gtk4_GtkFilterListModel_get_incremental, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilterListModel, get_model, arginfo_class_Gtk4_GtkFilterListModel_get_model, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilterListModel, get_pending, arginfo_class_Gtk4_GtkFilterListModel_get_pending, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilterListModel, set_filter, arginfo_class_Gtk4_GtkFilterListModel_set_filter, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilterListModel, set_incremental, arginfo_class_Gtk4_GtkFilterListModel_set_incremental, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkFilterListModel, set_model, arginfo_class_Gtk4_GtkFilterListModel_set_model, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("get_item_type", zim_Gtk4_GListModel_get_item_type, arginfo_class_Gtk4_GtkFilterListModel_get_item_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_n_items", zim_Gtk4_GListModel_get_n_items, arginfo_class_Gtk4_GtkFilterListModel_get_n_items, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_item", zim_Gtk4_GListModel_get_item, arginfo_class_Gtk4_GtkFilterListModel_get_item, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("items_changed", zim_Gtk4_GListModel_items_changed, arginfo_class_Gtk4_GtkFilterListModel_items_changed, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkLabel_methods[] = {
	ZEND_ME(Gtk4_GtkLabel, __construct, arginfo_class_Gtk4_GtkLabel___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, new_with_mnemonic, arginfo_class_Gtk4_GtkLabel_new_with_mnemonic, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GtkLabel, get_current_uri, arginfo_class_Gtk4_GtkLabel_get_current_uri, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_ellipsize, arginfo_class_Gtk4_GtkLabel_get_ellipsize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_justify, arginfo_class_Gtk4_GtkLabel_get_justify, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_label, arginfo_class_Gtk4_GtkLabel_get_label, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_layout_offsets, arginfo_class_Gtk4_GtkLabel_get_layout_offsets, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_lines, arginfo_class_Gtk4_GtkLabel_get_lines, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_max_width_chars, arginfo_class_Gtk4_GtkLabel_get_max_width_chars, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_mnemonic_keyval, arginfo_class_Gtk4_GtkLabel_get_mnemonic_keyval, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_mnemonic_widget, arginfo_class_Gtk4_GtkLabel_get_mnemonic_widget, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_natural_wrap_mode, arginfo_class_Gtk4_GtkLabel_get_natural_wrap_mode, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_selectable, arginfo_class_Gtk4_GtkLabel_get_selectable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_selection_bounds, arginfo_class_Gtk4_GtkLabel_get_selection_bounds, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_single_line_mode, arginfo_class_Gtk4_GtkLabel_get_single_line_mode, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_text, arginfo_class_Gtk4_GtkLabel_get_text, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_use_markup, arginfo_class_Gtk4_GtkLabel_get_use_markup, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_use_underline, arginfo_class_Gtk4_GtkLabel_get_use_underline, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_width_chars, arginfo_class_Gtk4_GtkLabel_get_width_chars, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_wrap, arginfo_class_Gtk4_GtkLabel_get_wrap, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_wrap_mode, arginfo_class_Gtk4_GtkLabel_get_wrap_mode, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_xalign, arginfo_class_Gtk4_GtkLabel_get_xalign, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, get_yalign, arginfo_class_Gtk4_GtkLabel_get_yalign, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, select_region, arginfo_class_Gtk4_GtkLabel_select_region, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_ellipsize, arginfo_class_Gtk4_GtkLabel_set_ellipsize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_justify, arginfo_class_Gtk4_GtkLabel_set_justify, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_label, arginfo_class_Gtk4_GtkLabel_set_label, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_lines, arginfo_class_Gtk4_GtkLabel_set_lines, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_markup, arginfo_class_Gtk4_GtkLabel_set_markup, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_markup_with_mnemonic, arginfo_class_Gtk4_GtkLabel_set_markup_with_mnemonic, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_max_width_chars, arginfo_class_Gtk4_GtkLabel_set_max_width_chars, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_mnemonic_widget, arginfo_class_Gtk4_GtkLabel_set_mnemonic_widget, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_natural_wrap_mode, arginfo_class_Gtk4_GtkLabel_set_natural_wrap_mode, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_selectable, arginfo_class_Gtk4_GtkLabel_set_selectable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_single_line_mode, arginfo_class_Gtk4_GtkLabel_set_single_line_mode, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_text, arginfo_class_Gtk4_GtkLabel_set_text, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_text_with_mnemonic, arginfo_class_Gtk4_GtkLabel_set_text_with_mnemonic, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_use_markup, arginfo_class_Gtk4_GtkLabel_set_use_markup, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_use_underline, arginfo_class_Gtk4_GtkLabel_set_use_underline, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_width_chars, arginfo_class_Gtk4_GtkLabel_set_width_chars, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_wrap, arginfo_class_Gtk4_GtkLabel_set_wrap, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_wrap_mode, arginfo_class_Gtk4_GtkLabel_set_wrap_mode, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_xalign, arginfo_class_Gtk4_GtkLabel_set_xalign, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkLabel, set_yalign, arginfo_class_Gtk4_GtkLabel_set_yalign, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkOrientable_methods[] = {
	ZEND_RAW_FENTRY("get_orientation", NULL, arginfo_class_Gtk4_GtkOrientable_get_orientation, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("set_orientation", NULL, arginfo_class_Gtk4_GtkOrientable_set_orientation, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkRoot_methods[] = {
	ZEND_RAW_FENTRY("get_display", NULL, arginfo_class_Gtk4_GtkRoot_get_display, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_focus", NULL, arginfo_class_Gtk4_GtkRoot_get_focus, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("set_focus", NULL, arginfo_class_Gtk4_GtkRoot_set_focus, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkSortListModel_methods[] = {
	ZEND_ME(Gtk4_GtkSortListModel, __construct, arginfo_class_Gtk4_GtkSortListModel___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSortListModel, get_incremental, arginfo_class_Gtk4_GtkSortListModel_get_incremental, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSortListModel, get_model, arginfo_class_Gtk4_GtkSortListModel_get_model, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSortListModel, get_pending, arginfo_class_Gtk4_GtkSortListModel_get_pending, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSortListModel, get_section_sorter, arginfo_class_Gtk4_GtkSortListModel_get_section_sorter, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSortListModel, get_sorter, arginfo_class_Gtk4_GtkSortListModel_get_sorter, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSortListModel, set_incremental, arginfo_class_Gtk4_GtkSortListModel_set_incremental, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSortListModel, set_model, arginfo_class_Gtk4_GtkSortListModel_set_model, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSortListModel, set_section_sorter, arginfo_class_Gtk4_GtkSortListModel_set_section_sorter, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSortListModel, set_sorter, arginfo_class_Gtk4_GtkSortListModel_set_sorter, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("get_item_type", zim_Gtk4_GListModel_get_item_type, arginfo_class_Gtk4_GtkSortListModel_get_item_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_n_items", zim_Gtk4_GListModel_get_n_items, arginfo_class_Gtk4_GtkSortListModel_get_n_items, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_item", zim_Gtk4_GListModel_get_item, arginfo_class_Gtk4_GtkSortListModel_get_item, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("items_changed", zim_Gtk4_GListModel_items_changed, arginfo_class_Gtk4_GtkSortListModel_items_changed, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkSorter_methods[] = {
	ZEND_ME(Gtk4_GtkSorter, __construct, arginfo_class_Gtk4_GtkSorter___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSorter, changed, arginfo_class_Gtk4_GtkSorter_changed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSorter, compare, arginfo_class_Gtk4_GtkSorter_compare, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSorter, get_order, arginfo_class_Gtk4_GtkSorter_get_order, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSorter, vfunc_compare, arginfo_class_Gtk4_GtkSorter_vfunc_compare, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkSorter, vfunc_get_order, arginfo_class_Gtk4_GtkSorter_vfunc_get_order, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkWidget_methods[] = {
	ZEND_ME(Gtk4_GtkWidget, __construct, arginfo_class_Gtk4_GtkWidget___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_default_direction, arginfo_class_Gtk4_GtkWidget_get_default_direction, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GtkWidget, set_default_direction, arginfo_class_Gtk4_GtkWidget_set_default_direction, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GtkWidget, action_set_enabled, arginfo_class_Gtk4_GtkWidget_action_set_enabled, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, activate, arginfo_class_Gtk4_GtkWidget_activate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, activate_action, arginfo_class_Gtk4_GtkWidget_activate_action, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, activate_default, arginfo_class_Gtk4_GtkWidget_activate_default, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, add_css_class, arginfo_class_Gtk4_GtkWidget_add_css_class, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, add_mnemonic_label, arginfo_class_Gtk4_GtkWidget_add_mnemonic_label, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, child_focus, arginfo_class_Gtk4_GtkWidget_child_focus, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, compute_expand, arginfo_class_Gtk4_GtkWidget_compute_expand, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, contains, arginfo_class_Gtk4_GtkWidget_contains, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, drag_check_threshold, arginfo_class_Gtk4_GtkWidget_drag_check_threshold, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, error_bell, arginfo_class_Gtk4_GtkWidget_error_bell, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_baseline, arginfo_class_Gtk4_GtkWidget_get_baseline, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_can_focus, arginfo_class_Gtk4_GtkWidget_get_can_focus, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_can_target, arginfo_class_Gtk4_GtkWidget_get_can_target, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_child_visible, arginfo_class_Gtk4_GtkWidget_get_child_visible, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_css_classes, arginfo_class_Gtk4_GtkWidget_get_css_classes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_css_name, arginfo_class_Gtk4_GtkWidget_get_css_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_direction, arginfo_class_Gtk4_GtkWidget_get_direction, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_display, arginfo_class_Gtk4_GtkWidget_get_display, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_first_child, arginfo_class_Gtk4_GtkWidget_get_first_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_focus_child, arginfo_class_Gtk4_GtkWidget_get_focus_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_focus_on_click, arginfo_class_Gtk4_GtkWidget_get_focus_on_click, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_focusable, arginfo_class_Gtk4_GtkWidget_get_focusable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_halign, arginfo_class_Gtk4_GtkWidget_get_halign, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_has_tooltip, arginfo_class_Gtk4_GtkWidget_get_has_tooltip, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_height, arginfo_class_Gtk4_GtkWidget_get_height, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_hexpand, arginfo_class_Gtk4_GtkWidget_get_hexpand, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_hexpand_set, arginfo_class_Gtk4_GtkWidget_get_hexpand_set, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_last_child, arginfo_class_Gtk4_GtkWidget_get_last_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_mapped, arginfo_class_Gtk4_GtkWidget_get_mapped, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_margin_bottom, arginfo_class_Gtk4_GtkWidget_get_margin_bottom, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_margin_end, arginfo_class_Gtk4_GtkWidget_get_margin_end, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_margin_start, arginfo_class_Gtk4_GtkWidget_get_margin_start, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_margin_top, arginfo_class_Gtk4_GtkWidget_get_margin_top, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_name, arginfo_class_Gtk4_GtkWidget_get_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_next_sibling, arginfo_class_Gtk4_GtkWidget_get_next_sibling, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_opacity, arginfo_class_Gtk4_GtkWidget_get_opacity, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_overflow, arginfo_class_Gtk4_GtkWidget_get_overflow, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_parent, arginfo_class_Gtk4_GtkWidget_get_parent, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_prev_sibling, arginfo_class_Gtk4_GtkWidget_get_prev_sibling, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_realized, arginfo_class_Gtk4_GtkWidget_get_realized, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_receives_default, arginfo_class_Gtk4_GtkWidget_get_receives_default, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_request_mode, arginfo_class_Gtk4_GtkWidget_get_request_mode, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_root, arginfo_class_Gtk4_GtkWidget_get_root, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_scale_factor, arginfo_class_Gtk4_GtkWidget_get_scale_factor, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_sensitive, arginfo_class_Gtk4_GtkWidget_get_sensitive, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_size, arginfo_class_Gtk4_GtkWidget_get_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_size_request, arginfo_class_Gtk4_GtkWidget_get_size_request, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_state_flags, arginfo_class_Gtk4_GtkWidget_get_state_flags, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_tooltip_markup, arginfo_class_Gtk4_GtkWidget_get_tooltip_markup, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_tooltip_text, arginfo_class_Gtk4_GtkWidget_get_tooltip_text, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_valign, arginfo_class_Gtk4_GtkWidget_get_valign, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_vexpand, arginfo_class_Gtk4_GtkWidget_get_vexpand, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_vexpand_set, arginfo_class_Gtk4_GtkWidget_get_vexpand_set, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_visible, arginfo_class_Gtk4_GtkWidget_get_visible, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, get_width, arginfo_class_Gtk4_GtkWidget_get_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, grab_focus, arginfo_class_Gtk4_GtkWidget_grab_focus, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, has_css_class, arginfo_class_Gtk4_GtkWidget_has_css_class, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, has_default, arginfo_class_Gtk4_GtkWidget_has_default, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, has_focus, arginfo_class_Gtk4_GtkWidget_has_focus, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, has_visible_focus, arginfo_class_Gtk4_GtkWidget_has_visible_focus, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, in_destruction, arginfo_class_Gtk4_GtkWidget_in_destruction, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, init_template, arginfo_class_Gtk4_GtkWidget_init_template, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, insert_action_group, arginfo_class_Gtk4_GtkWidget_insert_action_group, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, insert_after, arginfo_class_Gtk4_GtkWidget_insert_after, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, insert_before, arginfo_class_Gtk4_GtkWidget_insert_before, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, is_ancestor, arginfo_class_Gtk4_GtkWidget_is_ancestor, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, is_drawable, arginfo_class_Gtk4_GtkWidget_is_drawable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, is_focus, arginfo_class_Gtk4_GtkWidget_is_focus, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, is_sensitive, arginfo_class_Gtk4_GtkWidget_is_sensitive, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, is_visible, arginfo_class_Gtk4_GtkWidget_is_visible, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, keynav_failed, arginfo_class_Gtk4_GtkWidget_keynav_failed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, list_mnemonic_labels, arginfo_class_Gtk4_GtkWidget_list_mnemonic_labels, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, map, arginfo_class_Gtk4_GtkWidget_map, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, measure, arginfo_class_Gtk4_GtkWidget_measure, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, mnemonic_activate, arginfo_class_Gtk4_GtkWidget_mnemonic_activate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, observe_children, arginfo_class_Gtk4_GtkWidget_observe_children, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, observe_controllers, arginfo_class_Gtk4_GtkWidget_observe_controllers, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, pick, arginfo_class_Gtk4_GtkWidget_pick, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, queue_allocate, arginfo_class_Gtk4_GtkWidget_queue_allocate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, queue_draw, arginfo_class_Gtk4_GtkWidget_queue_draw, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, queue_resize, arginfo_class_Gtk4_GtkWidget_queue_resize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, realize, arginfo_class_Gtk4_GtkWidget_realize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, remove_css_class, arginfo_class_Gtk4_GtkWidget_remove_css_class, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, remove_mnemonic_label, arginfo_class_Gtk4_GtkWidget_remove_mnemonic_label, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, remove_tick_callback, arginfo_class_Gtk4_GtkWidget_remove_tick_callback, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_can_focus, arginfo_class_Gtk4_GtkWidget_set_can_focus, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_can_target, arginfo_class_Gtk4_GtkWidget_set_can_target, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_child_visible, arginfo_class_Gtk4_GtkWidget_set_child_visible, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_css_classes, arginfo_class_Gtk4_GtkWidget_set_css_classes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_cursor_from_name, arginfo_class_Gtk4_GtkWidget_set_cursor_from_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_direction, arginfo_class_Gtk4_GtkWidget_set_direction, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_focus_child, arginfo_class_Gtk4_GtkWidget_set_focus_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_focus_on_click, arginfo_class_Gtk4_GtkWidget_set_focus_on_click, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_focusable, arginfo_class_Gtk4_GtkWidget_set_focusable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_halign, arginfo_class_Gtk4_GtkWidget_set_halign, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_has_tooltip, arginfo_class_Gtk4_GtkWidget_set_has_tooltip, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_hexpand, arginfo_class_Gtk4_GtkWidget_set_hexpand, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_hexpand_set, arginfo_class_Gtk4_GtkWidget_set_hexpand_set, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_margin_bottom, arginfo_class_Gtk4_GtkWidget_set_margin_bottom, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_margin_end, arginfo_class_Gtk4_GtkWidget_set_margin_end, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_margin_start, arginfo_class_Gtk4_GtkWidget_set_margin_start, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_margin_top, arginfo_class_Gtk4_GtkWidget_set_margin_top, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_name, arginfo_class_Gtk4_GtkWidget_set_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_opacity, arginfo_class_Gtk4_GtkWidget_set_opacity, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_overflow, arginfo_class_Gtk4_GtkWidget_set_overflow, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_parent, arginfo_class_Gtk4_GtkWidget_set_parent, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_receives_default, arginfo_class_Gtk4_GtkWidget_set_receives_default, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_sensitive, arginfo_class_Gtk4_GtkWidget_set_sensitive, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_size_request, arginfo_class_Gtk4_GtkWidget_set_size_request, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_state_flags, arginfo_class_Gtk4_GtkWidget_set_state_flags, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_tooltip_markup, arginfo_class_Gtk4_GtkWidget_set_tooltip_markup, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_tooltip_text, arginfo_class_Gtk4_GtkWidget_set_tooltip_text, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_valign, arginfo_class_Gtk4_GtkWidget_set_valign, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_vexpand, arginfo_class_Gtk4_GtkWidget_set_vexpand, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_vexpand_set, arginfo_class_Gtk4_GtkWidget_set_vexpand_set, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, set_visible, arginfo_class_Gtk4_GtkWidget_set_visible, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, should_layout, arginfo_class_Gtk4_GtkWidget_should_layout, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, trigger_tooltip_query, arginfo_class_Gtk4_GtkWidget_trigger_tooltip_query, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, unmap, arginfo_class_Gtk4_GtkWidget_unmap, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, unparent, arginfo_class_Gtk4_GtkWidget_unparent, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, unrealize, arginfo_class_Gtk4_GtkWidget_unrealize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, unset_state_flags, arginfo_class_Gtk4_GtkWidget_unset_state_flags, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_contains, arginfo_class_Gtk4_GtkWidget_vfunc_contains, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_direction_changed, arginfo_class_Gtk4_GtkWidget_vfunc_direction_changed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_focus, arginfo_class_Gtk4_GtkWidget_vfunc_focus, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_get_request_mode, arginfo_class_Gtk4_GtkWidget_vfunc_get_request_mode, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_grab_focus, arginfo_class_Gtk4_GtkWidget_vfunc_grab_focus, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_keynav_failed, arginfo_class_Gtk4_GtkWidget_vfunc_keynav_failed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_map, arginfo_class_Gtk4_GtkWidget_vfunc_map, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_measure, arginfo_class_Gtk4_GtkWidget_vfunc_measure, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_mnemonic_activate, arginfo_class_Gtk4_GtkWidget_vfunc_mnemonic_activate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_move_focus, arginfo_class_Gtk4_GtkWidget_vfunc_move_focus, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_realize, arginfo_class_Gtk4_GtkWidget_vfunc_realize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_root, arginfo_class_Gtk4_GtkWidget_vfunc_root, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_set_focus_child, arginfo_class_Gtk4_GtkWidget_vfunc_set_focus_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_size_allocate, arginfo_class_Gtk4_GtkWidget_vfunc_size_allocate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_state_flags_changed, arginfo_class_Gtk4_GtkWidget_vfunc_state_flags_changed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_system_setting_changed, arginfo_class_Gtk4_GtkWidget_vfunc_system_setting_changed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_unmap, arginfo_class_Gtk4_GtkWidget_vfunc_unmap, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_unrealize, arginfo_class_Gtk4_GtkWidget_vfunc_unrealize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWidget, vfunc_unroot, arginfo_class_Gtk4_GtkWidget_vfunc_unroot, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GtkWindow_methods[] = {
	ZEND_ME(Gtk4_GtkWindow, __construct, arginfo_class_Gtk4_GtkWindow___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_default_icon_name, arginfo_class_Gtk4_GtkWindow_get_default_icon_name, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GtkWindow, get_toplevels, arginfo_class_Gtk4_GtkWindow_get_toplevels, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GtkWindow, list_toplevels, arginfo_class_Gtk4_GtkWindow_list_toplevels, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GtkWindow, set_auto_startup_notification, arginfo_class_Gtk4_GtkWindow_set_auto_startup_notification, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GtkWindow, set_default_icon_name, arginfo_class_Gtk4_GtkWindow_set_default_icon_name, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GtkWindow, set_interactive_debugging, arginfo_class_Gtk4_GtkWindow_set_interactive_debugging, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GtkWindow, close, arginfo_class_Gtk4_GtkWindow_close, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, destroy, arginfo_class_Gtk4_GtkWindow_destroy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, fullscreen, arginfo_class_Gtk4_GtkWindow_fullscreen, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_application, arginfo_class_Gtk4_GtkWindow_get_application, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_child, arginfo_class_Gtk4_GtkWindow_get_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_decorated, arginfo_class_Gtk4_GtkWindow_get_decorated, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_default_size, arginfo_class_Gtk4_GtkWindow_get_default_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_default_widget, arginfo_class_Gtk4_GtkWindow_get_default_widget, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_deletable, arginfo_class_Gtk4_GtkWindow_get_deletable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_destroy_with_parent, arginfo_class_Gtk4_GtkWindow_get_destroy_with_parent, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_focus, arginfo_class_Gtk4_GtkWindow_get_focus, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_focus_visible, arginfo_class_Gtk4_GtkWindow_get_focus_visible, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_handle_menubar_accel, arginfo_class_Gtk4_GtkWindow_get_handle_menubar_accel, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_hide_on_close, arginfo_class_Gtk4_GtkWindow_get_hide_on_close, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_icon_name, arginfo_class_Gtk4_GtkWindow_get_icon_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_mnemonics_visible, arginfo_class_Gtk4_GtkWindow_get_mnemonics_visible, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_modal, arginfo_class_Gtk4_GtkWindow_get_modal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_resizable, arginfo_class_Gtk4_GtkWindow_get_resizable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_title, arginfo_class_Gtk4_GtkWindow_get_title, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_titlebar, arginfo_class_Gtk4_GtkWindow_get_titlebar, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, get_transient_for, arginfo_class_Gtk4_GtkWindow_get_transient_for, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, has_group, arginfo_class_Gtk4_GtkWindow_has_group, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, is_active, arginfo_class_Gtk4_GtkWindow_is_active, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, is_fullscreen, arginfo_class_Gtk4_GtkWindow_is_fullscreen, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, is_maximized, arginfo_class_Gtk4_GtkWindow_is_maximized, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, is_suspended, arginfo_class_Gtk4_GtkWindow_is_suspended, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, maximize, arginfo_class_Gtk4_GtkWindow_maximize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, minimize, arginfo_class_Gtk4_GtkWindow_minimize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, present, arginfo_class_Gtk4_GtkWindow_present, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_application, arginfo_class_Gtk4_GtkWindow_set_application, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_child, arginfo_class_Gtk4_GtkWindow_set_child, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_decorated, arginfo_class_Gtk4_GtkWindow_set_decorated, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_default_size, arginfo_class_Gtk4_GtkWindow_set_default_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_default_widget, arginfo_class_Gtk4_GtkWindow_set_default_widget, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_deletable, arginfo_class_Gtk4_GtkWindow_set_deletable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_destroy_with_parent, arginfo_class_Gtk4_GtkWindow_set_destroy_with_parent, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_display, arginfo_class_Gtk4_GtkWindow_set_display, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_focus, arginfo_class_Gtk4_GtkWindow_set_focus, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_focus_visible, arginfo_class_Gtk4_GtkWindow_set_focus_visible, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_handle_menubar_accel, arginfo_class_Gtk4_GtkWindow_set_handle_menubar_accel, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_hide_on_close, arginfo_class_Gtk4_GtkWindow_set_hide_on_close, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_icon_name, arginfo_class_Gtk4_GtkWindow_set_icon_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_mnemonics_visible, arginfo_class_Gtk4_GtkWindow_set_mnemonics_visible, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_modal, arginfo_class_Gtk4_GtkWindow_set_modal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_resizable, arginfo_class_Gtk4_GtkWindow_set_resizable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_startup_id, arginfo_class_Gtk4_GtkWindow_set_startup_id, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_title, arginfo_class_Gtk4_GtkWindow_set_title, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_titlebar, arginfo_class_Gtk4_GtkWindow_set_titlebar, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, set_transient_for, arginfo_class_Gtk4_GtkWindow_set_transient_for, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, unfullscreen, arginfo_class_Gtk4_GtkWindow_unfullscreen, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, unmaximize, arginfo_class_Gtk4_GtkWindow_unmaximize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, unminimize, arginfo_class_Gtk4_GtkWindow_unminimize, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("get_display", zim_Gtk4_GtkRoot_get_display, arginfo_class_Gtk4_GtkWindow_get_display, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_ME(Gtk4_GtkWindow, vfunc_activate_default, arginfo_class_Gtk4_GtkWindow_vfunc_activate_default, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, vfunc_activate_focus, arginfo_class_Gtk4_GtkWindow_vfunc_activate_focus, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, vfunc_close_request, arginfo_class_Gtk4_GtkWindow_vfunc_close_request, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, vfunc_enable_debugging, arginfo_class_Gtk4_GtkWindow_vfunc_enable_debugging, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GtkWindow, vfunc_keys_changed, arginfo_class_Gtk4_GtkWindow_vfunc_keys_changed, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

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

static zend_class_entry *register_class_Gtk4_GtkApplication(zend_class_entry *class_entry_Gtk4_GApplication, zend_class_entry *class_entry_Gtk4_GActionGroup, zend_class_entry *class_entry_Gtk4_GActionMap)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkApplication", class_Gtk4_GtkApplication_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GApplication, 0);
	zend_class_implements(class_entry, 2, class_entry_Gtk4_GActionGroup, class_entry_Gtk4_GActionMap);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkApplicationInhibitFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkApplicationInhibitFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_LOGOUT_value;
	ZVAL_LONG(&const_LOGOUT_value, 1);
	zend_string *const_LOGOUT_name = zend_string_init_interned("LOGOUT", sizeof("LOGOUT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_LOGOUT_name, &const_LOGOUT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_LOGOUT_name);

	zval const_SWITCH_value;
	ZVAL_LONG(&const_SWITCH_value, 2);
	zend_string *const_SWITCH_name = zend_string_init_interned("SWITCH", sizeof("SWITCH") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_SWITCH_name, &const_SWITCH_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_SWITCH_name);

	zval const_SUSPEND_value;
	ZVAL_LONG(&const_SUSPEND_value, 4);
	zend_string *const_SUSPEND_name = zend_string_init_interned("SUSPEND", sizeof("SUSPEND") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_SUSPEND_name, &const_SUSPEND_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_SUSPEND_name);

	zval const_IDLE_value;
	ZVAL_LONG(&const_IDLE_value, 8);
	zend_string *const_IDLE_name = zend_string_init_interned("IDLE", sizeof("IDLE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_IDLE_name, &const_IDLE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_IDLE_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkBaselinePosition(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GtkBaselinePosition", IS_LONG, NULL);

	zval enum_case_Top_value;
	ZVAL_LONG(&enum_case_Top_value, 0);
	zend_enum_add_case_cstr(class_entry, "Top", &enum_case_Top_value);

	zval enum_case_Center_value;
	ZVAL_LONG(&enum_case_Center_value, 1);
	zend_enum_add_case_cstr(class_entry, "Center", &enum_case_Center_value);

	zval enum_case_Bottom_value;
	ZVAL_LONG(&enum_case_Bottom_value, 2);
	zend_enum_add_case_cstr(class_entry, "Bottom", &enum_case_Bottom_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkBox(zend_class_entry *class_entry_Gtk4_GtkWidget, zend_class_entry *class_entry_Gtk4_GtkOrientable)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkBox", class_Gtk4_GtkBox_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GtkWidget, 0);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GtkOrientable);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkButton(zend_class_entry *class_entry_Gtk4_GtkWidget)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkButton", class_Gtk4_GtkButton_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GtkWidget, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkCssProvider(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GtkStyleProvider)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkCssProvider", class_Gtk4_GtkCssProvider_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GtkStyleProvider);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkCustomFilter(zend_class_entry *class_entry_Gtk4_GtkFilter)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkCustomFilter", class_Gtk4_GtkCustomFilter_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GtkFilter, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkCustomSorter(zend_class_entry *class_entry_Gtk4_GtkSorter)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkCustomSorter", class_Gtk4_GtkCustomSorter_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GtkSorter, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkDirectionType(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GtkDirectionType", IS_LONG, NULL);

	zval enum_case_TabForward_value;
	ZVAL_LONG(&enum_case_TabForward_value, 0);
	zend_enum_add_case_cstr(class_entry, "TabForward", &enum_case_TabForward_value);

	zval enum_case_TabBackward_value;
	ZVAL_LONG(&enum_case_TabBackward_value, 1);
	zend_enum_add_case_cstr(class_entry, "TabBackward", &enum_case_TabBackward_value);

	zval enum_case_Up_value;
	ZVAL_LONG(&enum_case_Up_value, 2);
	zend_enum_add_case_cstr(class_entry, "Up", &enum_case_Up_value);

	zval enum_case_Down_value;
	ZVAL_LONG(&enum_case_Down_value, 3);
	zend_enum_add_case_cstr(class_entry, "Down", &enum_case_Down_value);

	zval enum_case_Left_value;
	ZVAL_LONG(&enum_case_Left_value, 4);
	zend_enum_add_case_cstr(class_entry, "Left", &enum_case_Left_value);

	zval enum_case_Right_value;
	ZVAL_LONG(&enum_case_Right_value, 5);
	zend_enum_add_case_cstr(class_entry, "Right", &enum_case_Right_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkDrawingArea(zend_class_entry *class_entry_Gtk4_GtkWidget)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkDrawingArea", class_Gtk4_GtkDrawingArea_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GtkWidget, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkFilter(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkFilter", class_Gtk4_GtkFilter_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

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

static zend_class_entry *register_class_Gtk4_GtkFilterListModel(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GListModel)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkFilterListModel", class_Gtk4_GtkFilterListModel_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GListModel);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkFilterMatch(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GtkFilterMatch", IS_LONG, NULL);

	zval enum_case_Some_value;
	ZVAL_LONG(&enum_case_Some_value, 0);
	zend_enum_add_case_cstr(class_entry, "Some", &enum_case_Some_value);

	zval enum_case_None_value;
	ZVAL_LONG(&enum_case_None_value, 1);
	zend_enum_add_case_cstr(class_entry, "None", &enum_case_None_value);

	zval enum_case_All_value;
	ZVAL_LONG(&enum_case_All_value, 2);
	zend_enum_add_case_cstr(class_entry, "All", &enum_case_All_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkJustification(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GtkJustification", IS_LONG, NULL);

	zval enum_case_Left_value;
	ZVAL_LONG(&enum_case_Left_value, 0);
	zend_enum_add_case_cstr(class_entry, "Left", &enum_case_Left_value);

	zval enum_case_Right_value;
	ZVAL_LONG(&enum_case_Right_value, 1);
	zend_enum_add_case_cstr(class_entry, "Right", &enum_case_Right_value);

	zval enum_case_Center_value;
	ZVAL_LONG(&enum_case_Center_value, 2);
	zend_enum_add_case_cstr(class_entry, "Center", &enum_case_Center_value);

	zval enum_case_Fill_value;
	ZVAL_LONG(&enum_case_Fill_value, 3);
	zend_enum_add_case_cstr(class_entry, "Fill", &enum_case_Fill_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkLabel(zend_class_entry *class_entry_Gtk4_GtkWidget)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkLabel", class_Gtk4_GtkLabel_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GtkWidget, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkNaturalWrapMode(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GtkNaturalWrapMode", IS_LONG, NULL);

	zval enum_case_Inherit_value;
	ZVAL_LONG(&enum_case_Inherit_value, 0);
	zend_enum_add_case_cstr(class_entry, "Inherit", &enum_case_Inherit_value);

	zval enum_case_None_value;
	ZVAL_LONG(&enum_case_None_value, 1);
	zend_enum_add_case_cstr(class_entry, "None", &enum_case_None_value);

	zval enum_case_Word_value;
	ZVAL_LONG(&enum_case_Word_value, 2);
	zend_enum_add_case_cstr(class_entry, "Word", &enum_case_Word_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkOrdering(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GtkOrdering", IS_LONG, NULL);

	zval enum_case_Smaller_value;
	ZVAL_LONG(&enum_case_Smaller_value, -1);
	zend_enum_add_case_cstr(class_entry, "Smaller", &enum_case_Smaller_value);

	zval enum_case_Equal_value;
	ZVAL_LONG(&enum_case_Equal_value, 0);
	zend_enum_add_case_cstr(class_entry, "Equal", &enum_case_Equal_value);

	zval enum_case_Larger_value;
	ZVAL_LONG(&enum_case_Larger_value, 1);
	zend_enum_add_case_cstr(class_entry, "Larger", &enum_case_Larger_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkOrientable(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkOrientable", class_Gtk4_GtkOrientable_methods);
	class_entry = zend_register_internal_interface(&ce);

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

static zend_class_entry *register_class_Gtk4_GtkOverflow(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GtkOverflow", IS_LONG, NULL);

	zval enum_case_Visible_value;
	ZVAL_LONG(&enum_case_Visible_value, 0);
	zend_enum_add_case_cstr(class_entry, "Visible", &enum_case_Visible_value);

	zval enum_case_Hidden_value;
	ZVAL_LONG(&enum_case_Hidden_value, 1);
	zend_enum_add_case_cstr(class_entry, "Hidden", &enum_case_Hidden_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkPickFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkPickFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_DEFAULT_value;
	ZVAL_LONG(&const_DEFAULT_value, 0);
	zend_string *const_DEFAULT_name = zend_string_init_interned("DEFAULT", sizeof("DEFAULT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_DEFAULT_name, &const_DEFAULT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_DEFAULT_name);

	zval const_INSENSITIVE_value;
	ZVAL_LONG(&const_INSENSITIVE_value, 1);
	zend_string *const_INSENSITIVE_name = zend_string_init_interned("INSENSITIVE", sizeof("INSENSITIVE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_INSENSITIVE_name, &const_INSENSITIVE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_INSENSITIVE_name);

	zval const_NON_TARGETABLE_value;
	ZVAL_LONG(&const_NON_TARGETABLE_value, 2);
	zend_string *const_NON_TARGETABLE_name = zend_string_init_interned("NON_TARGETABLE", sizeof("NON_TARGETABLE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NON_TARGETABLE_name, &const_NON_TARGETABLE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NON_TARGETABLE_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkRoot(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkRoot", class_Gtk4_GtkRoot_methods);
	class_entry = zend_register_internal_interface(&ce);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkSizeRequestMode(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GtkSizeRequestMode", IS_LONG, NULL);

	zval enum_case_HeightForWidth_value;
	ZVAL_LONG(&enum_case_HeightForWidth_value, 0);
	zend_enum_add_case_cstr(class_entry, "HeightForWidth", &enum_case_HeightForWidth_value);

	zval enum_case_WidthForHeight_value;
	ZVAL_LONG(&enum_case_WidthForHeight_value, 1);
	zend_enum_add_case_cstr(class_entry, "WidthForHeight", &enum_case_WidthForHeight_value);

	zval enum_case_ConstantSize_value;
	ZVAL_LONG(&enum_case_ConstantSize_value, 2);
	zend_enum_add_case_cstr(class_entry, "ConstantSize", &enum_case_ConstantSize_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkSortListModel(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GListModel)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkSortListModel", class_Gtk4_GtkSortListModel_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GListModel);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkSorter(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkSorter", class_Gtk4_GtkSorter_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

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

static zend_class_entry *register_class_Gtk4_GtkSorterOrder(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GtkSorterOrder", IS_LONG, NULL);

	zval enum_case_Partial_value;
	ZVAL_LONG(&enum_case_Partial_value, 0);
	zend_enum_add_case_cstr(class_entry, "Partial", &enum_case_Partial_value);

	zval enum_case_None_value;
	ZVAL_LONG(&enum_case_None_value, 1);
	zend_enum_add_case_cstr(class_entry, "None", &enum_case_None_value);

	zval enum_case_Total_value;
	ZVAL_LONG(&enum_case_Total_value, 2);
	zend_enum_add_case_cstr(class_entry, "Total", &enum_case_Total_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkStateFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkStateFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_NORMAL_value;
	ZVAL_LONG(&const_NORMAL_value, 0);
	zend_string *const_NORMAL_name = zend_string_init_interned("NORMAL", sizeof("NORMAL") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NORMAL_name, &const_NORMAL_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NORMAL_name);

	zval const_ACTIVE_value;
	ZVAL_LONG(&const_ACTIVE_value, 1);
	zend_string *const_ACTIVE_name = zend_string_init_interned("ACTIVE", sizeof("ACTIVE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_ACTIVE_name, &const_ACTIVE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_ACTIVE_name);

	zval const_PRELIGHT_value;
	ZVAL_LONG(&const_PRELIGHT_value, 2);
	zend_string *const_PRELIGHT_name = zend_string_init_interned("PRELIGHT", sizeof("PRELIGHT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_PRELIGHT_name, &const_PRELIGHT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_PRELIGHT_name);

	zval const_SELECTED_value;
	ZVAL_LONG(&const_SELECTED_value, 4);
	zend_string *const_SELECTED_name = zend_string_init_interned("SELECTED", sizeof("SELECTED") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_SELECTED_name, &const_SELECTED_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_SELECTED_name);

	zval const_INSENSITIVE_value;
	ZVAL_LONG(&const_INSENSITIVE_value, 8);
	zend_string *const_INSENSITIVE_name = zend_string_init_interned("INSENSITIVE", sizeof("INSENSITIVE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_INSENSITIVE_name, &const_INSENSITIVE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_INSENSITIVE_name);

	zval const_INCONSISTENT_value;
	ZVAL_LONG(&const_INCONSISTENT_value, 16);
	zend_string *const_INCONSISTENT_name = zend_string_init_interned("INCONSISTENT", sizeof("INCONSISTENT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_INCONSISTENT_name, &const_INCONSISTENT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_INCONSISTENT_name);

	zval const_FOCUSED_value;
	ZVAL_LONG(&const_FOCUSED_value, 32);
	zend_string *const_FOCUSED_name = zend_string_init_interned("FOCUSED", sizeof("FOCUSED") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_FOCUSED_name, &const_FOCUSED_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_FOCUSED_name);

	zval const_BACKDROP_value;
	ZVAL_LONG(&const_BACKDROP_value, 64);
	zend_string *const_BACKDROP_name = zend_string_init_interned("BACKDROP", sizeof("BACKDROP") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_BACKDROP_name, &const_BACKDROP_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_BACKDROP_name);

	zval const_DIR_LTR_value;
	ZVAL_LONG(&const_DIR_LTR_value, 128);
	zend_string *const_DIR_LTR_name = zend_string_init_interned("DIR_LTR", sizeof("DIR_LTR") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_DIR_LTR_name, &const_DIR_LTR_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_DIR_LTR_name);

	zval const_DIR_RTL_value;
	ZVAL_LONG(&const_DIR_RTL_value, 256);
	zend_string *const_DIR_RTL_name = zend_string_init_interned("DIR_RTL", sizeof("DIR_RTL") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_DIR_RTL_name, &const_DIR_RTL_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_DIR_RTL_name);

	zval const_LINK_value;
	ZVAL_LONG(&const_LINK_value, 512);
	zend_string *const_LINK_name = zend_string_init_interned("LINK", sizeof("LINK") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_LINK_name, &const_LINK_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_LINK_name);

	zval const_VISITED_value;
	ZVAL_LONG(&const_VISITED_value, 1024);
	zend_string *const_VISITED_name = zend_string_init_interned("VISITED", sizeof("VISITED") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_VISITED_name, &const_VISITED_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_VISITED_name);

	zval const_CHECKED_value;
	ZVAL_LONG(&const_CHECKED_value, 2048);
	zend_string *const_CHECKED_name = zend_string_init_interned("CHECKED", sizeof("CHECKED") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_CHECKED_name, &const_CHECKED_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_CHECKED_name);

	zval const_DROP_ACTIVE_value;
	ZVAL_LONG(&const_DROP_ACTIVE_value, 4096);
	zend_string *const_DROP_ACTIVE_name = zend_string_init_interned("DROP_ACTIVE", sizeof("DROP_ACTIVE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_DROP_ACTIVE_name, &const_DROP_ACTIVE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_DROP_ACTIVE_name);

	zval const_FOCUS_VISIBLE_value;
	ZVAL_LONG(&const_FOCUS_VISIBLE_value, 8192);
	zend_string *const_FOCUS_VISIBLE_name = zend_string_init_interned("FOCUS_VISIBLE", sizeof("FOCUS_VISIBLE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_FOCUS_VISIBLE_name, &const_FOCUS_VISIBLE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_FOCUS_VISIBLE_name);

	zval const_FOCUS_WITHIN_value;
	ZVAL_LONG(&const_FOCUS_WITHIN_value, 16384);
	zend_string *const_FOCUS_WITHIN_name = zend_string_init_interned("FOCUS_WITHIN", sizeof("FOCUS_WITHIN") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_FOCUS_WITHIN_name, &const_FOCUS_WITHIN_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_FOCUS_WITHIN_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkStyleProvider(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkStyleProvider", NULL);
	class_entry = zend_register_internal_interface(&ce);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkTextDirection(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GtkTextDirection", IS_LONG, NULL);

	zval enum_case_None_value;
	ZVAL_LONG(&enum_case_None_value, 0);
	zend_enum_add_case_cstr(class_entry, "None", &enum_case_None_value);

	zval enum_case_Ltr_value;
	ZVAL_LONG(&enum_case_Ltr_value, 1);
	zend_enum_add_case_cstr(class_entry, "Ltr", &enum_case_Ltr_value);

	zval enum_case_Rtl_value;
	ZVAL_LONG(&enum_case_Rtl_value, 2);
	zend_enum_add_case_cstr(class_entry, "Rtl", &enum_case_Rtl_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkWidget(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkWidget", class_Gtk4_GtkWidget_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GtkWindow(zend_class_entry *class_entry_Gtk4_GtkWidget, zend_class_entry *class_entry_Gtk4_GtkRoot)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GtkWindow", class_Gtk4_GtkWindow_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GtkWidget, 0);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GtkRoot);

	return class_entry;
}
