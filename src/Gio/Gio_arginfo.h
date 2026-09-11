/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 62a92d3f2936be485c4a5d936e0958cb0cfe2b8b */

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

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GActionGroup_activate_action, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, action_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameter, IS_MIXED, 0, "null")
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

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_get_flags, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GApplication_get_inactivity_timeout arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GApplication_get_is_busy arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GApplication_get_is_registered arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GApplication_get_resource_base_path arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GApplication_get_version arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GApplication_hold arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GApplication_mark_busy arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GApplication_quit arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_register, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, cancellable, Gtk4\\GCancellable, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GApplication_release arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_send_notification, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, id, IS_STRING, 1)
	ZEND_ARG_OBJ_INFO(0, notification, Gtk4\\GNotification, 0)
ZEND_END_ARG_INFO()

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

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GApplication_get_dbus_connection, 0, 0, Gtk4\\GDBusConnection, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GApplication_get_dbus_object_path arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GApplication_get_is_remote arginfo_class_Gtk4_GAction_get_enabled

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

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_vfunc_after_emit, 0, 0, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, platform_data, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GApplication_vfunc_before_emit arginfo_class_Gtk4_GApplication_vfunc_after_emit

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GApplication_vfunc_dbus_unregister, 0, 2, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, connection, Gtk4\\GDBusConnection, 0)
	ZEND_ARG_TYPE_INFO(0, object_path, IS_STRING, 0)
ZEND_END_ARG_INFO()

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

#define arginfo_class_Gtk4_GCancellable_reset arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GCancellable_set_error_if_cancelled arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GCancellable_vfunc_cancelled arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GDBusArgInfo___construct arginfo_class_Gtk4_GActionObject___construct

#define arginfo_class_Gtk4_GDBusConnection___construct arginfo_class_Gtk4_GActionObject___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDBusConnection_new_finish, 0, 1, Gtk4\\GDBusConnection, 0)
	ZEND_ARG_OBJ_INFO(0, res, Gtk4\\GAsyncResult, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusConnection_new_for_address_finish arginfo_class_Gtk4_GDBusConnection_new_finish

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_call_finish, 0, 1, IS_MIXED, 0)
	ZEND_ARG_OBJ_INFO(0, res, Gtk4\\GAsyncResult, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_close, 0, 2, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, cancellable, Gtk4\\GCancellable, 1)
	ZEND_ARG_TYPE_INFO(0, callback, IS_CALLABLE, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_close_finish, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, res, Gtk4\\GAsyncResult, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusConnection_close_sync arginfo_class_Gtk4_GApplication_register

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_export_action_group, 0, 2, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, object_path, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO(0, action_group, Gtk4\\GActionGroup, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_export_menu_model, 0, 2, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, object_path, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO(0, menu, Gtk4\\GMenuModel, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusConnection_flush arginfo_class_Gtk4_GDBusConnection_close

#define arginfo_class_Gtk4_GDBusConnection_flush_finish arginfo_class_Gtk4_GDBusConnection_close_finish

#define arginfo_class_Gtk4_GDBusConnection_flush_sync arginfo_class_Gtk4_GApplication_register

#define arginfo_class_Gtk4_GDBusConnection_get_capabilities arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GDBusConnection_get_exit_on_close arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GDBusConnection_get_flags arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GDBusConnection_get_guid arginfo_class_Gtk4_GAction_get_name

#define arginfo_class_Gtk4_GDBusConnection_get_last_serial arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GDBusConnection_get_unique_name arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GDBusConnection_is_closed arginfo_class_Gtk4_GAction_get_enabled

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_remove_filter, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, filter_id, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_set_exit_on_close, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, exit_on_close, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_signal_unsubscribe, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, subscription_id, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusConnection_start_message_processing arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_unexport_action_group, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, export_id, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusConnection_unexport_menu_model arginfo_class_Gtk4_GDBusConnection_unexport_action_group

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_unregister_object, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, registration_id, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusConnection_unregister_subtree arginfo_class_Gtk4_GDBusConnection_unregister_object

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDBusConnection_bus_get_sync, 0, 1, Gtk4\\GDBusConnection, 0)
	ZEND_ARG_OBJ_INFO(0, bus_type, Gtk4\\GBusType, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, cancellable, Gtk4\\GCancellable, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_call, 0, 4, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, bus_name, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, object_path, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, interface_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, method_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameters, IS_ARRAY, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, reply_type, IS_STRING, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, flags, IS_LONG, 0, "0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, timeout_msec, IS_LONG, 0, "-1")
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, cancellable, Gtk4\\GCancellable, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, callback, IS_CALLABLE, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, signature, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_call_sync, 0, 4, IS_ARRAY, 0)
	ZEND_ARG_TYPE_INFO(0, bus_name, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, object_path, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, interface_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, method_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameters, IS_ARRAY, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, reply_type, IS_STRING, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, flags, IS_LONG, 0, "0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, timeout_msec, IS_LONG, 0, "-1")
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, cancellable, Gtk4\\GCancellable, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, signature, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_emit_signal, 0, 4, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, destination_bus_name, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, object_path, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, interface_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, signal_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameters, IS_ARRAY, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, signature, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_register_object, 0, 3, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, object_path, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO(0, interface_info, Gtk4\\GDBusInterfaceInfo, 0)
	ZEND_ARG_TYPE_INFO(0, method_call, IS_CALLABLE, 1)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, get_property, IS_CALLABLE, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, set_property, IS_CALLABLE, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusConnection_signal_subscribe, 0, 7, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, sender, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, interface_name, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, member, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, object_path, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, arg0, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, callback, IS_CALLABLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusInterfaceInfo___construct arginfo_class_Gtk4_GActionObject___construct

#define arginfo_class_Gtk4_GDBusInterfaceInfo_cache_build arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GDBusInterfaceInfo_cache_release arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDBusInterfaceInfo_lookup_method, 0, 1, Gtk4\\GDBusMethodInfo, 1)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDBusInterfaceInfo_lookup_property, 0, 1, Gtk4\\GDBusPropertyInfo, 1)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDBusInterfaceInfo_lookup_signal, 0, 1, Gtk4\\GDBusSignalInfo, 1)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusMethodInfo___construct arginfo_class_Gtk4_GActionObject___construct

#define arginfo_class_Gtk4_GDBusMethodInvocation___construct arginfo_class_Gtk4_GActionObject___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDBusMethodInvocation_get_connection, 0, 0, Gtk4\\GDBusConnection, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusMethodInvocation_get_interface_name arginfo_class_Gtk4_GAction_get_name

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDBusMethodInvocation_get_method_info, 0, 0, Gtk4\\GDBusMethodInfo, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusMethodInvocation_get_method_name arginfo_class_Gtk4_GAction_get_name

#define arginfo_class_Gtk4_GDBusMethodInvocation_get_object_path arginfo_class_Gtk4_GAction_get_name

#define arginfo_class_Gtk4_GDBusMethodInvocation_get_parameters arginfo_class_Gtk4_GAction_get_state

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDBusMethodInvocation_get_property_info, 0, 0, Gtk4\\GDBusPropertyInfo, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusMethodInvocation_get_sender arginfo_class_Gtk4_GAction_get_name

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusMethodInvocation_return_dbus_error, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, error_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, error_message, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusMethodInvocation_return_gerror, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, error, Gtk4\\GError, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusMethodInvocation_return_value, 0, 0, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameters, IS_ARRAY, 1, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusNodeInfo___construct arginfo_class_Gtk4_GActionObject___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDBusNodeInfo_new_for_xml, 0, 1, Gtk4\\GDBusNodeInfo, 0)
	ZEND_ARG_TYPE_INFO(0, xml_data, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDBusNodeInfo_lookup_interface, 0, 1, Gtk4\\GDBusInterfaceInfo, 1)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusPropertyInfo___construct arginfo_class_Gtk4_GActionObject___construct

#define arginfo_class_Gtk4_GDBusProxy___construct arginfo_class_Gtk4_GActionObject___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDBusProxy_new_finish, 0, 1, Gtk4\\GDBusProxy, 0)
	ZEND_ARG_OBJ_INFO(0, res, Gtk4\\GAsyncResult, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusProxy_new_for_bus_finish arginfo_class_Gtk4_GDBusProxy_new_finish

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDBusProxy_new_for_bus_sync, 0, 6, Gtk4\\GDBusProxy, 0)
	ZEND_ARG_OBJ_INFO(0, bus_type, Gtk4\\GBusType, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, info, Gtk4\\GDBusInterfaceInfo, 1)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, object_path, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, interface_name, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, cancellable, Gtk4\\GCancellable, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDBusProxy_new_sync, 0, 6, Gtk4\\GDBusProxy, 0)
	ZEND_ARG_OBJ_INFO(0, connection, Gtk4\\GDBusConnection, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, info, Gtk4\\GDBusInterfaceInfo, 1)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, object_path, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, interface_name, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, cancellable, Gtk4\\GCancellable, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusProxy_new, 0, 6, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, connection, Gtk4\\GDBusConnection, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, info, Gtk4\\GDBusInterfaceInfo, 1)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, object_path, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, interface_name, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, cancellable, Gtk4\\GCancellable, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, callback, IS_CALLABLE, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusProxy_new_for_bus, 0, 6, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, bus_type, Gtk4\\GBusType, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, info, Gtk4\\GDBusInterfaceInfo, 1)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, object_path, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, interface_name, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, cancellable, Gtk4\\GCancellable, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, callback, IS_CALLABLE, 1, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusProxy_call_finish arginfo_class_Gtk4_GDBusConnection_call_finish

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusProxy_get_cached_property, 0, 1, IS_MIXED, 0)
	ZEND_ARG_TYPE_INFO(0, property_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusProxy_get_cached_property_names arginfo_class_Gtk4_GActionGroup_list_actions

#define arginfo_class_Gtk4_GDBusProxy_get_connection arginfo_class_Gtk4_GDBusMethodInvocation_get_connection

#define arginfo_class_Gtk4_GDBusProxy_get_default_timeout arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GDBusProxy_get_flags arginfo_class_Gtk4_GApplication_get_flags

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDBusProxy_get_interface_info, 0, 0, Gtk4\\GDBusInterfaceInfo, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusProxy_get_interface_name arginfo_class_Gtk4_GAction_get_name

#define arginfo_class_Gtk4_GDBusProxy_get_name arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GDBusProxy_get_name_owner arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GDBusProxy_get_object_path arginfo_class_Gtk4_GAction_get_name

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusProxy_set_cached_property, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, property_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, value, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusProxy_set_default_timeout, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, timeout_msec, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusProxy_set_interface_info, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, info, Gtk4\\GDBusInterfaceInfo, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusProxy_call, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, method_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameters, IS_ARRAY, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, flags, IS_LONG, 0, "0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, timeout_msec, IS_LONG, 0, "-1")
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, cancellable, Gtk4\\GCancellable, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, callback, IS_CALLABLE, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusProxy_call_sync, 0, 1, IS_ARRAY, 0)
	ZEND_ARG_TYPE_INFO(0, method_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameters, IS_ARRAY, 1, "null")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, flags, IS_LONG, 0, "0")
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, timeout_msec, IS_LONG, 0, "-1")
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, cancellable, Gtk4\\GCancellable, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDBusProxy_vfunc_g_signal, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, sender_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, signal_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameters, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDBusSignalInfo___construct arginfo_class_Gtk4_GActionObject___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GIcon_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, icon2, Gtk4\\GIcon, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GIcon_hash arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GIcon_serialize arginfo_class_Gtk4_GAction_get_state

#define arginfo_class_Gtk4_GIconObject___construct arginfo_class_Gtk4_GActionObject___construct

#define arginfo_class_Gtk4_GIconObject_equal arginfo_class_Gtk4_GIcon_equal

#define arginfo_class_Gtk4_GIconObject_hash arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GIconObject_serialize arginfo_class_Gtk4_GAction_get_state

#define arginfo_class_Gtk4_GIconObject_to_string arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GInputStream___construct arginfo_class_Gtk4_GActionObject___construct

