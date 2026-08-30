/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: e737fdcfe153666ebb47d79400836380d0bffc7a */

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GAction_change_state, 0, 0, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, value, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GAction_get_enabled, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GAction_get_name, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GAction_get_parameter_type, 0, 0, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GAction_get_state, 0, 0, IS_MIXED, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GAction_get_state_hint arginfo_class_Gtk4_GAction_get_state

#define arginfo_class_Gtk4_GAction_get_state_type arginfo_class_Gtk4_GAction_get_parameter_type

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GAction_activate, 0, 0, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameter, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GActionObject___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GActionObject_activate arginfo_class_Gtk4_GAction_activate

#define arginfo_class_Gtk4_GActionObject_change_state arginfo_class_Gtk4_GAction_change_state

#define arginfo_class_Gtk4_GActionObject_get_enabled arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GActionObject_get_name arginfo_class_Gtk4_GAction_get_name

#define arginfo_class_Gtk4_GActionObject_get_parameter_type arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GActionObject_get_state arginfo_class_Gtk4_GAction_get_state

#define arginfo_class_Gtk4_GActionObject_get_state_hint arginfo_class_Gtk4_GAction_get_state

#define arginfo_class_Gtk4_GActionObject_get_state_type arginfo_class_Gtk4_GAction_get_parameter_type

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionGroup_action_added, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionGroup_action_enabled_changed, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, enabled, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GActionGroup_action_removed arginfo_class_Gtk4_GActionGroup_action_added

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionGroup_action_state_changed, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, state, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionGroup_change_action_state, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, value, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionGroup_get_action_enabled, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionGroup_get_action_parameter_type, 0, 1, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionGroup_get_action_state, 0, 1, IS_MIXED, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GActionGroup_get_action_state_hint arginfo_class_Gtk4_GActionGroup_get_action_state

#define arginfo_class_Gtk4_GActionGroup_get_action_state_type arginfo_class_Gtk4_GActionGroup_get_action_parameter_type

#define arginfo_class_Gtk4_GActionGroup_has_action arginfo_class_Gtk4_GActionGroup_get_action_enabled

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionGroup_list_actions, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionGroup_activate_action, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameter, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GActionGroupObject___construct arginfo_class_Gtk4_GActionObject___construct

#define arginfo_class_Gtk4_GActionGroupObject_action_added arginfo_class_Gtk4_GActionGroup_action_added

#define arginfo_class_Gtk4_GActionGroupObject_action_enabled_changed arginfo_class_Gtk4_GActionGroup_action_enabled_changed

#define arginfo_class_Gtk4_GActionGroupObject_action_removed arginfo_class_Gtk4_GActionGroup_action_added

#define arginfo_class_Gtk4_GActionGroupObject_action_state_changed arginfo_class_Gtk4_GActionGroup_action_state_changed

#define arginfo_class_Gtk4_GActionGroupObject_activate_action arginfo_class_Gtk4_GActionGroup_activate_action

#define arginfo_class_Gtk4_GActionGroupObject_change_action_state arginfo_class_Gtk4_GActionGroup_change_action_state

#define arginfo_class_Gtk4_GActionGroupObject_get_action_enabled arginfo_class_Gtk4_GActionGroup_get_action_enabled

#define arginfo_class_Gtk4_GActionGroupObject_get_action_parameter_type arginfo_class_Gtk4_GActionGroup_get_action_parameter_type

#define arginfo_class_Gtk4_GActionGroupObject_get_action_state arginfo_class_Gtk4_GActionGroup_get_action_state

#define arginfo_class_Gtk4_GActionGroupObject_get_action_state_hint arginfo_class_Gtk4_GActionGroup_get_action_state

#define arginfo_class_Gtk4_GActionGroupObject_get_action_state_type arginfo_class_Gtk4_GActionGroup_get_action_parameter_type

#define arginfo_class_Gtk4_GActionGroupObject_has_action arginfo_class_Gtk4_GActionGroup_get_action_enabled

#define arginfo_class_Gtk4_GActionGroupObject_list_actions arginfo_class_Gtk4_GActionGroup_list_actions

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionMap_add_action, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, action, Gtk4\\GAction, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GActionMap_lookup_action, 0, 1, Gtk4\\GAction, 1)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GActionMap_remove_action arginfo_class_Gtk4_GActionGroup_action_added

#define arginfo_class_Gtk4_GActionMapObject___construct arginfo_class_Gtk4_GActionObject___construct

#define arginfo_class_Gtk4_GActionMapObject_add_action arginfo_class_Gtk4_GActionMap_add_action

#define arginfo_class_Gtk4_GActionMapObject_lookup_action arginfo_class_Gtk4_GActionMap_lookup_action

#define arginfo_class_Gtk4_GActionMapObject_remove_action arginfo_class_Gtk4_GActionGroup_action_added

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GApplication___construct, 0, 0, 2)
	ZEND_ARG_TYPE_INFO(0, application_id, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GApplication_get_default, 0, 0, Gtk4\\GApplication, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_id_is_valid, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, application_id, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_activate, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_add_main_option, 0, 6, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, long_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, short_name, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, arg, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, description, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, arg_description, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_bind_busy_property, 0, 2, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, object, Gtk4\\GObject, 0)
	ZEND_ARG_TYPE_INFO(0, property, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GApplication_get_application_id arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GApplication_get_dbus_object_path arginfo_class_Gtk4_GAction_get_parameter_type

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_get_flags, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GApplication_get_inactivity_timeout arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GApplication_get_is_busy arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GApplication_get_is_registered arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GApplication_get_is_remote arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GApplication_get_resource_base_path arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GApplication_get_version arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GApplication_hold arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GApplication_mark_busy arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GApplication_quit arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_register, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, cancellable, Gtk4\\GCancellable, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GApplication_release arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_set_application_id, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, application_id, IS_STRING, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GApplication_set_default arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_set_flags, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_set_inactivity_timeout, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, inactivity_timeout, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_set_option_context_description, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, description, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_set_option_context_parameter_string, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, parameter_string, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_set_option_context_summary, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, summary, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_set_resource_base_path, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, resource_path, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_set_version, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, version, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GApplication_unbind_busy_property arginfo_class_Gtk4_GApplication_bind_busy_property

#define arginfo_class_Gtk4_GApplication_unmark_busy arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_withdraw_notification, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, id, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_run, 0, 0, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, argv, IS_ARRAY, 0, "[]")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GApplication_action_added arginfo_class_Gtk4_GActionGroup_action_added

#define arginfo_class_Gtk4_GApplication_action_enabled_changed arginfo_class_Gtk4_GActionGroup_action_enabled_changed

#define arginfo_class_Gtk4_GApplication_action_removed arginfo_class_Gtk4_GActionGroup_action_added

#define arginfo_class_Gtk4_GApplication_action_state_changed arginfo_class_Gtk4_GActionGroup_action_state_changed

#define arginfo_class_Gtk4_GApplication_activate_action arginfo_class_Gtk4_GActionGroup_activate_action

#define arginfo_class_Gtk4_GApplication_change_action_state arginfo_class_Gtk4_GActionGroup_change_action_state

#define arginfo_class_Gtk4_GApplication_get_action_enabled arginfo_class_Gtk4_GActionGroup_get_action_enabled

#define arginfo_class_Gtk4_GApplication_get_action_parameter_type arginfo_class_Gtk4_GActionGroup_get_action_parameter_type

#define arginfo_class_Gtk4_GApplication_get_action_state arginfo_class_Gtk4_GActionGroup_get_action_state

#define arginfo_class_Gtk4_GApplication_get_action_state_hint arginfo_class_Gtk4_GActionGroup_get_action_state

#define arginfo_class_Gtk4_GApplication_get_action_state_type arginfo_class_Gtk4_GActionGroup_get_action_parameter_type

#define arginfo_class_Gtk4_GApplication_has_action arginfo_class_Gtk4_GActionGroup_get_action_enabled

#define arginfo_class_Gtk4_GApplication_list_actions arginfo_class_Gtk4_GActionGroup_list_actions

#define arginfo_class_Gtk4_GApplication_add_action arginfo_class_Gtk4_GActionMap_add_action

#define arginfo_class_Gtk4_GApplication_lookup_action arginfo_class_Gtk4_GActionMap_lookup_action

#define arginfo_class_Gtk4_GApplication_remove_action arginfo_class_Gtk4_GActionGroup_action_added

#define arginfo_class_Gtk4_GApplication_vfunc_activate arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GApplication_vfunc_name_lost arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GApplication_vfunc_quit_mainloop arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GApplication_vfunc_run_mainloop arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GApplication_vfunc_shutdown arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GApplication_vfunc_startup arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GAsyncResult_get_source_object, 0, 0, Gtk4\\GObject, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GAsyncResultObject___construct arginfo_class_Gtk4_GActionObject___construct

#define arginfo_class_Gtk4_GAsyncResultObject_get_source_object arginfo_class_Gtk4_GAsyncResult_get_source_object

#define arginfo_class_Gtk4_GAsyncResultObject_legacy_propagate_error arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GCancellable___construct arginfo_class_Gtk4_GActionObject___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GCancellable_get_current, 0, 0, Gtk4\\GCancellable, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GCancellable_cancel arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GCancellable_disconnect, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, handler_id, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GCancellable_get_fd arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GCancellable_is_cancelled arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GCancellable_pop_current arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GCancellable_push_current arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GCancellable_release_fd arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GCancellable_reset arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GCancellable_set_error_if_cancelled arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GCancellable_vfunc_cancelled arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GListModel_get_item_type arginfo_class_Gtk4_GAction_get_name

#define arginfo_class_Gtk4_GListModel_get_n_items arginfo_class_Gtk4_GApplication_get_flags

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GListModel_get_item, 0, 1, Gtk4\\GObject, 1)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GListModelObject___construct arginfo_class_Gtk4_GActionObject___construct

#define arginfo_class_Gtk4_GListModelObject_get_item_type arginfo_class_Gtk4_GAction_get_name

#define arginfo_class_Gtk4_GListModelObject_get_n_items arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GListModelObject_get_item arginfo_class_Gtk4_GListModel_get_item

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GListModelObject_items_changed, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, removed, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, added, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GListStore_append, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, item, Gtk4\\GObject, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GListStore_find, 0, 1, IS_LONG, 1)
	ZEND_ARG_OBJ_INFO(0, item, Gtk4\\GObject, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GListStore_insert, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, item, Gtk4\\GObject, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GListStore_remove, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GListStore_remove_all arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GListStore___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, item_type, IS_STRING, 0, "Gtk4\\GObject::class")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GListStore_get_item_type arginfo_class_Gtk4_GAction_get_name

#define arginfo_class_Gtk4_GListStore_get_n_items arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GListStore_get_item arginfo_class_Gtk4_GListModel_get_item

#define arginfo_class_Gtk4_GListStore_items_changed arginfo_class_Gtk4_GListModelObject_items_changed