#define arginfo_class_Gtk4_GInputStream_clear_pending arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GInputStream_close arginfo_class_Gtk4_GApplication_register

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GInputStream_close_async, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, io_priority, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, cancellable, Gtk4\\GCancellable, 1)
	ZEND_ARG_TYPE_INFO(0, callback, IS_CALLABLE, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GInputStream_close_finish, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, result, Gtk4\\GAsyncResult, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GInputStream_has_pending arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GInputStream_is_closed arginfo_class_Gtk4_GAction_get_enabled

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GInputStream_read_all_finish, 0, 1, IS_LONG, 1)
	ZEND_ARG_OBJ_INFO(0, result, Gtk4\\GAsyncResult, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GInputStream_read_bytes, 0, 2, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, count, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, cancellable, Gtk4\\GCancellable, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GInputStream_read_bytes_async, 0, 4, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, count, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, io_priority, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, cancellable, Gtk4\\GCancellable, 1)
	ZEND_ARG_TYPE_INFO(0, callback, IS_CALLABLE, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GInputStream_read_bytes_finish, 0, 1, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO(0, result, Gtk4\\GAsyncResult, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GInputStream_read_finish, 0, 1, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, result, Gtk4\\GAsyncResult, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GInputStream_set_pending arginfo_class_Gtk4_GAction_get_enabled

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GInputStream_skip, 0, 2, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, count, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, cancellable, Gtk4\\GCancellable, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GInputStream_skip_async arginfo_class_Gtk4_GInputStream_read_bytes_async

#define arginfo_class_Gtk4_GInputStream_skip_finish arginfo_class_Gtk4_GInputStream_read_finish

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

#define arginfo_class_Gtk4_GMemoryInputStream___construct arginfo_class_Gtk4_GActionObject___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GMemoryInputStream_new_from_bytes, 0, 1, Gtk4\\GMemoryInputStream, 0)
	ZEND_ARG_TYPE_INFO(0, bytes, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMemoryInputStream_add_bytes, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, bytes, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GMemoryOutputStream___construct arginfo_class_Gtk4_GActionObject___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GMemoryOutputStream_new_resizable, 0, 0, Gtk4\\GMemoryOutputStream, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GMemoryOutputStream_get_data_size arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GMemoryOutputStream_get_size arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GMemoryOutputStream_steal_as_bytes arginfo_class_Gtk4_GAction_get_name

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

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenuItem_set_icon, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, icon, Gtk4\\GIcon, 0)
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

#define arginfo_class_Gtk4_GMenuModel_get_n_items arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GMenuModel_is_mutable arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GMenuModel_items_changed arginfo_class_Gtk4_GListModelObject_items_changed

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GMenuModel_get_item_attribute_value, 0, 3, IS_MIXED, 0)
	ZEND_ARG_TYPE_INFO(0, item_index, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, attribute, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, expected_type, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GMenuModel_get_item_link, 0, 2, Gtk4\\GMenuModel, 1)
	ZEND_ARG_TYPE_INFO(0, item_index, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, link, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GMenuModel_vfunc_get_item_attribute_value arginfo_class_Gtk4_GMenuModel_get_item_attribute_value

#define arginfo_class_Gtk4_GMenuModel_vfunc_get_item_link arginfo_class_Gtk4_GMenuModel_get_item_link

#define arginfo_class_Gtk4_GMenuModel_vfunc_get_n_items arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GMenuModel_vfunc_is_mutable arginfo_class_Gtk4_GAction_get_enabled

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GNotification___construct, 0, 0, 1)
	ZEND_ARG_TYPE_INFO(0, title, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GNotification_add_button, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, label, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, detailed_action, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GNotification_add_button_with_target, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, label, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, action, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, target, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GNotification_set_body, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, body, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GNotification_set_category, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, category, IS_STRING, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GNotification_set_default_action arginfo_class_Gtk4_GMenuItem_set_detailed_action

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GNotification_set_default_action_and_target, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, action, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, target, IS_MIXED, 0, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GNotification_set_icon arginfo_class_Gtk4_GMenuItem_set_icon

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GNotification_set_priority, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, priority, Gtk4\\GNotificationPriority, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GNotification_set_title, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, title, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GOutputStream___construct arginfo_class_Gtk4_GActionObject___construct

#define arginfo_class_Gtk4_GOutputStream_clear_pending arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GOutputStream_close arginfo_class_Gtk4_GApplication_register

#define arginfo_class_Gtk4_GOutputStream_close_async arginfo_class_Gtk4_GInputStream_close_async

#define arginfo_class_Gtk4_GOutputStream_close_finish arginfo_class_Gtk4_GInputStream_close_finish

#define arginfo_class_Gtk4_GOutputStream_flush arginfo_class_Gtk4_GApplication_register

#define arginfo_class_Gtk4_GOutputStream_flush_async arginfo_class_Gtk4_GInputStream_close_async

#define arginfo_class_Gtk4_GOutputStream_flush_finish arginfo_class_Gtk4_GInputStream_close_finish

#define arginfo_class_Gtk4_GOutputStream_has_pending arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GOutputStream_is_closed arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GOutputStream_is_closing arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GOutputStream_set_pending arginfo_class_Gtk4_GAction_get_enabled

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GOutputStream_splice, 0, 3, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, source, Gtk4\\GInputStream, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, cancellable, Gtk4\\GCancellable, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GOutputStream_splice_async, 0, 5, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, source, Gtk4\\GInputStream, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, io_priority, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, cancellable, Gtk4\\GCancellable, 1)
	ZEND_ARG_TYPE_INFO(0, callback, IS_CALLABLE, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GOutputStream_splice_finish arginfo_class_Gtk4_GInputStream_read_finish

#define arginfo_class_Gtk4_GOutputStream_write_all_finish arginfo_class_Gtk4_GInputStream_read_all_finish

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GOutputStream_write_bytes, 0, 2, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, bytes, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO(0, cancellable, Gtk4\\GCancellable, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GOutputStream_write_bytes_async, 0, 4, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, bytes, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, io_priority, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, cancellable, Gtk4\\GCancellable, 1)
	ZEND_ARG_TYPE_INFO(0, callback, IS_CALLABLE, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GOutputStream_write_bytes_finish arginfo_class_Gtk4_GInputStream_read_finish

#define arginfo_class_Gtk4_GOutputStream_write_finish arginfo_class_Gtk4_GInputStream_read_finish

#define arginfo_class_Gtk4_GOutputStream_writev_all_finish arginfo_class_Gtk4_GInputStream_read_all_finish

#define arginfo_class_Gtk4_GOutputStream_writev_finish arginfo_class_Gtk4_GInputStream_read_all_finish

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

#define arginfo_class_Gtk4_GTask_return_error_if_cancelled arginfo_class_Gtk4_GAction_get_enabled

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

#define arginfo_class_Gtk4_GTask_propagate_boolean arginfo_class_Gtk4_GAction_get_enabled

#define arginfo_class_Gtk4_GTask_propagate_int arginfo_class_Gtk4_GApplication_get_flags

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTask_return_boolean, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, result, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GTask_return_error arginfo_class_Gtk4_GDBusMethodInvocation_return_gerror

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTask_return_int, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, result, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GTask_legacy_propagate_error arginfo_class_Gtk4_GAction_get_enabled

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GTestDBus___construct, 0, 0, 1)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GTestDBus_unset arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTestDBus_add_service_dir, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, path, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GTestDBus_down arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GTestDBus_get_bus_address arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GTestDBus_get_flags arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GTestDBus_stop arginfo_class_Gtk4_GApplication_activate

#define arginfo_class_Gtk4_GTestDBus_up arginfo_class_Gtk4_GApplication_activate

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GThemedIcon___construct, 0, 0, 1)
	ZEND_ARG_TYPE_INFO(0, iconname, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GThemedIcon_new_from_names, 0, 1, Gtk4\\GThemedIcon, 0)
	ZEND_ARG_TYPE_INFO(0, iconnames, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GThemedIcon_new_with_default_fallbacks, 0, 1, Gtk4\\GThemedIcon, 0)
	ZEND_ARG_TYPE_INFO(0, iconname, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GThemedIcon_append_name, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, iconname, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GThemedIcon_get_names arginfo_class_Gtk4_GActionGroup_list_actions

#define arginfo_class_Gtk4_GThemedIcon_prepend_name arginfo_class_Gtk4_GThemedIcon_append_name

#define arginfo_class_Gtk4_GThemedIcon_equal arginfo_class_Gtk4_GIcon_equal

#define arginfo_class_Gtk4_GThemedIcon_hash arginfo_class_Gtk4_GApplication_get_flags

#define arginfo_class_Gtk4_GThemedIcon_serialize arginfo_class_Gtk4_GAction_get_state

#define arginfo_class_Gtk4_GThemedIcon_to_string arginfo_class_Gtk4_GAction_get_parameter_type

#define arginfo_class_Gtk4_GTlsCertificate___construct arginfo_class_Gtk4_GActionObject___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GTlsCertificate_new_from_file, 0, 1, Gtk4\\GTlsCertificate, 0)
	ZEND_ARG_TYPE_INFO(0, file, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GTlsCertificate_new_from_file_with_password, 0, 2, Gtk4\\GTlsCertificate, 0)
	ZEND_ARG_TYPE_INFO(0, file, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, password, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GTlsCertificate_new_from_files, 0, 2, Gtk4\\GTlsCertificate, 0)
	ZEND_ARG_TYPE_INFO(0, cert_file, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, key_file, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GTlsCertificate_new_from_pem, 0, 2, Gtk4\\GTlsCertificate, 0)
	ZEND_ARG_TYPE_INFO(0, data, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, length, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GTlsCertificate_new_from_pkcs11_uris, 0, 1, Gtk4\\GTlsCertificate, 0)
	ZEND_ARG_TYPE_INFO(0, pkcs11_uri, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, private_key_pkcs11_uri, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTlsCertificate_list_new_from_file, 0, 1, IS_ARRAY, 0)
	ZEND_ARG_TYPE_INFO(0, file, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GTlsCertificate_get_dns_names arginfo_class_Gtk4_GActionGroup_list_actions

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GTlsCertificate_get_issuer, 0, 0, Gtk4\\GTlsCertificate, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GTlsCertificate_get_issuer_name arginfo_class_Gtk4_GAction_get_parameter_type

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GTlsCertificate_get_not_valid_after, 0, 0, Gtk4\\GDateTime, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GTlsCertificate_get_not_valid_before arginfo_class_Gtk4_GTlsCertificate_get_not_valid_after

#define arginfo_class_Gtk4_GTlsCertificate_get_subject_name arginfo_class_Gtk4_GAction_get_parameter_type

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTlsCertificate_is_same, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, cert_two, Gtk4\\GTlsCertificate, 0)
ZEND_END_ARG_INFO()

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
ZEND_METHOD(Gtk4_GApplication, get_flags);
ZEND_METHOD(Gtk4_GApplication, get_inactivity_timeout);
ZEND_METHOD(Gtk4_GApplication, get_is_busy);
ZEND_METHOD(Gtk4_GApplication, get_is_registered);
ZEND_METHOD(Gtk4_GApplication, get_resource_base_path);
ZEND_METHOD(Gtk4_GApplication, get_version);
ZEND_METHOD(Gtk4_GApplication, hold);
ZEND_METHOD(Gtk4_GApplication, mark_busy);
ZEND_METHOD(Gtk4_GApplication, quit);
ZEND_METHOD(Gtk4_GApplication, register);
ZEND_METHOD(Gtk4_GApplication, release);
ZEND_METHOD(Gtk4_GApplication, send_notification);
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
ZEND_METHOD(Gtk4_GApplication, get_dbus_connection);
ZEND_METHOD(Gtk4_GApplication, get_dbus_object_path);
ZEND_METHOD(Gtk4_GApplication, get_is_remote);
ZEND_METHOD(Gtk4_GApplication, run);
ZEND_METHOD(Gtk4_GApplication, vfunc_activate);
ZEND_METHOD(Gtk4_GApplication, vfunc_after_emit);
ZEND_METHOD(Gtk4_GApplication, vfunc_before_emit);
ZEND_METHOD(Gtk4_GApplication, vfunc_dbus_unregister);
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
ZEND_METHOD(Gtk4_GCancellable, reset);
ZEND_METHOD(Gtk4_GCancellable, set_error_if_cancelled);
ZEND_METHOD(Gtk4_GCancellable, vfunc_cancelled);
ZEND_METHOD(Gtk4_GDBusArgInfo, __construct);
ZEND_METHOD(Gtk4_GDBusConnection, __construct);
ZEND_METHOD(Gtk4_GDBusConnection, new_finish);
ZEND_METHOD(Gtk4_GDBusConnection, new_for_address_finish);
ZEND_METHOD(Gtk4_GDBusConnection, call_finish);
ZEND_METHOD(Gtk4_GDBusConnection, close);
ZEND_METHOD(Gtk4_GDBusConnection, close_finish);
ZEND_METHOD(Gtk4_GDBusConnection, close_sync);
ZEND_METHOD(Gtk4_GDBusConnection, export_action_group);
ZEND_METHOD(Gtk4_GDBusConnection, export_menu_model);
ZEND_METHOD(Gtk4_GDBusConnection, flush);
ZEND_METHOD(Gtk4_GDBusConnection, flush_finish);
ZEND_METHOD(Gtk4_GDBusConnection, flush_sync);
ZEND_METHOD(Gtk4_GDBusConnection, get_capabilities);
ZEND_METHOD(Gtk4_GDBusConnection, get_exit_on_close);
ZEND_METHOD(Gtk4_GDBusConnection, get_flags);
ZEND_METHOD(Gtk4_GDBusConnection, get_guid);
ZEND_METHOD(Gtk4_GDBusConnection, get_last_serial);
ZEND_METHOD(Gtk4_GDBusConnection, get_unique_name);
ZEND_METHOD(Gtk4_GDBusConnection, is_closed);
ZEND_METHOD(Gtk4_GDBusConnection, remove_filter);
ZEND_METHOD(Gtk4_GDBusConnection, set_exit_on_close);
ZEND_METHOD(Gtk4_GDBusConnection, signal_unsubscribe);
ZEND_METHOD(Gtk4_GDBusConnection, start_message_processing);
ZEND_METHOD(Gtk4_GDBusConnection, unexport_action_group);
ZEND_METHOD(Gtk4_GDBusConnection, unexport_menu_model);
ZEND_METHOD(Gtk4_GDBusConnection, unregister_object);
ZEND_METHOD(Gtk4_GDBusConnection, unregister_subtree);
ZEND_METHOD(Gtk4_GDBusConnection, bus_get_sync);
ZEND_METHOD(Gtk4_GDBusConnection, call);
ZEND_METHOD(Gtk4_GDBusConnection, call_sync);
ZEND_METHOD(Gtk4_GDBusConnection, emit_signal);
ZEND_METHOD(Gtk4_GDBusConnection, register_object);
ZEND_METHOD(Gtk4_GDBusConnection, signal_subscribe);
ZEND_METHOD(Gtk4_GDBusInterfaceInfo, __construct);
ZEND_METHOD(Gtk4_GDBusInterfaceInfo, cache_build);
ZEND_METHOD(Gtk4_GDBusInterfaceInfo, cache_release);
ZEND_METHOD(Gtk4_GDBusInterfaceInfo, lookup_method);
ZEND_METHOD(Gtk4_GDBusInterfaceInfo, lookup_property);
ZEND_METHOD(Gtk4_GDBusInterfaceInfo, lookup_signal);
ZEND_METHOD(Gtk4_GDBusMethodInfo, __construct);
ZEND_METHOD(Gtk4_GDBusMethodInvocation, __construct);
ZEND_METHOD(Gtk4_GDBusMethodInvocation, get_connection);
ZEND_METHOD(Gtk4_GDBusMethodInvocation, get_interface_name);
ZEND_METHOD(Gtk4_GDBusMethodInvocation, get_method_info);
ZEND_METHOD(Gtk4_GDBusMethodInvocation, get_method_name);
ZEND_METHOD(Gtk4_GDBusMethodInvocation, get_object_path);
ZEND_METHOD(Gtk4_GDBusMethodInvocation, get_parameters);
ZEND_METHOD(Gtk4_GDBusMethodInvocation, get_property_info);
ZEND_METHOD(Gtk4_GDBusMethodInvocation, get_sender);
ZEND_METHOD(Gtk4_GDBusMethodInvocation, return_dbus_error);
ZEND_METHOD(Gtk4_GDBusMethodInvocation, return_gerror);
ZEND_METHOD(Gtk4_GDBusMethodInvocation, return_value);
ZEND_METHOD(Gtk4_GDBusNodeInfo, __construct);
ZEND_METHOD(Gtk4_GDBusNodeInfo, new_for_xml);
ZEND_METHOD(Gtk4_GDBusNodeInfo, lookup_interface);
ZEND_METHOD(Gtk4_GDBusPropertyInfo, __construct);
ZEND_METHOD(Gtk4_GDBusProxy, __construct);
ZEND_METHOD(Gtk4_GDBusProxy, new_finish);
ZEND_METHOD(Gtk4_GDBusProxy, new_for_bus_finish);
ZEND_METHOD(Gtk4_GDBusProxy, new_for_bus_sync);
ZEND_METHOD(Gtk4_GDBusProxy, new_sync);
ZEND_METHOD(Gtk4_GDBusProxy, new);
ZEND_METHOD(Gtk4_GDBusProxy, new_for_bus);
ZEND_METHOD(Gtk4_GDBusProxy, call_finish);
ZEND_METHOD(Gtk4_GDBusProxy, get_cached_property);
ZEND_METHOD(Gtk4_GDBusProxy, get_cached_property_names);
ZEND_METHOD(Gtk4_GDBusProxy, get_connection);
ZEND_METHOD(Gtk4_GDBusProxy, get_default_timeout);
ZEND_METHOD(Gtk4_GDBusProxy, get_flags);
ZEND_METHOD(Gtk4_GDBusProxy, get_interface_info);
ZEND_METHOD(Gtk4_GDBusProxy, get_interface_name);
ZEND_METHOD(Gtk4_GDBusProxy, get_name);
ZEND_METHOD(Gtk4_GDBusProxy, get_name_owner);
ZEND_METHOD(Gtk4_GDBusProxy, get_object_path);
ZEND_METHOD(Gtk4_GDBusProxy, set_cached_property);
ZEND_METHOD(Gtk4_GDBusProxy, set_default_timeout);
ZEND_METHOD(Gtk4_GDBusProxy, set_interface_info);
ZEND_METHOD(Gtk4_GDBusProxy, call);
ZEND_METHOD(Gtk4_GDBusProxy, call_sync);
ZEND_METHOD(Gtk4_GDBusProxy, vfunc_g_signal);
ZEND_METHOD(Gtk4_GDBusSignalInfo, __construct);
ZEND_METHOD(Gtk4_GIconObject, __construct);
ZEND_METHOD(Gtk4_GIcon, to_string);
ZEND_METHOD(Gtk4_GInputStream, __construct);
ZEND_METHOD(Gtk4_GInputStream, clear_pending);
ZEND_METHOD(Gtk4_GInputStream, close);
ZEND_METHOD(Gtk4_GInputStream, close_async);
ZEND_METHOD(Gtk4_GInputStream, close_finish);
ZEND_METHOD(Gtk4_GInputStream, has_pending);
ZEND_METHOD(Gtk4_GInputStream, is_closed);
ZEND_METHOD(Gtk4_GInputStream, read_all_finish);
ZEND_METHOD(Gtk4_GInputStream, read_bytes);
ZEND_METHOD(Gtk4_GInputStream, read_bytes_async);
ZEND_METHOD(Gtk4_GInputStream, read_bytes_finish);
ZEND_METHOD(Gtk4_GInputStream, read_finish);
ZEND_METHOD(Gtk4_GInputStream, set_pending);
ZEND_METHOD(Gtk4_GInputStream, skip);
ZEND_METHOD(Gtk4_GInputStream, skip_async);
ZEND_METHOD(Gtk4_GInputStream, skip_finish);
ZEND_METHOD(Gtk4_GListModelObject, __construct);
ZEND_METHOD(Gtk4_GListModel, items_changed);
ZEND_METHOD(Gtk4_GListStore, append);
ZEND_METHOD(Gtk4_GListStore, find);
ZEND_METHOD(Gtk4_GListStore, insert);
ZEND_METHOD(Gtk4_GListStore, remove);
ZEND_METHOD(Gtk4_GListStore, remove_all);
ZEND_METHOD(Gtk4_GListStore, __construct);
ZEND_METHOD(Gtk4_GMemoryInputStream, __construct);
ZEND_METHOD(Gtk4_GMemoryInputStream, new_from_bytes);
ZEND_METHOD(Gtk4_GMemoryInputStream, add_bytes);
ZEND_METHOD(Gtk4_GMemoryOutputStream, __construct);
ZEND_METHOD(Gtk4_GMemoryOutputStream, new_resizable);
ZEND_METHOD(Gtk4_GMemoryOutputStream, get_data_size);
ZEND_METHOD(Gtk4_GMemoryOutputStream, get_size);
ZEND_METHOD(Gtk4_GMemoryOutputStream, steal_as_bytes);
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
ZEND_METHOD(Gtk4_GMenuItem, set_icon);
ZEND_METHOD(Gtk4_GMenuItem, set_label);
ZEND_METHOD(Gtk4_GMenuItem, set_link);
ZEND_METHOD(Gtk4_GMenuItem, set_section);
ZEND_METHOD(Gtk4_GMenuItem, set_submenu);
ZEND_METHOD(Gtk4_GMenuModel, __construct);
ZEND_METHOD(Gtk4_GMenuModel, get_n_items);
ZEND_METHOD(Gtk4_GMenuModel, is_mutable);
ZEND_METHOD(Gtk4_GMenuModel, items_changed);
ZEND_METHOD(Gtk4_GMenuModel, get_item_attribute_value);
ZEND_METHOD(Gtk4_GMenuModel, get_item_link);
ZEND_METHOD(Gtk4_GMenuModel, vfunc_get_item_attribute_value);
ZEND_METHOD(Gtk4_GMenuModel, vfunc_get_item_link);
ZEND_METHOD(Gtk4_GMenuModel, vfunc_get_n_items);
ZEND_METHOD(Gtk4_GMenuModel, vfunc_is_mutable);
ZEND_METHOD(Gtk4_GNotification, __construct);
ZEND_METHOD(Gtk4_GNotification, add_button);
ZEND_METHOD(Gtk4_GNotification, add_button_with_target);
ZEND_METHOD(Gtk4_GNotification, set_body);
ZEND_METHOD(Gtk4_GNotification, set_category);
ZEND_METHOD(Gtk4_GNotification, set_default_action);
ZEND_METHOD(Gtk4_GNotification, set_default_action_and_target);
ZEND_METHOD(Gtk4_GNotification, set_icon);
ZEND_METHOD(Gtk4_GNotification, set_priority);
ZEND_METHOD(Gtk4_GNotification, set_title);
ZEND_METHOD(Gtk4_GOutputStream, __construct);
ZEND_METHOD(Gtk4_GOutputStream, clear_pending);
ZEND_METHOD(Gtk4_GOutputStream, close);
ZEND_METHOD(Gtk4_GOutputStream, close_async);
ZEND_METHOD(Gtk4_GOutputStream, close_finish);
ZEND_METHOD(Gtk4_GOutputStream, flush);
ZEND_METHOD(Gtk4_GOutputStream, flush_async);
ZEND_METHOD(Gtk4_GOutputStream, flush_finish);
ZEND_METHOD(Gtk4_GOutputStream, has_pending);
ZEND_METHOD(Gtk4_GOutputStream, is_closed);
ZEND_METHOD(Gtk4_GOutputStream, is_closing);
ZEND_METHOD(Gtk4_GOutputStream, set_pending);
ZEND_METHOD(Gtk4_GOutputStream, splice);
ZEND_METHOD(Gtk4_GOutputStream, splice_async);
ZEND_METHOD(Gtk4_GOutputStream, splice_finish);
ZEND_METHOD(Gtk4_GOutputStream, write_all_finish);
ZEND_METHOD(Gtk4_GOutputStream, write_bytes);
ZEND_METHOD(Gtk4_GOutputStream, write_bytes_async);
ZEND_METHOD(Gtk4_GOutputStream, write_bytes_finish);
ZEND_METHOD(Gtk4_GOutputStream, write_finish);
ZEND_METHOD(Gtk4_GOutputStream, writev_all_finish);
ZEND_METHOD(Gtk4_GOutputStream, writev_finish);
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
ZEND_METHOD(Gtk4_GTask, return_error_if_cancelled);
ZEND_METHOD(Gtk4_GTask, set_check_cancellable);
ZEND_METHOD(Gtk4_GTask, set_name);
ZEND_METHOD(Gtk4_GTask, set_priority);
ZEND_METHOD(Gtk4_GTask, set_return_on_cancel);
ZEND_METHOD(Gtk4_GTask, set_static_name);
ZEND_METHOD(Gtk4_GTask, propagate_boolean);
ZEND_METHOD(Gtk4_GTask, propagate_int);
ZEND_METHOD(Gtk4_GTask, return_boolean);
ZEND_METHOD(Gtk4_GTask, return_error);
ZEND_METHOD(Gtk4_GTask, return_int);
ZEND_METHOD(Gtk4_GTestDBus, __construct);
ZEND_METHOD(Gtk4_GTestDBus, unset);
ZEND_METHOD(Gtk4_GTestDBus, add_service_dir);
ZEND_METHOD(Gtk4_GTestDBus, down);
ZEND_METHOD(Gtk4_GTestDBus, get_bus_address);
ZEND_METHOD(Gtk4_GTestDBus, get_flags);
ZEND_METHOD(Gtk4_GTestDBus, stop);
ZEND_METHOD(Gtk4_GTestDBus, up);
ZEND_METHOD(Gtk4_GThemedIcon, __construct);
ZEND_METHOD(Gtk4_GThemedIcon, new_from_names);
ZEND_METHOD(Gtk4_GThemedIcon, new_with_default_fallbacks);
ZEND_METHOD(Gtk4_GThemedIcon, append_name);
ZEND_METHOD(Gtk4_GThemedIcon, get_names);
ZEND_METHOD(Gtk4_GThemedIcon, prepend_name);
ZEND_METHOD(Gtk4_GTlsCertificate, __construct);
ZEND_METHOD(Gtk4_GTlsCertificate, new_from_file);
ZEND_METHOD(Gtk4_GTlsCertificate, new_from_file_with_password);
ZEND_METHOD(Gtk4_GTlsCertificate, new_from_files);
ZEND_METHOD(Gtk4_GTlsCertificate, new_from_pem);
ZEND_METHOD(Gtk4_GTlsCertificate, new_from_pkcs11_uris);
ZEND_METHOD(Gtk4_GTlsCertificate, list_new_from_file);
ZEND_METHOD(Gtk4_GTlsCertificate, get_dns_names);
ZEND_METHOD(Gtk4_GTlsCertificate, get_issuer);
ZEND_METHOD(Gtk4_GTlsCertificate, get_issuer_name);
ZEND_METHOD(Gtk4_GTlsCertificate, get_not_valid_after);
ZEND_METHOD(Gtk4_GTlsCertificate, get_not_valid_before);
ZEND_METHOD(Gtk4_GTlsCertificate, get_subject_name);
ZEND_METHOD(Gtk4_GTlsCertificate, is_same);

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
	ZEND_RAW_FENTRY("activate_action", NULL, arginfo_class_Gtk4_GActionGroup_activate_action, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("change_action_state", NULL, arginfo_class_Gtk4_GActionGroup_change_action_state, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_enabled", NULL, arginfo_class_Gtk4_GActionGroup_get_action_enabled, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_parameter_type", NULL, arginfo_class_Gtk4_GActionGroup_get_action_parameter_type, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state", NULL, arginfo_class_Gtk4_GActionGroup_get_action_state, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state_hint", NULL, arginfo_class_Gtk4_GActionGroup_get_action_state_hint, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("get_action_state_type", NULL, arginfo_class_Gtk4_GActionGroup_get_action_state_type, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("has_action", NULL, arginfo_class_Gtk4_GActionGroup_has_action, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("list_actions", NULL, arginfo_class_Gtk4_GActionGroup_list_actions, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
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
	ZEND_ME(Gtk4_GApplication, get_flags, arginfo_class_Gtk4_GApplication_get_flags, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_inactivity_timeout, arginfo_class_Gtk4_GApplication_get_inactivity_timeout, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_is_busy, arginfo_class_Gtk4_GApplication_get_is_busy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_is_registered, arginfo_class_Gtk4_GApplication_get_is_registered, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_resource_base_path, arginfo_class_Gtk4_GApplication_get_resource_base_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_version, arginfo_class_Gtk4_GApplication_get_version, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, hold, arginfo_class_Gtk4_GApplication_hold, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, mark_busy, arginfo_class_Gtk4_GApplication_mark_busy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, quit, arginfo_class_Gtk4_GApplication_quit, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, register, arginfo_class_Gtk4_GApplication_register, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, release, arginfo_class_Gtk4_GApplication_release, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, send_notification, arginfo_class_Gtk4_GApplication_send_notification, ZEND_ACC_PUBLIC)
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
	ZEND_ME(Gtk4_GApplication, get_dbus_connection, arginfo_class_Gtk4_GApplication_get_dbus_connection, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_dbus_object_path, arginfo_class_Gtk4_GApplication_get_dbus_object_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, get_is_remote, arginfo_class_Gtk4_GApplication_get_is_remote, ZEND_ACC_PUBLIC)
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
	ZEND_ME(Gtk4_GApplication, vfunc_after_emit, arginfo_class_Gtk4_GApplication_vfunc_after_emit, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, vfunc_before_emit, arginfo_class_Gtk4_GApplication_vfunc_before_emit, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GApplication, vfunc_dbus_unregister, arginfo_class_Gtk4_GApplication_vfunc_dbus_unregister, ZEND_ACC_PUBLIC)
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
	ZEND_ME(Gtk4_GCancellable, reset, arginfo_class_Gtk4_GCancellable_reset, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GCancellable, set_error_if_cancelled, arginfo_class_Gtk4_GCancellable_set_error_if_cancelled, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GCancellable, vfunc_cancelled, arginfo_class_Gtk4_GCancellable_vfunc_cancelled, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GDBusArgInfo_methods[] = {
	ZEND_ME(Gtk4_GDBusArgInfo, __construct, arginfo_class_Gtk4_GDBusArgInfo___construct, ZEND_ACC_PRIVATE)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GDBusConnection_methods[] = {
	ZEND_ME(Gtk4_GDBusConnection, __construct, arginfo_class_Gtk4_GDBusConnection___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GDBusConnection, new_finish, arginfo_class_Gtk4_GDBusConnection_new_finish, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDBusConnection, new_for_address_finish, arginfo_class_Gtk4_GDBusConnection_new_for_address_finish, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDBusConnection, call_finish, arginfo_class_Gtk4_GDBusConnection_call_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, close, arginfo_class_Gtk4_GDBusConnection_close, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, close_finish, arginfo_class_Gtk4_GDBusConnection_close_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, close_sync, arginfo_class_Gtk4_GDBusConnection_close_sync, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, export_action_group, arginfo_class_Gtk4_GDBusConnection_export_action_group, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, export_menu_model, arginfo_class_Gtk4_GDBusConnection_export_menu_model, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, flush, arginfo_class_Gtk4_GDBusConnection_flush, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, flush_finish, arginfo_class_Gtk4_GDBusConnection_flush_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, flush_sync, arginfo_class_Gtk4_GDBusConnection_flush_sync, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, get_capabilities, arginfo_class_Gtk4_GDBusConnection_get_capabilities, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, get_exit_on_close, arginfo_class_Gtk4_GDBusConnection_get_exit_on_close, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, get_flags, arginfo_class_Gtk4_GDBusConnection_get_flags, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, get_guid, arginfo_class_Gtk4_GDBusConnection_get_guid, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, get_last_serial, arginfo_class_Gtk4_GDBusConnection_get_last_serial, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, get_unique_name, arginfo_class_Gtk4_GDBusConnection_get_unique_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, is_closed, arginfo_class_Gtk4_GDBusConnection_is_closed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, remove_filter, arginfo_class_Gtk4_GDBusConnection_remove_filter, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, set_exit_on_close, arginfo_class_Gtk4_GDBusConnection_set_exit_on_close, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, signal_unsubscribe, arginfo_class_Gtk4_GDBusConnection_signal_unsubscribe, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, start_message_processing, arginfo_class_Gtk4_GDBusConnection_start_message_processing, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, unexport_action_group, arginfo_class_Gtk4_GDBusConnection_unexport_action_group, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, unexport_menu_model, arginfo_class_Gtk4_GDBusConnection_unexport_menu_model, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, unregister_object, arginfo_class_Gtk4_GDBusConnection_unregister_object, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, unregister_subtree, arginfo_class_Gtk4_GDBusConnection_unregister_subtree, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, bus_get_sync, arginfo_class_Gtk4_GDBusConnection_bus_get_sync, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDBusConnection, call, arginfo_class_Gtk4_GDBusConnection_call, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, call_sync, arginfo_class_Gtk4_GDBusConnection_call_sync, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, emit_signal, arginfo_class_Gtk4_GDBusConnection_emit_signal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, register_object, arginfo_class_Gtk4_GDBusConnection_register_object, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusConnection, signal_subscribe, arginfo_class_Gtk4_GDBusConnection_signal_subscribe, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GDBusInterfaceInfo_methods[] = {
	ZEND_ME(Gtk4_GDBusInterfaceInfo, __construct, arginfo_class_Gtk4_GDBusInterfaceInfo___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GDBusInterfaceInfo, cache_build, arginfo_class_Gtk4_GDBusInterfaceInfo_cache_build, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusInterfaceInfo, cache_release, arginfo_class_Gtk4_GDBusInterfaceInfo_cache_release, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusInterfaceInfo, lookup_method, arginfo_class_Gtk4_GDBusInterfaceInfo_lookup_method, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusInterfaceInfo, lookup_property, arginfo_class_Gtk4_GDBusInterfaceInfo_lookup_property, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusInterfaceInfo, lookup_signal, arginfo_class_Gtk4_GDBusInterfaceInfo_lookup_signal, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GDBusMethodInfo_methods[] = {
	ZEND_ME(Gtk4_GDBusMethodInfo, __construct, arginfo_class_Gtk4_GDBusMethodInfo___construct, ZEND_ACC_PRIVATE)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GDBusMethodInvocation_methods[] = {
	ZEND_ME(Gtk4_GDBusMethodInvocation, __construct, arginfo_class_Gtk4_GDBusMethodInvocation___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GDBusMethodInvocation, get_connection, arginfo_class_Gtk4_GDBusMethodInvocation_get_connection, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusMethodInvocation, get_interface_name, arginfo_class_Gtk4_GDBusMethodInvocation_get_interface_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusMethodInvocation, get_method_info, arginfo_class_Gtk4_GDBusMethodInvocation_get_method_info, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusMethodInvocation, get_method_name, arginfo_class_Gtk4_GDBusMethodInvocation_get_method_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusMethodInvocation, get_object_path, arginfo_class_Gtk4_GDBusMethodInvocation_get_object_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusMethodInvocation, get_parameters, arginfo_class_Gtk4_GDBusMethodInvocation_get_parameters, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusMethodInvocation, get_property_info, arginfo_class_Gtk4_GDBusMethodInvocation_get_property_info, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusMethodInvocation, get_sender, arginfo_class_Gtk4_GDBusMethodInvocation_get_sender, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusMethodInvocation, return_dbus_error, arginfo_class_Gtk4_GDBusMethodInvocation_return_dbus_error, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusMethodInvocation, return_gerror, arginfo_class_Gtk4_GDBusMethodInvocation_return_gerror, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusMethodInvocation, return_value, arginfo_class_Gtk4_GDBusMethodInvocation_return_value, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GDBusNodeInfo_methods[] = {
	ZEND_ME(Gtk4_GDBusNodeInfo, __construct, arginfo_class_Gtk4_GDBusNodeInfo___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GDBusNodeInfo, new_for_xml, arginfo_class_Gtk4_GDBusNodeInfo_new_for_xml, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDBusNodeInfo, lookup_interface, arginfo_class_Gtk4_GDBusNodeInfo_lookup_interface, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GDBusPropertyInfo_methods[] = {
	ZEND_ME(Gtk4_GDBusPropertyInfo, __construct, arginfo_class_Gtk4_GDBusPropertyInfo___construct, ZEND_ACC_PRIVATE)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GDBusProxy_methods[] = {
	ZEND_ME(Gtk4_GDBusProxy, __construct, arginfo_class_Gtk4_GDBusProxy___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GDBusProxy, new_finish, arginfo_class_Gtk4_GDBusProxy_new_finish, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDBusProxy, new_for_bus_finish, arginfo_class_Gtk4_GDBusProxy_new_for_bus_finish, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDBusProxy, new_for_bus_sync, arginfo_class_Gtk4_GDBusProxy_new_for_bus_sync, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDBusProxy, new_sync, arginfo_class_Gtk4_GDBusProxy_new_sync, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDBusProxy, new, arginfo_class_Gtk4_GDBusProxy_new, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDBusProxy, new_for_bus, arginfo_class_Gtk4_GDBusProxy_new_for_bus, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDBusProxy, call_finish, arginfo_class_Gtk4_GDBusProxy_call_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, get_cached_property, arginfo_class_Gtk4_GDBusProxy_get_cached_property, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, get_cached_property_names, arginfo_class_Gtk4_GDBusProxy_get_cached_property_names, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, get_connection, arginfo_class_Gtk4_GDBusProxy_get_connection, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, get_default_timeout, arginfo_class_Gtk4_GDBusProxy_get_default_timeout, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, get_flags, arginfo_class_Gtk4_GDBusProxy_get_flags, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, get_interface_info, arginfo_class_Gtk4_GDBusProxy_get_interface_info, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, get_interface_name, arginfo_class_Gtk4_GDBusProxy_get_interface_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, get_name, arginfo_class_Gtk4_GDBusProxy_get_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, get_name_owner, arginfo_class_Gtk4_GDBusProxy_get_name_owner, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, get_object_path, arginfo_class_Gtk4_GDBusProxy_get_object_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, set_cached_property, arginfo_class_Gtk4_GDBusProxy_set_cached_property, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, set_default_timeout, arginfo_class_Gtk4_GDBusProxy_set_default_timeout, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, set_interface_info, arginfo_class_Gtk4_GDBusProxy_set_interface_info, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, call, arginfo_class_Gtk4_GDBusProxy_call, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, call_sync, arginfo_class_Gtk4_GDBusProxy_call_sync, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDBusProxy, vfunc_g_signal, arginfo_class_Gtk4_GDBusProxy_vfunc_g_signal, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GDBusSignalInfo_methods[] = {
	ZEND_ME(Gtk4_GDBusSignalInfo, __construct, arginfo_class_Gtk4_GDBusSignalInfo___construct, ZEND_ACC_PRIVATE)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GIcon_methods[] = {
	ZEND_RAW_FENTRY("equal", NULL, arginfo_class_Gtk4_GIcon_equal, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("hash", NULL, arginfo_class_Gtk4_GIcon_hash, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_RAW_FENTRY("serialize", NULL, arginfo_class_Gtk4_GIcon_serialize, ZEND_ACC_PUBLIC|ZEND_ACC_ABSTRACT, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GIconObject_methods[] = {
	ZEND_ME(Gtk4_GIconObject, __construct, arginfo_class_Gtk4_GIconObject___construct, ZEND_ACC_PRIVATE)
	ZEND_RAW_FENTRY("equal", zim_Gtk4_GIcon_equal, arginfo_class_Gtk4_GIconObject_equal, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("hash", zim_Gtk4_GIcon_hash, arginfo_class_Gtk4_GIconObject_hash, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("serialize", zim_Gtk4_GIcon_serialize, arginfo_class_Gtk4_GIconObject_serialize, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("to_string", zim_Gtk4_GIcon_to_string, arginfo_class_Gtk4_GIconObject_to_string, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GInputStream_methods[] = {
	ZEND_ME(Gtk4_GInputStream, __construct, arginfo_class_Gtk4_GInputStream___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, clear_pending, arginfo_class_Gtk4_GInputStream_clear_pending, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, close, arginfo_class_Gtk4_GInputStream_close, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, close_async, arginfo_class_Gtk4_GInputStream_close_async, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, close_finish, arginfo_class_Gtk4_GInputStream_close_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, has_pending, arginfo_class_Gtk4_GInputStream_has_pending, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, is_closed, arginfo_class_Gtk4_GInputStream_is_closed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, read_all_finish, arginfo_class_Gtk4_GInputStream_read_all_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, read_bytes, arginfo_class_Gtk4_GInputStream_read_bytes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, read_bytes_async, arginfo_class_Gtk4_GInputStream_read_bytes_async, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, read_bytes_finish, arginfo_class_Gtk4_GInputStream_read_bytes_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, read_finish, arginfo_class_Gtk4_GInputStream_read_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, set_pending, arginfo_class_Gtk4_GInputStream_set_pending, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, skip, arginfo_class_Gtk4_GInputStream_skip, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, skip_async, arginfo_class_Gtk4_GInputStream_skip_async, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GInputStream, skip_finish, arginfo_class_Gtk4_GInputStream_skip_finish, ZEND_ACC_PUBLIC)
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

static const zend_function_entry class_Gtk4_GMemoryInputStream_methods[] = {
	ZEND_ME(Gtk4_GMemoryInputStream, __construct, arginfo_class_Gtk4_GMemoryInputStream___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMemoryInputStream, new_from_bytes, arginfo_class_Gtk4_GMemoryInputStream_new_from_bytes, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GMemoryInputStream, add_bytes, arginfo_class_Gtk4_GMemoryInputStream_add_bytes, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GMemoryOutputStream_methods[] = {
	ZEND_ME(Gtk4_GMemoryOutputStream, __construct, arginfo_class_Gtk4_GMemoryOutputStream___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMemoryOutputStream, new_resizable, arginfo_class_Gtk4_GMemoryOutputStream_new_resizable, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GMemoryOutputStream, get_data_size, arginfo_class_Gtk4_GMemoryOutputStream_get_data_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMemoryOutputStream, get_size, arginfo_class_Gtk4_GMemoryOutputStream_get_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMemoryOutputStream, steal_as_bytes, arginfo_class_Gtk4_GMemoryOutputStream_steal_as_bytes, ZEND_ACC_PUBLIC)
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
	ZEND_ME(Gtk4_GMenuItem, set_icon, arginfo_class_Gtk4_GMenuItem_set_icon, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuItem, set_label, arginfo_class_Gtk4_GMenuItem_set_label, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuItem, set_link, arginfo_class_Gtk4_GMenuItem_set_link, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuItem, set_section, arginfo_class_Gtk4_GMenuItem_set_section, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuItem, set_submenu, arginfo_class_Gtk4_GMenuItem_set_submenu, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GMenuModel_methods[] = {
	ZEND_ME(Gtk4_GMenuModel, __construct, arginfo_class_Gtk4_GMenuModel___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GMenuModel, get_n_items, arginfo_class_Gtk4_GMenuModel_get_n_items, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, is_mutable, arginfo_class_Gtk4_GMenuModel_is_mutable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, items_changed, arginfo_class_Gtk4_GMenuModel_items_changed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, get_item_attribute_value, arginfo_class_Gtk4_GMenuModel_get_item_attribute_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, get_item_link, arginfo_class_Gtk4_GMenuModel_get_item_link, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, vfunc_get_item_attribute_value, arginfo_class_Gtk4_GMenuModel_vfunc_get_item_attribute_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, vfunc_get_item_link, arginfo_class_Gtk4_GMenuModel_vfunc_get_item_link, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, vfunc_get_n_items, arginfo_class_Gtk4_GMenuModel_vfunc_get_n_items, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GMenuModel, vfunc_is_mutable, arginfo_class_Gtk4_GMenuModel_vfunc_is_mutable, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GNotification_methods[] = {
	ZEND_ME(Gtk4_GNotification, __construct, arginfo_class_Gtk4_GNotification___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GNotification, add_button, arginfo_class_Gtk4_GNotification_add_button, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GNotification, add_button_with_target, arginfo_class_Gtk4_GNotification_add_button_with_target, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GNotification, set_body, arginfo_class_Gtk4_GNotification_set_body, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GNotification, set_category, arginfo_class_Gtk4_GNotification_set_category, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GNotification, set_default_action, arginfo_class_Gtk4_GNotification_set_default_action, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GNotification, set_default_action_and_target, arginfo_class_Gtk4_GNotification_set_default_action_and_target, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GNotification, set_icon, arginfo_class_Gtk4_GNotification_set_icon, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GNotification, set_priority, arginfo_class_Gtk4_GNotification_set_priority, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GNotification, set_title, arginfo_class_Gtk4_GNotification_set_title, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GOutputStream_methods[] = {
	ZEND_ME(Gtk4_GOutputStream, __construct, arginfo_class_Gtk4_GOutputStream___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, clear_pending, arginfo_class_Gtk4_GOutputStream_clear_pending, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, close, arginfo_class_Gtk4_GOutputStream_close, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, close_async, arginfo_class_Gtk4_GOutputStream_close_async, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, close_finish, arginfo_class_Gtk4_GOutputStream_close_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, flush, arginfo_class_Gtk4_GOutputStream_flush, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, flush_async, arginfo_class_Gtk4_GOutputStream_flush_async, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, flush_finish, arginfo_class_Gtk4_GOutputStream_flush_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, has_pending, arginfo_class_Gtk4_GOutputStream_has_pending, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, is_closed, arginfo_class_Gtk4_GOutputStream_is_closed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, is_closing, arginfo_class_Gtk4_GOutputStream_is_closing, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, set_pending, arginfo_class_Gtk4_GOutputStream_set_pending, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, splice, arginfo_class_Gtk4_GOutputStream_splice, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, splice_async, arginfo_class_Gtk4_GOutputStream_splice_async, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, splice_finish, arginfo_class_Gtk4_GOutputStream_splice_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, write_all_finish, arginfo_class_Gtk4_GOutputStream_write_all_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, write_bytes, arginfo_class_Gtk4_GOutputStream_write_bytes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, write_bytes_async, arginfo_class_Gtk4_GOutputStream_write_bytes_async, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, write_bytes_finish, arginfo_class_Gtk4_GOutputStream_write_bytes_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, write_finish, arginfo_class_Gtk4_GOutputStream_write_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, writev_all_finish, arginfo_class_Gtk4_GOutputStream_writev_all_finish, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GOutputStream, writev_finish, arginfo_class_Gtk4_GOutputStream_writev_finish, ZEND_ACC_PUBLIC)
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
	ZEND_ME(Gtk4_GTask, return_error_if_cancelled, arginfo_class_Gtk4_GTask_return_error_if_cancelled, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, set_check_cancellable, arginfo_class_Gtk4_GTask_set_check_cancellable, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, set_name, arginfo_class_Gtk4_GTask_set_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, set_priority, arginfo_class_Gtk4_GTask_set_priority, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, set_return_on_cancel, arginfo_class_Gtk4_GTask_set_return_on_cancel, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, set_static_name, arginfo_class_Gtk4_GTask_set_static_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, propagate_boolean, arginfo_class_Gtk4_GTask_propagate_boolean, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, propagate_int, arginfo_class_Gtk4_GTask_propagate_int, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, return_boolean, arginfo_class_Gtk4_GTask_return_boolean, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, return_error, arginfo_class_Gtk4_GTask_return_error, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTask, return_int, arginfo_class_Gtk4_GTask_return_int, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("legacy_propagate_error", zim_Gtk4_GAsyncResult_legacy_propagate_error, arginfo_class_Gtk4_GTask_legacy_propagate_error, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GTestDBus_methods[] = {
	ZEND_ME(Gtk4_GTestDBus, __construct, arginfo_class_Gtk4_GTestDBus___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTestDBus, unset, arginfo_class_Gtk4_GTestDBus_unset, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GTestDBus, add_service_dir, arginfo_class_Gtk4_GTestDBus_add_service_dir, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTestDBus, down, arginfo_class_Gtk4_GTestDBus_down, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTestDBus, get_bus_address, arginfo_class_Gtk4_GTestDBus_get_bus_address, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTestDBus, get_flags, arginfo_class_Gtk4_GTestDBus_get_flags, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTestDBus, stop, arginfo_class_Gtk4_GTestDBus_stop, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTestDBus, up, arginfo_class_Gtk4_GTestDBus_up, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GThemedIcon_methods[] = {
	ZEND_ME(Gtk4_GThemedIcon, __construct, arginfo_class_Gtk4_GThemedIcon___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GThemedIcon, new_from_names, arginfo_class_Gtk4_GThemedIcon_new_from_names, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GThemedIcon, new_with_default_fallbacks, arginfo_class_Gtk4_GThemedIcon_new_with_default_fallbacks, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GThemedIcon, append_name, arginfo_class_Gtk4_GThemedIcon_append_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GThemedIcon, get_names, arginfo_class_Gtk4_GThemedIcon_get_names, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GThemedIcon, prepend_name, arginfo_class_Gtk4_GThemedIcon_prepend_name, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("equal", zim_Gtk4_GIcon_equal, arginfo_class_Gtk4_GThemedIcon_equal, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("hash", zim_Gtk4_GIcon_hash, arginfo_class_Gtk4_GThemedIcon_hash, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("serialize", zim_Gtk4_GIcon_serialize, arginfo_class_Gtk4_GThemedIcon_serialize, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("to_string", zim_Gtk4_GIcon_to_string, arginfo_class_Gtk4_GThemedIcon_to_string, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GTlsCertificate_methods[] = {
	ZEND_ME(Gtk4_GTlsCertificate, __construct, arginfo_class_Gtk4_GTlsCertificate___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GTlsCertificate, new_from_file, arginfo_class_Gtk4_GTlsCertificate_new_from_file, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GTlsCertificate, new_from_file_with_password, arginfo_class_Gtk4_GTlsCertificate_new_from_file_with_password, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GTlsCertificate, new_from_files, arginfo_class_Gtk4_GTlsCertificate_new_from_files, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GTlsCertificate, new_from_pem, arginfo_class_Gtk4_GTlsCertificate_new_from_pem, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GTlsCertificate, new_from_pkcs11_uris, arginfo_class_Gtk4_GTlsCertificate_new_from_pkcs11_uris, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GTlsCertificate, list_new_from_file, arginfo_class_Gtk4_GTlsCertificate_list_new_from_file, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GTlsCertificate, get_dns_names, arginfo_class_Gtk4_GTlsCertificate_get_dns_names, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTlsCertificate, get_issuer, arginfo_class_Gtk4_GTlsCertificate_get_issuer, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTlsCertificate, get_issuer_name, arginfo_class_Gtk4_GTlsCertificate_get_issuer_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTlsCertificate, get_not_valid_after, arginfo_class_Gtk4_GTlsCertificate_get_not_valid_after, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTlsCertificate, get_not_valid_before, arginfo_class_Gtk4_GTlsCertificate_get_not_valid_before, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTlsCertificate, get_subject_name, arginfo_class_Gtk4_GTlsCertificate_get_subject_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTlsCertificate, is_same, arginfo_class_Gtk4_GTlsCertificate_is_same, ZEND_ACC_PUBLIC)
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

static zend_class_entry *register_class_Gtk4_GBusType(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GBusType", IS_LONG, NULL);

	zval enum_case_Starter_value;
	ZVAL_LONG(&enum_case_Starter_value, -1);
	zend_enum_add_case_cstr(class_entry, "Starter", &enum_case_Starter_value);

	zval enum_case_None_value;
	ZVAL_LONG(&enum_case_None_value, 0);
	zend_enum_add_case_cstr(class_entry, "None", &enum_case_None_value);

	zval enum_case_System_value;
	ZVAL_LONG(&enum_case_System_value, 1);
	zend_enum_add_case_cstr(class_entry, "System", &enum_case_System_value);

	zval enum_case_Session_value;
	ZVAL_LONG(&enum_case_Session_value, 2);
	zend_enum_add_case_cstr(class_entry, "Session", &enum_case_Session_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GCancellable(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GCancellable", class_Gtk4_GCancellable_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusArgInfo(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusArgInfo", class_Gtk4_GDBusArgInfo_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusCallFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusCallFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_NONE_value;
	ZVAL_LONG(&const_NONE_value, 0);
	zend_string *const_NONE_name = zend_string_init_interned("NONE", sizeof("NONE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NONE_name, &const_NONE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NONE_name);

	zval const_NO_AUTO_START_value;
	ZVAL_LONG(&const_NO_AUTO_START_value, 1);
	zend_string *const_NO_AUTO_START_name = zend_string_init_interned("NO_AUTO_START", sizeof("NO_AUTO_START") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NO_AUTO_START_name, &const_NO_AUTO_START_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NO_AUTO_START_name);

	zval const_ALLOW_INTERACTIVE_AUTHORIZATION_value;
	ZVAL_LONG(&const_ALLOW_INTERACTIVE_AUTHORIZATION_value, 2);
	zend_string *const_ALLOW_INTERACTIVE_AUTHORIZATION_name = zend_string_init_interned("ALLOW_INTERACTIVE_AUTHORIZATION", sizeof("ALLOW_INTERACTIVE_AUTHORIZATION") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_ALLOW_INTERACTIVE_AUTHORIZATION_name, &const_ALLOW_INTERACTIVE_AUTHORIZATION_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_ALLOW_INTERACTIVE_AUTHORIZATION_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusCapabilityFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusCapabilityFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_NONE_value;
	ZVAL_LONG(&const_NONE_value, 0);
	zend_string *const_NONE_name = zend_string_init_interned("NONE", sizeof("NONE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NONE_name, &const_NONE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NONE_name);

	zval const_UNIX_FD_PASSING_value;
	ZVAL_LONG(&const_UNIX_FD_PASSING_value, 1);
	zend_string *const_UNIX_FD_PASSING_name = zend_string_init_interned("UNIX_FD_PASSING", sizeof("UNIX_FD_PASSING") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_UNIX_FD_PASSING_name, &const_UNIX_FD_PASSING_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_UNIX_FD_PASSING_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusConnection(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusConnection", class_Gtk4_GDBusConnection_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusConnectionFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusConnectionFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_NONE_value;
	ZVAL_LONG(&const_NONE_value, 0);
	zend_string *const_NONE_name = zend_string_init_interned("NONE", sizeof("NONE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NONE_name, &const_NONE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NONE_name);

	zval const_AUTHENTICATION_CLIENT_value;
	ZVAL_LONG(&const_AUTHENTICATION_CLIENT_value, 1);
	zend_string *const_AUTHENTICATION_CLIENT_name = zend_string_init_interned("AUTHENTICATION_CLIENT", sizeof("AUTHENTICATION_CLIENT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_AUTHENTICATION_CLIENT_name, &const_AUTHENTICATION_CLIENT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_AUTHENTICATION_CLIENT_name);

	zval const_AUTHENTICATION_SERVER_value;
	ZVAL_LONG(&const_AUTHENTICATION_SERVER_value, 2);
	zend_string *const_AUTHENTICATION_SERVER_name = zend_string_init_interned("AUTHENTICATION_SERVER", sizeof("AUTHENTICATION_SERVER") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_AUTHENTICATION_SERVER_name, &const_AUTHENTICATION_SERVER_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_AUTHENTICATION_SERVER_name);

	zval const_AUTHENTICATION_ALLOW_ANONYMOUS_value;
	ZVAL_LONG(&const_AUTHENTICATION_ALLOW_ANONYMOUS_value, 4);
	zend_string *const_AUTHENTICATION_ALLOW_ANONYMOUS_name = zend_string_init_interned("AUTHENTICATION_ALLOW_ANONYMOUS", sizeof("AUTHENTICATION_ALLOW_ANONYMOUS") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_AUTHENTICATION_ALLOW_ANONYMOUS_name, &const_AUTHENTICATION_ALLOW_ANONYMOUS_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_AUTHENTICATION_ALLOW_ANONYMOUS_name);

	zval const_MESSAGE_BUS_CONNECTION_value;
	ZVAL_LONG(&const_MESSAGE_BUS_CONNECTION_value, 8);
	zend_string *const_MESSAGE_BUS_CONNECTION_name = zend_string_init_interned("MESSAGE_BUS_CONNECTION", sizeof("MESSAGE_BUS_CONNECTION") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_MESSAGE_BUS_CONNECTION_name, &const_MESSAGE_BUS_CONNECTION_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_MESSAGE_BUS_CONNECTION_name);

	zval const_DELAY_MESSAGE_PROCESSING_value;
	ZVAL_LONG(&const_DELAY_MESSAGE_PROCESSING_value, 16);
	zend_string *const_DELAY_MESSAGE_PROCESSING_name = zend_string_init_interned("DELAY_MESSAGE_PROCESSING", sizeof("DELAY_MESSAGE_PROCESSING") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_DELAY_MESSAGE_PROCESSING_name, &const_DELAY_MESSAGE_PROCESSING_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_DELAY_MESSAGE_PROCESSING_name);

	zval const_AUTHENTICATION_REQUIRE_SAME_USER_value;
	ZVAL_LONG(&const_AUTHENTICATION_REQUIRE_SAME_USER_value, 32);
	zend_string *const_AUTHENTICATION_REQUIRE_SAME_USER_name = zend_string_init_interned("AUTHENTICATION_REQUIRE_SAME_USER", sizeof("AUTHENTICATION_REQUIRE_SAME_USER") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_AUTHENTICATION_REQUIRE_SAME_USER_name, &const_AUTHENTICATION_REQUIRE_SAME_USER_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_AUTHENTICATION_REQUIRE_SAME_USER_name);

	zval const_CROSS_NAMESPACE_value;
	ZVAL_LONG(&const_CROSS_NAMESPACE_value, 64);
	zend_string *const_CROSS_NAMESPACE_name = zend_string_init_interned("CROSS_NAMESPACE", sizeof("CROSS_NAMESPACE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_CROSS_NAMESPACE_name, &const_CROSS_NAMESPACE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_CROSS_NAMESPACE_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusError(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GDBusError", IS_LONG, NULL);

	zval enum_case_Failed_value;
	ZVAL_LONG(&enum_case_Failed_value, 0);
	zend_enum_add_case_cstr(class_entry, "Failed", &enum_case_Failed_value);

	zval enum_case_NoMemory_value;
	ZVAL_LONG(&enum_case_NoMemory_value, 1);
	zend_enum_add_case_cstr(class_entry, "NoMemory", &enum_case_NoMemory_value);

	zval enum_case_ServiceUnknown_value;
	ZVAL_LONG(&enum_case_ServiceUnknown_value, 2);
	zend_enum_add_case_cstr(class_entry, "ServiceUnknown", &enum_case_ServiceUnknown_value);

	zval enum_case_NameHasNoOwner_value;
	ZVAL_LONG(&enum_case_NameHasNoOwner_value, 3);
	zend_enum_add_case_cstr(class_entry, "NameHasNoOwner", &enum_case_NameHasNoOwner_value);

	zval enum_case_NoReply_value;
	ZVAL_LONG(&enum_case_NoReply_value, 4);
	zend_enum_add_case_cstr(class_entry, "NoReply", &enum_case_NoReply_value);

	zval enum_case_IoError_value;
	ZVAL_LONG(&enum_case_IoError_value, 5);
	zend_enum_add_case_cstr(class_entry, "IoError", &enum_case_IoError_value);

	zval enum_case_BadAddress_value;
	ZVAL_LONG(&enum_case_BadAddress_value, 6);
	zend_enum_add_case_cstr(class_entry, "BadAddress", &enum_case_BadAddress_value);

	zval enum_case_NotSupported_value;
	ZVAL_LONG(&enum_case_NotSupported_value, 7);
	zend_enum_add_case_cstr(class_entry, "NotSupported", &enum_case_NotSupported_value);

	zval enum_case_LimitsExceeded_value;
	ZVAL_LONG(&enum_case_LimitsExceeded_value, 8);
	zend_enum_add_case_cstr(class_entry, "LimitsExceeded", &enum_case_LimitsExceeded_value);

	zval enum_case_AccessDenied_value;
	ZVAL_LONG(&enum_case_AccessDenied_value, 9);
	zend_enum_add_case_cstr(class_entry, "AccessDenied", &enum_case_AccessDenied_value);

	zval enum_case_AuthFailed_value;
	ZVAL_LONG(&enum_case_AuthFailed_value, 10);
	zend_enum_add_case_cstr(class_entry, "AuthFailed", &enum_case_AuthFailed_value);

	zval enum_case_NoServer_value;
	ZVAL_LONG(&enum_case_NoServer_value, 11);
	zend_enum_add_case_cstr(class_entry, "NoServer", &enum_case_NoServer_value);

	zval enum_case_Timeout_value;
	ZVAL_LONG(&enum_case_Timeout_value, 12);
	zend_enum_add_case_cstr(class_entry, "Timeout", &enum_case_Timeout_value);

	zval enum_case_NoNetwork_value;
	ZVAL_LONG(&enum_case_NoNetwork_value, 13);
	zend_enum_add_case_cstr(class_entry, "NoNetwork", &enum_case_NoNetwork_value);

	zval enum_case_AddressInUse_value;
	ZVAL_LONG(&enum_case_AddressInUse_value, 14);
	zend_enum_add_case_cstr(class_entry, "AddressInUse", &enum_case_AddressInUse_value);

	zval enum_case_Disconnected_value;
	ZVAL_LONG(&enum_case_Disconnected_value, 15);
	zend_enum_add_case_cstr(class_entry, "Disconnected", &enum_case_Disconnected_value);

	zval enum_case_InvalidArgs_value;
	ZVAL_LONG(&enum_case_InvalidArgs_value, 16);
	zend_enum_add_case_cstr(class_entry, "InvalidArgs", &enum_case_InvalidArgs_value);

	zval enum_case_FileNotFound_value;
	ZVAL_LONG(&enum_case_FileNotFound_value, 17);
	zend_enum_add_case_cstr(class_entry, "FileNotFound", &enum_case_FileNotFound_value);

	zval enum_case_FileExists_value;
	ZVAL_LONG(&enum_case_FileExists_value, 18);
	zend_enum_add_case_cstr(class_entry, "FileExists", &enum_case_FileExists_value);

	zval enum_case_UnknownMethod_value;
	ZVAL_LONG(&enum_case_UnknownMethod_value, 19);
	zend_enum_add_case_cstr(class_entry, "UnknownMethod", &enum_case_UnknownMethod_value);

	zval enum_case_TimedOut_value;
	ZVAL_LONG(&enum_case_TimedOut_value, 20);
	zend_enum_add_case_cstr(class_entry, "TimedOut", &enum_case_TimedOut_value);

	zval enum_case_MatchRuleNotFound_value;
	ZVAL_LONG(&enum_case_MatchRuleNotFound_value, 21);
	zend_enum_add_case_cstr(class_entry, "MatchRuleNotFound", &enum_case_MatchRuleNotFound_value);

	zval enum_case_MatchRuleInvalid_value;
	ZVAL_LONG(&enum_case_MatchRuleInvalid_value, 22);
	zend_enum_add_case_cstr(class_entry, "MatchRuleInvalid", &enum_case_MatchRuleInvalid_value);

	zval enum_case_SpawnExecFailed_value;
	ZVAL_LONG(&enum_case_SpawnExecFailed_value, 23);
	zend_enum_add_case_cstr(class_entry, "SpawnExecFailed", &enum_case_SpawnExecFailed_value);

	zval enum_case_SpawnForkFailed_value;
	ZVAL_LONG(&enum_case_SpawnForkFailed_value, 24);
	zend_enum_add_case_cstr(class_entry, "SpawnForkFailed", &enum_case_SpawnForkFailed_value);

	zval enum_case_SpawnChildExited_value;
	ZVAL_LONG(&enum_case_SpawnChildExited_value, 25);
	zend_enum_add_case_cstr(class_entry, "SpawnChildExited", &enum_case_SpawnChildExited_value);

	zval enum_case_SpawnChildSignaled_value;
	ZVAL_LONG(&enum_case_SpawnChildSignaled_value, 26);
	zend_enum_add_case_cstr(class_entry, "SpawnChildSignaled", &enum_case_SpawnChildSignaled_value);

	zval enum_case_SpawnFailed_value;
	ZVAL_LONG(&enum_case_SpawnFailed_value, 27);
	zend_enum_add_case_cstr(class_entry, "SpawnFailed", &enum_case_SpawnFailed_value);

	zval enum_case_SpawnSetupFailed_value;
	ZVAL_LONG(&enum_case_SpawnSetupFailed_value, 28);
	zend_enum_add_case_cstr(class_entry, "SpawnSetupFailed", &enum_case_SpawnSetupFailed_value);

	zval enum_case_SpawnConfigInvalid_value;
	ZVAL_LONG(&enum_case_SpawnConfigInvalid_value, 29);
	zend_enum_add_case_cstr(class_entry, "SpawnConfigInvalid", &enum_case_SpawnConfigInvalid_value);

	zval enum_case_SpawnServiceInvalid_value;
	ZVAL_LONG(&enum_case_SpawnServiceInvalid_value, 30);
	zend_enum_add_case_cstr(class_entry, "SpawnServiceInvalid", &enum_case_SpawnServiceInvalid_value);

	zval enum_case_SpawnServiceNotFound_value;
	ZVAL_LONG(&enum_case_SpawnServiceNotFound_value, 31);
	zend_enum_add_case_cstr(class_entry, "SpawnServiceNotFound", &enum_case_SpawnServiceNotFound_value);

	zval enum_case_SpawnPermissionsInvalid_value;
	ZVAL_LONG(&enum_case_SpawnPermissionsInvalid_value, 32);
	zend_enum_add_case_cstr(class_entry, "SpawnPermissionsInvalid", &enum_case_SpawnPermissionsInvalid_value);

	zval enum_case_SpawnFileInvalid_value;
	ZVAL_LONG(&enum_case_SpawnFileInvalid_value, 33);
	zend_enum_add_case_cstr(class_entry, "SpawnFileInvalid", &enum_case_SpawnFileInvalid_value);

	zval enum_case_SpawnNoMemory_value;
	ZVAL_LONG(&enum_case_SpawnNoMemory_value, 34);
	zend_enum_add_case_cstr(class_entry, "SpawnNoMemory", &enum_case_SpawnNoMemory_value);

	zval enum_case_UnixProcessIdUnknown_value;
	ZVAL_LONG(&enum_case_UnixProcessIdUnknown_value, 35);
	zend_enum_add_case_cstr(class_entry, "UnixProcessIdUnknown", &enum_case_UnixProcessIdUnknown_value);

	zval enum_case_InvalidSignature_value;
	ZVAL_LONG(&enum_case_InvalidSignature_value, 36);
	zend_enum_add_case_cstr(class_entry, "InvalidSignature", &enum_case_InvalidSignature_value);

	zval enum_case_InvalidFileContent_value;
	ZVAL_LONG(&enum_case_InvalidFileContent_value, 37);
	zend_enum_add_case_cstr(class_entry, "InvalidFileContent", &enum_case_InvalidFileContent_value);

	zval enum_case_SelinuxSecurityContextUnknown_value;
	ZVAL_LONG(&enum_case_SelinuxSecurityContextUnknown_value, 38);
	zend_enum_add_case_cstr(class_entry, "SelinuxSecurityContextUnknown", &enum_case_SelinuxSecurityContextUnknown_value);

	zval enum_case_AdtAuditDataUnknown_value;
	ZVAL_LONG(&enum_case_AdtAuditDataUnknown_value, 39);
	zend_enum_add_case_cstr(class_entry, "AdtAuditDataUnknown", &enum_case_AdtAuditDataUnknown_value);

	zval enum_case_ObjectPathInUse_value;
	ZVAL_LONG(&enum_case_ObjectPathInUse_value, 40);
	zend_enum_add_case_cstr(class_entry, "ObjectPathInUse", &enum_case_ObjectPathInUse_value);

	zval enum_case_UnknownObject_value;
	ZVAL_LONG(&enum_case_UnknownObject_value, 41);
	zend_enum_add_case_cstr(class_entry, "UnknownObject", &enum_case_UnknownObject_value);

	zval enum_case_UnknownInterface_value;
	ZVAL_LONG(&enum_case_UnknownInterface_value, 42);
	zend_enum_add_case_cstr(class_entry, "UnknownInterface", &enum_case_UnknownInterface_value);

	zval enum_case_UnknownProperty_value;
	ZVAL_LONG(&enum_case_UnknownProperty_value, 43);
	zend_enum_add_case_cstr(class_entry, "UnknownProperty", &enum_case_UnknownProperty_value);

	zval enum_case_PropertyReadOnly_value;
	ZVAL_LONG(&enum_case_PropertyReadOnly_value, 44);
	zend_enum_add_case_cstr(class_entry, "PropertyReadOnly", &enum_case_PropertyReadOnly_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusInterfaceInfo(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusInterfaceInfo", class_Gtk4_GDBusInterfaceInfo_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusMethodInfo(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusMethodInfo", class_Gtk4_GDBusMethodInfo_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusMethodInvocation(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusMethodInvocation", class_Gtk4_GDBusMethodInvocation_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusNodeInfo(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusNodeInfo", class_Gtk4_GDBusNodeInfo_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusPropertyInfo(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusPropertyInfo", class_Gtk4_GDBusPropertyInfo_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusPropertyInfoFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusPropertyInfoFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_NONE_value;
	ZVAL_LONG(&const_NONE_value, 0);
	zend_string *const_NONE_name = zend_string_init_interned("NONE", sizeof("NONE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NONE_name, &const_NONE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NONE_name);

	zval const_READABLE_value;
	ZVAL_LONG(&const_READABLE_value, 1);
	zend_string *const_READABLE_name = zend_string_init_interned("READABLE", sizeof("READABLE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_READABLE_name, &const_READABLE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_READABLE_name);

	zval const_WRITABLE_value;
	ZVAL_LONG(&const_WRITABLE_value, 2);
	zend_string *const_WRITABLE_name = zend_string_init_interned("WRITABLE", sizeof("WRITABLE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_WRITABLE_name, &const_WRITABLE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_WRITABLE_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusProxy(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusProxy", class_Gtk4_GDBusProxy_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusProxyFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusProxyFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_NONE_value;
	ZVAL_LONG(&const_NONE_value, 0);
	zend_string *const_NONE_name = zend_string_init_interned("NONE", sizeof("NONE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NONE_name, &const_NONE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NONE_name);

	zval const_DO_NOT_LOAD_PROPERTIES_value;
	ZVAL_LONG(&const_DO_NOT_LOAD_PROPERTIES_value, 1);
	zend_string *const_DO_NOT_LOAD_PROPERTIES_name = zend_string_init_interned("DO_NOT_LOAD_PROPERTIES", sizeof("DO_NOT_LOAD_PROPERTIES") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_DO_NOT_LOAD_PROPERTIES_name, &const_DO_NOT_LOAD_PROPERTIES_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_DO_NOT_LOAD_PROPERTIES_name);

	zval const_DO_NOT_CONNECT_SIGNALS_value;
	ZVAL_LONG(&const_DO_NOT_CONNECT_SIGNALS_value, 2);
	zend_string *const_DO_NOT_CONNECT_SIGNALS_name = zend_string_init_interned("DO_NOT_CONNECT_SIGNALS", sizeof("DO_NOT_CONNECT_SIGNALS") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_DO_NOT_CONNECT_SIGNALS_name, &const_DO_NOT_CONNECT_SIGNALS_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_DO_NOT_CONNECT_SIGNALS_name);

	zval const_DO_NOT_AUTO_START_value;
	ZVAL_LONG(&const_DO_NOT_AUTO_START_value, 4);
	zend_string *const_DO_NOT_AUTO_START_name = zend_string_init_interned("DO_NOT_AUTO_START", sizeof("DO_NOT_AUTO_START") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_DO_NOT_AUTO_START_name, &const_DO_NOT_AUTO_START_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_DO_NOT_AUTO_START_name);

	zval const_GET_INVALIDATED_PROPERTIES_value;
	ZVAL_LONG(&const_GET_INVALIDATED_PROPERTIES_value, 8);
	zend_string *const_GET_INVALIDATED_PROPERTIES_name = zend_string_init_interned("GET_INVALIDATED_PROPERTIES", sizeof("GET_INVALIDATED_PROPERTIES") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_GET_INVALIDATED_PROPERTIES_name, &const_GET_INVALIDATED_PROPERTIES_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_GET_INVALIDATED_PROPERTIES_name);

	zval const_DO_NOT_AUTO_START_AT_CONSTRUCTION_value;
	ZVAL_LONG(&const_DO_NOT_AUTO_START_AT_CONSTRUCTION_value, 16);
	zend_string *const_DO_NOT_AUTO_START_AT_CONSTRUCTION_name = zend_string_init_interned("DO_NOT_AUTO_START_AT_CONSTRUCTION", sizeof("DO_NOT_AUTO_START_AT_CONSTRUCTION") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_DO_NOT_AUTO_START_AT_CONSTRUCTION_name, &const_DO_NOT_AUTO_START_AT_CONSTRUCTION_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_DO_NOT_AUTO_START_AT_CONSTRUCTION_name);

	zval const_NO_MATCH_RULE_value;
	ZVAL_LONG(&const_NO_MATCH_RULE_value, 32);
	zend_string *const_NO_MATCH_RULE_name = zend_string_init_interned("NO_MATCH_RULE", sizeof("NO_MATCH_RULE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NO_MATCH_RULE_name, &const_NO_MATCH_RULE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NO_MATCH_RULE_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusSendMessageFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusSendMessageFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_NONE_value;
	ZVAL_LONG(&const_NONE_value, 0);
	zend_string *const_NONE_name = zend_string_init_interned("NONE", sizeof("NONE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NONE_name, &const_NONE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NONE_name);

	zval const_PRESERVE_SERIAL_value;
	ZVAL_LONG(&const_PRESERVE_SERIAL_value, 1);
	zend_string *const_PRESERVE_SERIAL_name = zend_string_init_interned("PRESERVE_SERIAL", sizeof("PRESERVE_SERIAL") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_PRESERVE_SERIAL_name, &const_PRESERVE_SERIAL_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_PRESERVE_SERIAL_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusSignalFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusSignalFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_NONE_value;
	ZVAL_LONG(&const_NONE_value, 0);
	zend_string *const_NONE_name = zend_string_init_interned("NONE", sizeof("NONE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NONE_name, &const_NONE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NONE_name);

	zval const_NO_MATCH_RULE_value;
	ZVAL_LONG(&const_NO_MATCH_RULE_value, 1);
	zend_string *const_NO_MATCH_RULE_name = zend_string_init_interned("NO_MATCH_RULE", sizeof("NO_MATCH_RULE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NO_MATCH_RULE_name, &const_NO_MATCH_RULE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NO_MATCH_RULE_name);

	zval const_MATCH_ARG0_NAMESPACE_value;
	ZVAL_LONG(&const_MATCH_ARG0_NAMESPACE_value, 2);
	zend_string *const_MATCH_ARG0_NAMESPACE_name = zend_string_init_interned("MATCH_ARG0_NAMESPACE", sizeof("MATCH_ARG0_NAMESPACE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_MATCH_ARG0_NAMESPACE_name, &const_MATCH_ARG0_NAMESPACE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_MATCH_ARG0_NAMESPACE_name);

	zval const_MATCH_ARG0_PATH_value;
	ZVAL_LONG(&const_MATCH_ARG0_PATH_value, 4);
	zend_string *const_MATCH_ARG0_PATH_name = zend_string_init_interned("MATCH_ARG0_PATH", sizeof("MATCH_ARG0_PATH") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_MATCH_ARG0_PATH_name, &const_MATCH_ARG0_PATH_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_MATCH_ARG0_PATH_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusSignalInfo(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusSignalInfo", class_Gtk4_GDBusSignalInfo_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GDBusSubtreeFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDBusSubtreeFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_NONE_value;
	ZVAL_LONG(&const_NONE_value, 0);
	zend_string *const_NONE_name = zend_string_init_interned("NONE", sizeof("NONE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NONE_name, &const_NONE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NONE_name);

	zval const_DISPATCH_TO_UNENUMERATED_NODES_value;
	ZVAL_LONG(&const_DISPATCH_TO_UNENUMERATED_NODES_value, 1);
	zend_string *const_DISPATCH_TO_UNENUMERATED_NODES_name = zend_string_init_interned("DISPATCH_TO_UNENUMERATED_NODES", sizeof("DISPATCH_TO_UNENUMERATED_NODES") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_DISPATCH_TO_UNENUMERATED_NODES_name, &const_DISPATCH_TO_UNENUMERATED_NODES_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_DISPATCH_TO_UNENUMERATED_NODES_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GIcon(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GIcon", class_Gtk4_GIcon_methods);
	class_entry = zend_register_internal_interface(&ce);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GIconObject(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GIcon)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GIconObject", class_Gtk4_GIconObject_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GIcon);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GInputStream(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GInputStream", class_Gtk4_GInputStream_methods);
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

static zend_class_entry *register_class_Gtk4_GMemoryInputStream(zend_class_entry *class_entry_Gtk4_GInputStream)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GMemoryInputStream", class_Gtk4_GMemoryInputStream_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GInputStream, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GMemoryOutputStream(zend_class_entry *class_entry_Gtk4_GOutputStream)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GMemoryOutputStream", class_Gtk4_GMemoryOutputStream_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GOutputStream, 0);

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

static zend_class_entry *register_class_Gtk4_GNotification(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GNotification", class_Gtk4_GNotification_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GNotificationPriority(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\GNotificationPriority", IS_LONG, NULL);

	zval enum_case_Normal_value;
	ZVAL_LONG(&enum_case_Normal_value, 0);
	zend_enum_add_case_cstr(class_entry, "Normal", &enum_case_Normal_value);

	zval enum_case_Low_value;
	ZVAL_LONG(&enum_case_Low_value, 1);
	zend_enum_add_case_cstr(class_entry, "Low", &enum_case_Low_value);

	zval enum_case_High_value;
	ZVAL_LONG(&enum_case_High_value, 2);
	zend_enum_add_case_cstr(class_entry, "High", &enum_case_High_value);

	zval enum_case_Urgent_value;
	ZVAL_LONG(&enum_case_Urgent_value, 3);
	zend_enum_add_case_cstr(class_entry, "Urgent", &enum_case_Urgent_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GOutputStream(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GOutputStream", class_Gtk4_GOutputStream_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GOutputStreamSpliceFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GOutputStreamSpliceFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_NONE_value;
	ZVAL_LONG(&const_NONE_value, 0);
	zend_string *const_NONE_name = zend_string_init_interned("NONE", sizeof("NONE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NONE_name, &const_NONE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NONE_name);

	zval const_CLOSE_SOURCE_value;
	ZVAL_LONG(&const_CLOSE_SOURCE_value, 1);
	zend_string *const_CLOSE_SOURCE_name = zend_string_init_interned("CLOSE_SOURCE", sizeof("CLOSE_SOURCE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_CLOSE_SOURCE_name, &const_CLOSE_SOURCE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_CLOSE_SOURCE_name);

	zval const_CLOSE_TARGET_value;
	ZVAL_LONG(&const_CLOSE_TARGET_value, 2);
	zend_string *const_CLOSE_TARGET_name = zend_string_init_interned("CLOSE_TARGET", sizeof("CLOSE_TARGET") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_CLOSE_TARGET_name, &const_CLOSE_TARGET_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_CLOSE_TARGET_name);

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

static zend_class_entry *register_class_Gtk4_GTestDBus(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GTestDBus", class_Gtk4_GTestDBus_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GTestDBusFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GTestDBusFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_NONE_value;
	ZVAL_LONG(&const_NONE_value, 0);
	zend_string *const_NONE_name = zend_string_init_interned("NONE", sizeof("NONE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NONE_name, &const_NONE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NONE_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GThemedIcon(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GIcon)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GThemedIcon", class_Gtk4_GThemedIcon_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GIcon);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GTlsCertificate(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GTlsCertificate", class_Gtk4_GTlsCertificate_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GTlsCertificateFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GTlsCertificateFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_NO_FLAGS_value;
	ZVAL_LONG(&const_NO_FLAGS_value, 0);
	zend_string *const_NO_FLAGS_name = zend_string_init_interned("NO_FLAGS", sizeof("NO_FLAGS") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NO_FLAGS_name, &const_NO_FLAGS_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NO_FLAGS_name);

	zval const_UNKNOWN_CA_value;
	ZVAL_LONG(&const_UNKNOWN_CA_value, 1);
	zend_string *const_UNKNOWN_CA_name = zend_string_init_interned("UNKNOWN_CA", sizeof("UNKNOWN_CA") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_UNKNOWN_CA_name, &const_UNKNOWN_CA_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_UNKNOWN_CA_name);

	zval const_BAD_IDENTITY_value;
	ZVAL_LONG(&const_BAD_IDENTITY_value, 2);
	zend_string *const_BAD_IDENTITY_name = zend_string_init_interned("BAD_IDENTITY", sizeof("BAD_IDENTITY") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_BAD_IDENTITY_name, &const_BAD_IDENTITY_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_BAD_IDENTITY_name);

	zval const_NOT_ACTIVATED_value;
	ZVAL_LONG(&const_NOT_ACTIVATED_value, 4);
	zend_string *const_NOT_ACTIVATED_name = zend_string_init_interned("NOT_ACTIVATED", sizeof("NOT_ACTIVATED") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NOT_ACTIVATED_name, &const_NOT_ACTIVATED_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NOT_ACTIVATED_name);

	zval const_EXPIRED_value;
	ZVAL_LONG(&const_EXPIRED_value, 8);
	zend_string *const_EXPIRED_name = zend_string_init_interned("EXPIRED", sizeof("EXPIRED") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_EXPIRED_name, &const_EXPIRED_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_EXPIRED_name);

	zval const_REVOKED_value;
	ZVAL_LONG(&const_REVOKED_value, 16);
	zend_string *const_REVOKED_name = zend_string_init_interned("REVOKED", sizeof("REVOKED") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_REVOKED_name, &const_REVOKED_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_REVOKED_name);

	zval const_INSECURE_value;
	ZVAL_LONG(&const_INSECURE_value, 32);
	zend_string *const_INSECURE_name = zend_string_init_interned("INSECURE", sizeof("INSECURE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_INSECURE_name, &const_INSECURE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_INSECURE_name);

	zval const_GENERIC_ERROR_value;
	ZVAL_LONG(&const_GENERIC_ERROR_value, 64);
	zend_string *const_GENERIC_ERROR_name = zend_string_init_interned("GENERIC_ERROR", sizeof("GENERIC_ERROR") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_GENERIC_ERROR_name, &const_GENERIC_ERROR_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_GENERIC_ERROR_name);

	zval const_VALIDATE_ALL_value;
	ZVAL_LONG(&const_VALIDATE_ALL_value, 127);
	zend_string *const_VALIDATE_ALL_name = zend_string_init_interned("VALIDATE_ALL", sizeof("VALIDATE_ALL") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_VALIDATE_ALL_name, &const_VALIDATE_ALL_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_VALIDATE_ALL_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GTlsPasswordFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GTlsPasswordFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_NONE_value;
	ZVAL_LONG(&const_NONE_value, 0);
	zend_string *const_NONE_name = zend_string_init_interned("NONE", sizeof("NONE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_NONE_name, &const_NONE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_NONE_name);

	zval const_RETRY_value;
	ZVAL_LONG(&const_RETRY_value, 2);
	zend_string *const_RETRY_name = zend_string_init_interned("RETRY", sizeof("RETRY") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_RETRY_name, &const_RETRY_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_RETRY_name);

	zval const_MANY_TRIES_value;
	ZVAL_LONG(&const_MANY_TRIES_value, 4);
	zend_string *const_MANY_TRIES_name = zend_string_init_interned("MANY_TRIES", sizeof("MANY_TRIES") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_MANY_TRIES_name, &const_MANY_TRIES_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_MANY_TRIES_name);

	zval const_FINAL_TRY_value;
	ZVAL_LONG(&const_FINAL_TRY_value, 8);
	zend_string *const_FINAL_TRY_name = zend_string_init_interned("FINAL_TRY", sizeof("FINAL_TRY") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_FINAL_TRY_name, &const_FINAL_TRY_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_FINAL_TRY_name);

	zval const_PKCS11_USER_value;
	ZVAL_LONG(&const_PKCS11_USER_value, 16);
	zend_string *const_PKCS11_USER_name = zend_string_init_interned("PKCS11_USER", sizeof("PKCS11_USER") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_PKCS11_USER_name, &const_PKCS11_USER_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_PKCS11_USER_name);

	zval const_PKCS11_SECURITY_OFFICER_value;
	ZVAL_LONG(&const_PKCS11_SECURITY_OFFICER_value, 32);
	zend_string *const_PKCS11_SECURITY_OFFICER_name = zend_string_init_interned("PKCS11_SECURITY_OFFICER", sizeof("PKCS11_SECURITY_OFFICER") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_PKCS11_SECURITY_OFFICER_name, &const_PKCS11_SECURITY_OFFICER_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_PKCS11_SECURITY_OFFICER_name);

	zval const_PKCS11_CONTEXT_SPECIFIC_value;
	ZVAL_LONG(&const_PKCS11_CONTEXT_SPECIFIC_value, 64);
	zend_string *const_PKCS11_CONTEXT_SPECIFIC_name = zend_string_init_interned("PKCS11_CONTEXT_SPECIFIC", sizeof("PKCS11_CONTEXT_SPECIFIC") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_PKCS11_CONTEXT_SPECIFIC_name, &const_PKCS11_CONTEXT_SPECIFIC_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_PKCS11_CONTEXT_SPECIFIC_name);

	return class_entry;
}