#define arginfo_class_Gtk4_GMenu___construct arginfo_class_Gtk4_GActionObject___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenu_append, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, label, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, detailed_action, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenu_append_item, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, item, Gtk4\\GMenuItem, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenu_append_section, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, label, IS_STRING, 1)
	ZEND_ARG_OBJ_INFO(0, section, Gtk4\\GMenuModel, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenu_append_submenu, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, label, IS_STRING, 1)
	ZEND_ARG_OBJ_INFO(0, submenu, Gtk4\\GMenuModel, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GMenu_freeze arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenu_insert, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, label, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, detailed_action, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenu_insert_item, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, item, Gtk4\\GMenuItem, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenu_insert_section, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, label, IS_STRING, 1)
	ZEND_ARG_OBJ_INFO(0, section, Gtk4\\GMenuModel, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenu_insert_submenu, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, label, IS_STRING, 1)
	ZEND_ARG_OBJ_INFO(0, submenu, Gtk4\\GMenuModel, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GMenu_prepend arginfo_class_Gtk4_GMenu_append

#define arginfo_class_Gtk4_GMenu_prepend_item arginfo_class_Gtk4_GMenu_append_item

#define arginfo_class_Gtk4_GMenu_prepend_section arginfo_class_Gtk4_GMenu_append_section

#define arginfo_class_Gtk4_GMenu_prepend_submenu arginfo_class_Gtk4_GMenu_append_submenu

#define arginfo_class_Gtk4_GMenu_remove arginfo_class_Gtk4_GListStore_remove

#define arginfo_class_Gtk4_GMenu_remove_all arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GMenuItem___construct, 0, 0, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, label, IS_STRING, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, detailed_action, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GMenuItem_new_from_model, 0, 2, Gtk4\\GMenuItem, 0)
	ZEND_ARG_OBJ_INFO(0, model, Gtk4\\GMenuModel, 0)
	ZEND_ARG_TYPE_INFO(0, item_index, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GMenuItem_new_section, 0, 2, Gtk4\\GMenuItem, 0)
	ZEND_ARG_TYPE_INFO(0, label, IS_STRING, 1)
	ZEND_ARG_OBJ_INFO(0, section, Gtk4\\GMenuModel, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GMenuItem_new_submenu, 0, 2, Gtk4\\GMenuItem, 0)
	ZEND_ARG_TYPE_INFO(0, label, IS_STRING, 1)
	ZEND_ARG_OBJ_INFO(0, submenu, Gtk4\\GMenuModel, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenuItem_get_attribute_value, 0, 2, IS_MIXED, 0)
	ZEND_ARG_TYPE_INFO(0, attribute, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, expected_type, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GMenuItem_get_link, 0, 1, Gtk4\\GMenuModel, 1)
	ZEND_ARG_TYPE_INFO(0, link, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenuItem_set_action_and_target_value, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, action, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, target_value, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenuItem_set_attribute_value, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, attribute, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, value, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenuItem_set_detailed_action, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, detailed_action, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenuItem_set_label, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, label, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenuItem_set_link, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, link, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO(0, model, Gtk4\\GMenuModel, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenuItem_set_section, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, section, Gtk4\\GMenuModel, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenuItem_set_submenu, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, submenu, Gtk4\\GMenuModel, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GMenuModel___construct arginfo_class_Gtk4_GActionObject___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenuModel_get_item_attribute_value, 0, 3, IS_MIXED, 0)
	ZEND_ARG_TYPE_INFO(0, item_index, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, attribute, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, expected_type, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GMenuModel_get_item_link, 0, 2, Gtk4\\GMenuModel, 1)
	ZEND_ARG_TYPE_INFO(0, item_index, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, link, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GMenuModel_get_n_items arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GMenuModel_is_mutable arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GMenuModel_items_changed arginfo_class_Gtk4_GListModelObject_items_changed

#define arginfo_class_Gtk4_GMenuModel_vfunc_get_item_link arginfo_class_Gtk4_GMenuModel_get_item_link

#define arginfo_class_Gtk4_GMenuModel_vfunc_get_n_items arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GMenuModel_vfunc_is_mutable arginfo_class_Gtk4_GAction_get_enabled

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GSimpleAction___construct, 0, 0, 1)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameter_type, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GSimpleAction_new_stateful, 0, 2, Gtk4\\GSimpleAction, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, parameter_type, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, state, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GSimpleAction_set_enabled, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, enabled, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GSimpleAction_set_state_hint, 0, 0, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, state_hint, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GSimpleAction_set_state, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, value, IS_MIXED, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GSimpleAction_activate arginfo_class_Gtk4_GAction_activate

#define arginfo_class_Gtk4_GSimpleAction_change_state arginfo_class_Gtk4_GAction_change_state

#define arginfo_class_Gtk4_GSimpleAction_get_enabled arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GSimpleAction_get_name arginfo_class_Gtk4_GAction_get_name

#define arginfo_class_Gtk4_GSimpleAction_get_parameter_type arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GSimpleAction_get_state arginfo_class_Gtk4_GAction_get_state

#define arginfo_class_Gtk4_GSimpleAction_get_state_hint arginfo_class_Gtk4_GAction_get_state

#define arginfo_class_Gtk4_GSimpleAction_get_state_type arginfo_class_Gtk4_GAction_get_parameter_type

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GTask___construct, 0, 0, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, source_object, Gtk4\\GObject, 1, "null")
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, cancellable, Gtk4\\GCancellable, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, callback, IS_CALLABLE, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTask_is_valid, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, result, Gtk4\\GAsyncResult, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, source_object, Gtk4\\GObject, 1, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GTask_get_cancellable arginfo_class_Gtk4_GCancellable_get_current

#define arginfo_class_Gtk4_GTask_get_check_cancellable arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GTask_get_completed arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GTask_get_name arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GTask_get_priority arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GTask_get_return_on_cancel arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GTask_get_source_object arginfo_class_Gtk4_GAsyncResult_get_source_object

#define arginfo_class_Gtk4_GTask_had_error arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GTask_propagate_boolean arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GTask_propagate_int arginfo_class_Gtk4_GApplication_get_flags

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTask_return_boolean, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, result, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTask_return_error, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, error, Gtk4\\GError, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GTask_return_error_if_cancelled arginfo_class_Gtk4_GAction_get_enabled

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTask_return_int, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, result, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTask_set_check_cancellable, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, check_cancellable, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTask_set_name, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTask_set_priority, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, priority, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTask_set_return_on_cancel, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, return_on_cancel, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GTask_set_static_name arginfo_class_Gtk4_GTask_set_name

#define arginfo_class_Gtk4_GTask_legacy_propagate_error arginfo_class_Gtk4_GAction_get_enabled

ZEND_METHOD(Gtk4_GActionObject, __construct);
ZEND_METHOD(Gtk4_GActionGroupObject, __construct);
ZEND_METHOD(Gtk4_GActionMapObject, __construct);
ZEND_METHOD(Gtk4_GApplication, __construct);
ZEND_METHOD(Gtk4_GApplication, get_default);
ZEND_METHOD(Gtk4_GApplication, id_is_valid);
ZEND_METHOD(Gtk4_GApplication, activate);
ZEND_METHOD(Gtk4_GApplication, add_main_option);
ZEND_METHOD(Gtk4_GApplication, bind_busy_property);
ZEND_METHOD(Gtk4_GApplication, get_application_id);
ZEND_METHOD(Gtk4_GApplication, get_dbus_object_path);
ZEND_METHOD(Gtk4_GApplication, get_flags);
ZEND_METHOD(Gtk4_GApplication, get_inactivity_timeout);
ZEND_METHOD(Gtk4_GApplication, get_is_busy);
ZEND_METHOD(Gtk4_GApplication, get_is_registered);
ZEND_METHOD(Gtk4_GApplication, get_is_remote);
ZEND_METHOD(Gtk4_GApplication, get_resource_base_path);
ZEND_METHOD(Gtk4_GApplication, get_version);
ZEND_METHOD(Gtk4_GApplication, hold);
ZEND_METHOD(Gtk4_GApplication, mark_busy);
ZEND_METHOD(Gtk4_GApplication, quit);
ZEND_METHOD(Gtk4_GApplication, register);
ZEND_METHOD(Gtk4_GApplication, release);
ZEND_METHOD(Gtk4_GApplication, set_application_id);
ZEND_METHOD(Gtk4_GApplication, set_default);
ZEND_METHOD(Gtk4_GApplication, set_flags);
ZEND_METHOD(Gtk4_GApplication, set_inactivity_timeout);
ZEND_METHOD(Gtk4_GApplication, set_option_context_description);
ZEND_METHOD(Gtk4_GApplication, set_option_context_parameter_string);
ZEND_METHOD(Gtk4_GApplication, set_option_context_summary);
ZEND_METHOD(Gtk4_GApplication, set_resource_base_path);
ZEND_METHOD(Gtk4_GApplication, set_version);
ZEND_METHOD(Gtk4_GApplication, unbind_busy_property);
ZEND_METHOD(Gtk4_GApplication, unmark_busy);
ZEND_METHOD(Gtk4_GApplication, withdraw_notification);
ZEND_METHOD(Gtk4_GApplication, run);
ZEND_METHOD(Gtk4_GApplication, vfunc_activate);
ZEND_METHOD(Gtk4_GApplication, vfunc_name_lost);
ZEND_METHOD(Gtk4_GApplication, vfunc_quit_mainloop);
ZEND_METHOD(Gtk4_GApplication, vfunc_run_mainloop);
ZEND_METHOD(Gtk4_GApplication, vfunc_shutdown);
ZEND_METHOD(Gtk4_GApplication, vfunc_startup);
ZEND_METHOD(Gtk4_GAsyncResultObject, __construct);
ZEND_METHOD(Gtk4_GAsyncResult, legacy_propagate_error);
ZEND_METHOD(Gtk4_GCancellable, __construct);
ZEND_METHOD(Gtk4_GCancellable, get_current);
ZEND_METHOD(Gtk4_GCancellable, cancel);
ZEND_METHOD(Gtk4_GCancellable, disconnect);
ZEND_METHOD(Gtk4_GCancellable, get_fd);
ZEND_METHOD(Gtk4_GCancellable, is_cancelled);
ZEND_METHOD(Gtk4_GCancellable, pop_current);
ZEND_METHOD(Gtk4_GCancellable, push_current);
ZEND_METHOD(Gtk4_GCancellable, release_fd);
ZEND_METHOD(Gtk4_GCancellable, reset);
ZEND_METHOD(Gtk4_GCancellable, set_error_if_cancelled);
ZEND_METHOD(Gtk4_GCancellable, vfunc_cancelled);
ZEND_METHOD(Gtk4_GListModelObject, __construct);
ZEND_METHOD(Gtk4_GListModel, items_changed);
ZEND_METHOD(Gtk4_GListStore, append);
ZEND_METHOD(Gtk4_GListStore, find);
ZEND_METHOD(Gtk4_GListStore, insert);
ZEND_METHOD(Gtk4_GListStore, remove);
ZEND_METHOD(Gtk4_GListStore, remove_all);
ZEND_METHOD(Gtk4_GListStore, __construct);
ZEND_METHOD(Gtk4_GMenu, __construct);
ZEND_METHOD(Gtk4_GMenu, append);
ZEND_METHOD(Gtk4_GMenu, append_item);
ZEND_METHOD(Gtk4_GMenu, append_section);
ZEND_METHOD(Gtk4_GMenu, append_submenu);
ZEND_METHOD(Gtk4_GMenu, freeze);
ZEND_METHOD(Gtk4_GMenu, insert);
ZEND_METHOD(Gtk4_GMenu, insert_item);
ZEND_METHOD(Gtk4_GMenu, insert_section);
ZEND_METHOD(Gtk4_GMenu, insert_submenu);
ZEND_METHOD(Gtk4_GMenu, prepend);
ZEND_METHOD(Gtk4_GMenu, prepend_item);
ZEND_METHOD(Gtk4_GMenu, prepend_section);
ZEND_METHOD(Gtk4_GMenu, prepend_submenu);
ZEND_METHOD(Gtk4_GMenu, remove);
ZEND_METHOD(Gtk4_GMenu, remove_all);
ZEND_METHOD(Gtk4_GMenuItem, __construct);
ZEND_METHOD(Gtk4_GMenuItem, new_from_model);
ZEND_METHOD(Gtk4_GMenuItem, new_section);
ZEND_METHOD(Gtk4_GMenuItem, new_submenu);
ZEND_METHOD(Gtk4_GMenuItem, get_attribute_value);
ZEND_METHOD(Gtk4_GMenuItem, get_link);
ZEND_METHOD(Gtk4_GMenuItem, set_action_and_target_value);
ZEND_METHOD(Gtk4_GMenuItem, set_attribute_value);
ZEND_METHOD(Gtk4_GMenuItem, set_detailed_action);
ZEND_METHOD(Gtk4_GMenuItem, set_label);
ZEND_METHOD(Gtk4_GMenuItem, set_link);
ZEND_METHOD(Gtk4_GMenuItem, set_section);
ZEND_METHOD(Gtk4_GMenuItem, set_submenu);
ZEND_METHOD(Gtk4_GMenuModel, __construct);
ZEND_METHOD(Gtk4_GMenuModel, get_item_attribute_value);
ZEND_METHOD(Gtk4_GMenuModel, get_item_link);
ZEND_METHOD(Gtk4_GMenuModel, get_n_items);
ZEND_METHOD(Gtk4_GMenuModel, is_mutable);
ZEND_METHOD(Gtk4_GMenuModel, items_changed);
ZEND_METHOD(Gtk4_GMenuModel, vfunc_get_item_link);
ZEND_METHOD(Gtk4_GMenuModel, vfunc_get_n_items);
ZEND_METHOD(Gtk4_GMenuModel, vfunc_is_mutable);
ZEND_METHOD(Gtk4_GSimpleAction, __construct);
ZEND_METHOD(Gtk4_GSimpleAction, new_stateful);
ZEND_METHOD(Gtk4_GSimpleAction, set_enabled);
ZEND_METHOD(Gtk4_GSimpleAction, set_state_hint);
ZEND_METHOD(Gtk4_GSimpleAction, set_state);
ZEND_METHOD(Gtk4_GTask, __construct);
ZEND_METHOD(Gtk4_GTask, is_valid);
ZEND_METHOD(Gtk4_GTask, get_cancellable);
ZEND_METHOD(Gtk4_GTask, get_check_cancellable);
ZEND_METHOD(Gtk4_GTask, get_completed);
ZEND_METHOD(Gtk4_GTask, get_name);
ZEND_METHOD(Gtk4_GTask, get_priority);
ZEND_METHOD(Gtk4_GTask, get_return_on_cancel);
ZEND_METHOD(Gtk4_GTask, get_source_object);
ZEND_METHOD(Gtk4_GTask, had_error);
ZEND_METHOD(Gtk4_GTask, propagate_boolean);
ZEND_METHOD(Gtk4_GTask, propagate_int);
ZEND_METHOD(Gtk4_GTask, return_boolean);
ZEND_METHOD(Gtk4_GTask, return_error);
ZEND_METHOD(Gtk4_GTask, return_error_if_cancelled);
ZEND_METHOD(Gtk4_GTask, return_int);
ZEND_METHOD(Gtk4_GTask, set_check_cancellable);
ZEND_METHOD(Gtk4_GTask, set_name);
ZEND_METHOD(Gtk4_GTask, set_priority);
ZEND_METHOD(Gtk4_GTask, set_return_on_cancel);
ZEND_METHOD(Gtk4_GTask, set_static_name);

static const zend_function_entry class_Gtk4_GAction_methods[] = {
	ZEND_RAW_FENTRY("change_state", NULL, arginfo_class_Gtk4_GAction_change_state, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_enabled", NULL, arginfo_class_Gtk4_GAction_get_enabled, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_name", NULL, arginfo_class_Gtk4_GAction_get_name, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_parameter_type", NULL, arginfo_class_Gtk4_GAction_get_parameter_type, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_state", NULL, arginfo_class_Gtk4_GAction_get_state, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_state_hint", NULL, arginfo_class_Gtk4_GAction_get_state_hint, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_state_type", NULL, arginfo_class_Gtk4_GAction_get_state_type, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("activate", NULL, arginfo_class_Gtk4_GAction_activate, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GActionObject_methods[] = {
	ZEND_ME(Gtk4_GActionObject, __construct, arginfo_class_Gtk4_GActionObject___construct, ZEND_ACC_PRIVATE)
	ZEND_RAW_FENTRY("activate", zim_Gtk4_GAction_activate, arginfo_class_Gtk4_GActionObject_activate, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("change_state", zim_Gtk4_GAction_change_state, arginfo_class_Gtk4_GActionObject_change_state, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_enabled", zim_Gtk4_GAction_get_enabled, arginfo_class_Gtk4_GActionObject_get_enabled, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_name", zim_Gtk4_GAction_get_name, arginfo_class_Gtk4_GActionObject_get_name, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_parameter_type", zim_Gtk4_GAction_get_parameter_type, arginfo_class_Gtk4_GActionObject_get_parameter_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_state", zim_Gtk4_GAction_get_state, arginfo_class_Gtk4_GActionObject_get_state, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_state_hint", zim_Gtk4_GAction_get_state_hint, arginfo_class_Gtk4_GActionObject_get_state_hint, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_state_type", zim_Gtk4_GAction_get_state_type, arginfo_class_Gtk4_GActionObject_get_state_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GActionGroup_methods[] = {
	ZEND_RAW_FENTRY("action_added", NULL, arginfo_class_Gtk4_GActionGroup_action_added, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("action_enabled_changed", NULL, arginfo_class_Gtk4_GActionGroup_action_enabled_changed, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("action_removed", NULL, arginfo_class_Gtk4_GActionGroup_action_removed, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("action_state_changed", NULL, arginfo_class_Gtk4_GActionGroup_action_state_changed, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("change_action_state", NULL, arginfo_class_Gtk4_GActionGroup_change_action_state, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_enabled", NULL, arginfo_class_Gtk4_GActionGroup_get_action_enabled, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_parameter_type", NULL, arginfo_class_Gtk4_GActionGroup_get_action_parameter_type, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state", NULL, arginfo_class_Gtk4_GActionGroup_get_action_state, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state_hint", NULL, arginfo_class_Gtk4_GActionGroup_get_action_state_hint, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state_type", NULL, arginfo_class_Gtk4_GActionGroup_get_action_state_type, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("has_action", NULL, arginfo_class_Gtk4_GActionGroup_has_action, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("list_actions", NULL, arginfo_class_Gtk4_GActionGroup_list_actions, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("activate_action", NULL, arginfo_class_Gtk4_GActionGroup_activate_action, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GActionGroupObject_methods[] = {
	ZEND_ME(Gtk4_GActionGroupObject, __construct, arginfo_class_Gtk4_GActionGroupObject___construct, ZEND_ACC_PRIVATE)
	ZEND_RAW_FENTRY("action_added", zim_Gtk4_GActionGroup_action_added, arginfo_class_Gtk4_GActionGroupObject_action_added, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("action_enabled_changed", zim_Gtk4_GActionGroup_action_enabled_changed, arginfo_class_Gtk4_GActionGroupObject_action_enabled_changed, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("action_removed", zim_Gtk4_GActionGroup_action_removed, arginfo_class_Gtk4_GActionGroupObject_action_removed, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("action_state_changed", zim_Gtk4_GActionGroup_action_state_changed, arginfo_class_Gtk4_GActionGroupObject_action_state_changed, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("activate_action", zim_Gtk4_GActionGroup_activate_action, arginfo_class_Gtk4_GActionGroupObject_activate_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("change_action_state", zim_Gtk4_GActionGroup_change_action_state, arginfo_class_Gtk4_GActionGroupObject_change_action_state, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_enabled", zim_Gtk4_GActionGroup_get_action_enabled, arginfo_class_Gtk4_GActionGroupObject_get_action_enabled, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_parameter_type", zim_Gtk4_GActionGroup_get_action_parameter_type, arginfo_class_Gtk4_GActionGroupObject_get_action_parameter_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state", zim_Gtk4_GActionGroup_get_action_state, arginfo_class_Gtk4_GActionGroupObject_get_action_state, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state_hint", zim_Gtk4_GActionGroup_get_action_state_hint, arginfo_class_Gtk4_GActionGroupObject_get_action_state_hint, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state_type", zim_Gtk4_GActionGroup_get_action_state_type, arginfo_class_Gtk4_GActionGroupObject_get_action_state_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("has_action", zim_Gtk4_GActionGroup_has_action, arginfo_class_Gtk4_GActionGroupObject_has_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("list_actions", zim_Gtk4_GActionGroup_list_actions, arginfo_class_Gtk4_GActionGroupObject_list_actions, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GActionMap_methods[] = {
	ZEND_RAW_FENTRY("add_action", NULL, arginfo_class_Gtk4_GActionMap_add_action, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("lookup_action", NULL, arginfo_class_Gtk4_GActionMap_lookup_action, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("remove_action", NULL, arginfo_class_Gtk4_GActionMap_remove_action, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GActionMapObject_methods[] = {
	ZEND_ME(Gtk4_GActionMapObject, __construct, arginfo_class_Gtk4_GActionMapObject___construct, ZEND_ACC_PRIVATE)
	ZEND_RAW_FENTRY("add_action", zim_Gtk4_GActionMap_add_action, arginfo_class_Gtk4_GActionMapObject_add_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("lookup_action", zim_Gtk4_GActionMap_lookup_action, arginfo_class_Gtk4_GActionMapObject_lookup_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("remove_action", zim_Gtk4_GActionMap_remove_action, arginfo_class_Gtk4_GActionMapObject_remove_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GApplication_methods[] = {
	ZEND_ME(Gtk4_GApplication, __construct, arginfo_class_Gtk4_GApplication___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_default, arginfo_class_Gtk4_GApplication_get_default, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GApplication, id_is_valid, arginfo_class_Gtk4_GApplication_id_is_valid, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GApplication, activate, arginfo_class_Gtk4_GApplication_activate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, add_main_option, arginfo_class_Gtk4_GApplication_add_main_option, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, bind_busy_property, arginfo_class_Gtk4_GApplication_bind_busy_property, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_application_id, arginfo_class_Gtk4_GApplication_get_application_id, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_dbus_object_path, arginfo_class_Gtk4_GApplication_get_dbus_object_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_flags, arginfo_class_Gtk4_GApplication_get_flags, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_inactivity_timeout, arginfo_class_Gtk4_GApplication_get_inactivity_timeout, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_is_busy, arginfo_class_Gtk4_GApplication_get_is_busy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_is_registered, arginfo_class_Gtk4_GApplication_get_is_registered, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_is_remote, arginfo_class_Gtk4_GApplication_get_is_remote, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_resource_base_path, arginfo_class_Gtk4_GApplication_get_resource_base_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_version, arginfo_class_Gtk4_GApplication_get_version, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, hold, arginfo_class_Gtk4_GApplication_hold, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, mark_busy, arginfo_class_Gtk4_GApplication_mark_busy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, quit, arginfo_class_Gtk4_GApplication_quit, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, register, arginfo_class_Gtk4_GApplication_register, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, release, arginfo_class_Gtk4_GApplication_release, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, set_application_id, arginfo_class_Gtk4_GApplication_set_application_id, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, set_default, arginfo_class_Gtk4_GApplication_set_default, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, set_flags, arginfo_class_Gtk4_GApplication_set_flags, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, set_inactivity_timeout, arginfo_class_Gtk4_GApplication_set_inactivity_timeout, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, set_option_context_description, arginfo_class_Gtk4_GApplication_set_option_context_description, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, set_option_context_parameter_string, arginfo_class_Gtk4_GApplication_set_option_context_parameter_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, set_option_context_summary, arginfo_class_Gtk4_GApplication_set_option_context_summary, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, set_resource_base_path, arginfo_class_Gtk4_GApplication_set_resource_base_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, set_version, arginfo_class_Gtk4_GApplication_set_version, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, unbind_busy_property, arginfo_class_Gtk4_GApplication_unbind_busy_property, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, unmark_busy, arginfo_class_Gtk4_GApplication_unmark_busy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, withdraw_notification, arginfo_class_Gtk4_GApplication_withdraw_notification, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, run, arginfo_class_Gtk4_GApplication_run, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("action_added", zim_Gtk4_GActionGroup_action_added, arginfo_class_Gtk4_GApplication_action_added, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("action_enabled_changed", zim_Gtk4_GActionGroup_action_enabled_changed, arginfo_class_Gtk4_GApplication_action_enabled_changed, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("action_removed", zim_Gtk4_GActionGroup_action_removed, arginfo_class_Gtk4_GApplication_action_removed, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("action_state_changed", zim_Gtk4_GActionGroup_action_state_changed, arginfo_class_Gtk4_GApplication_action_state_changed, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("activate_action", zim_Gtk4_GActionGroup_activate_action, arginfo_class_Gtk4_GApplication_activate_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("change_action_state", zim_Gtk4_GActionGroup_change_action_state, arginfo_class_Gtk4_GApplication_change_action_state, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_enabled", zim_Gtk4_GActionGroup_get_action_enabled, arginfo_class_Gtk4_GApplication_get_action_enabled, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_parameter_type", zim_Gtk4_GActionGroup_get_action_parameter_type, arginfo_class_Gtk4_GApplication_get_action_parameter_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state", zim_Gtk4_GActionGroup_get_action_state, arginfo_class_Gtk4_GApplication_get_action_state, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state_hint", zim_Gtk4_GActionGroup_get_action_state_hint, arginfo_class_Gtk4_GApplication_get_action_state_hint, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state_type", zim_Gtk4_GActionGroup_get_action_state_type, arginfo_class_Gtk4_GApplication_get_action_state_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("has_action", zim_Gtk4_GActionGroup_has_action, arginfo_class_Gtk4_GApplication_has_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("list_actions", zim_Gtk4_GActionGroup_list_actions, arginfo_class_Gtk4_GApplication_list_actions, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("add_action", zim_Gtk4_GActionMap_add_action, arginfo_class_Gtk4_GApplication_add_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("lookup_action", zim_Gtk4_GActionMap_lookup_action, arginfo_class_Gtk4_GApplication_lookup_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("remove_action", zim_Gtk4_GActionMap_remove_action, arginfo_class_Gtk4_GApplication_remove_action, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_ME(Gtk4_GApplication, vfunc_activate, arginfo_class_Gtk4_GApplication_vfunc_activate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, vfunc_name_lost, arginfo_class_Gtk4_GApplication_vfunc_name_lost, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, vfunc_quit_mainloop, arginfo_class_Gtk4_GApplication_vfunc_quit_mainloop, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, vfunc_run_mainloop, arginfo_class_Gtk4_GApplication_vfunc_run_mainloop, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, vfunc_shutdown, arginfo_class_Gtk4_GApplication_vfunc_shutdown, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, vfunc_startup, arginfo_class_Gtk4_GApplication_vfunc_startup, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GAsyncResult_methods[] = {
	ZEND_RAW_FENTRY("get_source_object", NULL, arginfo_class_Gtk4_GAsyncResult_get_source_object, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GAsyncResultObject_methods[] = {
	ZEND_ME(Gtk4_GAsyncResultObject, __construct, arginfo_class_Gtk4_GAsyncResultObject___construct, ZEND_ACC_PRIVATE)
	ZEND_RAW_FENTRY("get_source_object", zim_Gtk4_GAsyncResult_get_source_object, arginfo_class_Gtk4_GAsyncResultObject_get_source_object, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("legacy_propagate_error", zim_Gtk4_GAsyncResult_legacy_propagate_error, arginfo_class_Gtk4_GAsyncResultObject_legacy_propagate_error, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GCancellable_methods[] = {
	ZEND_ME(Gtk4_GCancellable, __construct, arginfo_class_Gtk4_GCancellable___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GCancellable, get_current, arginfo_class_Gtk4_GCancellable_get_current, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GCancellable, cancel, arginfo_class_Gtk4_GCancellable_cancel, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GCancellable, disconnect, arginfo_class_Gtk4_GCancellable_disconnect, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GCancellable, get_fd, arginfo_class_Gtk4_GCancellable_get_fd, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GCancellable, is_cancelled, arginfo_class_Gtk4_GCancellable_is_cancelled, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GCancellable, pop_current, arginfo_class_Gtk4_GCancellable_pop_current, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GCancellable, push_current, arginfo_class_Gtk4_GCancellable_push_current, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GCancellable, release_fd, arginfo_class_Gtk4_GCancellable_release_fd, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GCancellable, reset, arginfo_class_Gtk4_GCancellable_reset, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GCancellable, set_error_if_cancelled, arginfo_class_Gtk4_GCancellable_set_error_if_cancelled, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GCancellable, vfunc_cancelled, arginfo_class_Gtk4_GCancellable_vfunc_cancelled, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GListModel_methods[] = {
	ZEND_RAW_FENTRY("get_item_type", NULL, arginfo_class_Gtk4_GListModel_get_item_type, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_n_items", NULL, arginfo_class_Gtk4_GListModel_get_n_items, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_item", NULL, arginfo_class_Gtk4_GListModel_get_item, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GListModelObject_methods[] = {
	ZEND_ME(Gtk4_GListModelObject, __construct, arginfo_class_Gtk4_GListModelObject___construct, ZEND_ACC_PRIVATE)
	ZEND_RAW_FENTRY("get_item_type", zim_Gtk4_GListModel_get_item_type, arginfo_class_Gtk4_GListModelObject_get_item_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_n_items", zim_Gtk4_GListModel_get_n_items, arginfo_class_Gtk4_GListModelObject_get_n_items, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_item", zim_Gtk4_GListModel_get_item, arginfo_class_Gtk4_GListModelObject_get_item, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("items_changed", zim_Gtk4_GListModel_items_changed, arginfo_class_Gtk4_GListModelObject_items_changed, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GListStore_methods[] = {
	ZEND_ME(Gtk4_GListStore, append, arginfo_class_Gtk4_GListStore_append, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GListStore, find, arginfo_class_Gtk4_GListStore_find, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GListStore, insert, arginfo_class_Gtk4_GListStore_insert, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GListStore, remove, arginfo_class_Gtk4_GListStore_remove, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GListStore, remove_all, arginfo_class_Gtk4_GListStore_remove_all, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GListStore, __construct, arginfo_class_Gtk4_GListStore___construct, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("get_item_type", zim_Gtk4_GListModel_get_item_type, arginfo_class_Gtk4_GListStore_get_item_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_n_items", zim_Gtk4_GListModel_get_n_items, arginfo_class_Gtk4_GListStore_get_n_items, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_item", zim_Gtk4_GListModel_get_item, arginfo_class_Gtk4_GListStore_get_item, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("items_changed", zim_Gtk4_GListModel_items_changed, arginfo_class_Gtk4_GListStore_items_changed, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GMenu_methods[] = {
	ZEND_ME(Gtk4_GMenu, __construct, arginfo_class_Gtk4_GMenu___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, append, arginfo_class_Gtk4_GMenu_append, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, append_item, arginfo_class_Gtk4_GMenu_append_item, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, append_section, arginfo_class_Gtk4_GMenu_append_section, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, append_submenu, arginfo_class_Gtk4_GMenu_append_submenu, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, freeze, arginfo_class_Gtk4_GMenu_freeze, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, insert, arginfo_class_Gtk4_GMenu_insert, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, insert_item, arginfo_class_Gtk4_GMenu_insert_item, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, insert_section, arginfo_class_Gtk4_GMenu_insert_section, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, insert_submenu, arginfo_class_Gtk4_GMenu_insert_submenu, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, prepend, arginfo_class_Gtk4_GMenu_prepend, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, prepend_item, arginfo_class_Gtk4_GMenu_prepend_item, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, prepend_section, arginfo_class_Gtk4_GMenu_prepend_section, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, prepend_submenu, arginfo_class_Gtk4_GMenu_prepend_submenu, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, remove, arginfo_class_Gtk4_GMenu_remove, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenu, remove_all, arginfo_class_Gtk4_GMenu_remove_all, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GMenuItem_methods[] = {
	ZEND_ME(Gtk4_GMenuItem, __construct, arginfo_class_Gtk4_GMenuItem___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuItem, new_from_model, arginfo_class_Gtk4_GMenuItem_new_from_model, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GMenuItem, new_section, arginfo_class_Gtk4_GMenuItem_new_section, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GMenuItem, new_submenu, arginfo_class_Gtk4_GMenuItem_new_submenu, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GMenuItem, get_attribute_value, arginfo_class_Gtk4_GMenuItem_get_attribute_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuItem, get_link, arginfo_class_Gtk4_GMenuItem_get_link, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuItem, set_action_and_target_value, arginfo_class_Gtk4_GMenuItem_set_action_and_target_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuItem, set_attribute_value, arginfo_class_Gtk4_GMenuItem_set_attribute_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuItem, set_detailed_action, arginfo_class_Gtk4_GMenuItem_set_detailed_action, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuItem, set_label, arginfo_class_Gtk4_GMenuItem_set_label, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuItem, set_link, arginfo_class_Gtk4_GMenuItem_set_link, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuItem, set_section, arginfo_class_Gtk4_GMenuItem_set_section, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuItem, set_submenu, arginfo_class_Gtk4_GMenuItem_set_submenu, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GMenuModel_methods[] = {
	ZEND_ME(Gtk4_GMenuModel, __construct, arginfo_class_Gtk4_GMenuModel___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GMenuModel, get_item_attribute_value, arginfo_class_Gtk4_GMenuModel_get_item_attribute_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, get_item_link, arginfo_class_Gtk4_GMenuModel_get_item_link, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, get_n_items, arginfo_class_Gtk4_GMenuModel_get_n_items, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, is_mutable, arginfo_class_Gtk4_GMenuModel_is_mutable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, items_changed, arginfo_class_Gtk4_GMenuModel_items_changed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, vfunc_get_item_link, arginfo_class_Gtk4_GMenuModel_vfunc_get_item_link, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, vfunc_get_n_items, arginfo_class_Gtk4_GMenuModel_vfunc_get_n_items, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, vfunc_is_mutable, arginfo_class_Gtk4_GMenuModel_vfunc_is_mutable, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GSimpleAction_methods[] = {
	ZEND_ME(Gtk4_GSimpleAction, __construct, arginfo_class_Gtk4_GSimpleAction___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GSimpleAction, new_stateful, arginfo_class_Gtk4_GSimpleAction_new_stateful, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GSimpleAction, set_enabled, arginfo_class_Gtk4_GSimpleAction_set_enabled, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GSimpleAction, set_state_hint, arginfo_class_Gtk4_GSimpleAction_set_state_hint, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GSimpleAction, set_state, arginfo_class_Gtk4_GSimpleAction_set_state, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("activate", zim_Gtk4_GAction_activate, arginfo_class_Gtk4_GSimpleAction_activate, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("change_state", zim_Gtk4_GAction_change_state, arginfo_class_Gtk4_GSimpleAction_change_state, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_enabled", zim_Gtk4_GAction_get_enabled, arginfo_class_Gtk4_GSimpleAction_get_enabled, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_name", zim_Gtk4_GAction_get_name, arginfo_class_Gtk4_GSimpleAction_get_name, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_parameter_type", zim_Gtk4_GAction_get_parameter_type, arginfo_class_Gtk4_GSimpleAction_get_parameter_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_state", zim_Gtk4_GAction_get_state, arginfo_class_Gtk4_GSimpleAction_get_state, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_state_hint", zim_Gtk4_GAction_get_state_hint, arginfo_class_Gtk4_GSimpleAction_get_state_hint, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_state_type", zim_Gtk4_GAction_get_state_type, arginfo_class_Gtk4_GSimpleAction_get_state_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GTask_methods[] = {
	ZEND_ME(Gtk4_GTask, __construct, arginfo_class_Gtk4_GTask___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, is_valid, arginfo_class_Gtk4_GTask_is_valid, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GTask, get_cancellable, arginfo_class_Gtk4_GTask_get_cancellable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, get_check_cancellable, arginfo_class_Gtk4_GTask_get_check_cancellable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, get_completed, arginfo_class_Gtk4_GTask_get_completed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, get_name, arginfo_class_Gtk4_GTask_get_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, get_priority, arginfo_class_Gtk4_GTask_get_priority, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, get_return_on_cancel, arginfo_class_Gtk4_GTask_get_return_on_cancel, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, get_source_object, arginfo_class_Gtk4_GTask_get_source_object, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, had_error, arginfo_class_Gtk4_GTask_had_error, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, propagate_boolean, arginfo_class_Gtk4_GTask_propagate_boolean, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, propagate_int, arginfo_class_Gtk4_GTask_propagate_int, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, return_boolean, arginfo_class_Gtk4_GTask_return_boolean, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, return_error, arginfo_class_Gtk4_GTask_return_error, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, return_error_if_cancelled, arginfo_class_Gtk4_GTask_return_error_if_cancelled, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, return_int, arginfo_class_Gtk4_GTask_return_int, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, set_check_cancellable, arginfo_class_Gtk4_GTask_set_check_cancellable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, set_name, arginfo_class_Gtk4_GTask_set_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, set_priority, arginfo_class_Gtk4_GTask_set_priority, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, set_return_on_cancel, arginfo_class_Gtk4_GTask_set_return_on_cancel, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, set_static_name, arginfo_class_Gtk4_GTask_set_static_name, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("legacy_propagate_error", zim_Gtk4_GAsyncResult_legacy_propagate_error, arginfo_class_Gtk4_GTask_legacy_propagate_error, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static zend_class_entry *register_class_Gtk4_GAction(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GAction", class_Gtk4_GAction_methods);
	class_entry = zend_register_internal_interface(&ce);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GActionObject(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GAction)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GActionObject", class_Gtk4_GActionObject_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GAction);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GActionGroup(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GActionGroup", class_Gtk4_GActionGroup_methods);
	class_entry = zend_register_internal_interface(&ce);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GActionGroupObject(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GActionGroup)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GActionGroupObject", class_Gtk4_GActionGroupObject_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GActionGroup);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GActionMap(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GActionMap", class_Gtk4_GActionMap_methods);
	class_entry = zend_register_internal_interface(&ce);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GActionMapObject(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GActionMap)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GActionMapObject", class_Gtk4_GActionMapObject_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GActionMap);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GApplication(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GActionGroup, zend_class_entry *class_entry_Gtk4_GActionMap)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GApplication", class_Gtk4_GApplication_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);
	zend_class_implements(class_entry, 2, class_entry_Gtk4_GActionGroup, class_entry_Gtk4_GActionMap);

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

static zend_class_entry *register_class_Gtk4_GAsyncResult(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GAsyncResult", class_Gtk4_GAsyncResult_methods);
	class_entry = zend_register_internal_interface(&ce);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GAsyncResultObject(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GAsyncResult)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GAsyncResultObject", class_Gtk4_GAsyncResultObject_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GAsyncResult);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GCancellable(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GCancellable", class_Gtk4_GCancellable_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GListModel(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GListModel", class_Gtk4_GListModel_methods);
	class_entry = zend_register_internal_interface(&ce);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GListModelObject(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GListModel)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GListModelObject", class_Gtk4_GListModelObject_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GListModel);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GListStore(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GListModel)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GListStore", class_Gtk4_GListStore_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GListModel);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GMenu(zend_class_entry *class_entry_Gtk4_GMenuModel)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GMenu", class_Gtk4_GMenu_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GMenuModel, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GMenuItem(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GMenuItem", class_Gtk4_GMenuItem_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GMenuModel(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GMenuModel", class_Gtk4_GMenuModel_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GSimpleAction(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GAction)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GSimpleAction", class_Gtk4_GSimpleAction_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GAction);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GTask(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GAsyncResult)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GTask", class_Gtk4_GTask_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GAsyncResult);

	return class_entry;
}
