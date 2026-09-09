/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: bd64cb96b8be52a4baf7e69cc76916601d843848 */

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_JSCContext___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCContext_new_with_virtual_machine, 0, 1, Gtk4\\JSCContext, 0)
	ZEND_ARG_OBJ_INFO(0, vm, Gtk4\\JSCVirtualMachine, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCContext_get_current, 0, 0, Gtk4\\JSCContext, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCContext_clear_exception, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCContext_evaluate, 0, 2, Gtk4\\JSCValue, 0)
	ZEND_ARG_TYPE_INFO(0, code, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, length, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCContext_evaluate_with_source_uri, 0, 4, Gtk4\\JSCValue, 0)
	ZEND_ARG_TYPE_INFO(0, code, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, length, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, uri, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, line_number, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCContext_get_exception, 0, 0, Gtk4\\JSCException, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCContext_get_global_object, 0, 0, Gtk4\\JSCValue, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCContext_get_value, 0, 1, Gtk4\\JSCValue, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCContext_get_virtual_machine, 0, 0, Gtk4\\JSCVirtualMachine, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_JSCContext_pop_exception_handler arginfo_class_Gtk4_JSCContext_clear_exception

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCContext_set_value, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO(0, value, Gtk4\\JSCValue, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCContext_throw, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, error_message, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCContext_throw_exception, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, exception, Gtk4\\JSCException, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCContext_throw_with_name, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, error_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, error_message, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_JSCException___construct, 0, 0, 2)
	ZEND_ARG_OBJ_INFO(0, context, Gtk4\\JSCContext, 0)
	ZEND_ARG_TYPE_INFO(0, message, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCException_new_with_name, 0, 3, Gtk4\\JSCException, 0)
	ZEND_ARG_OBJ_INFO(0, context, Gtk4\\JSCContext, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, message, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCException_get_backtrace_string, 0, 0, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCException_get_column_number, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_JSCException_get_line_number arginfo_class_Gtk4_JSCException_get_column_number

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCException_get_message, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_JSCException_get_name arginfo_class_Gtk4_JSCException_get_message

#define arginfo_class_Gtk4_JSCException_get_source_uri arginfo_class_Gtk4_JSCException_get_backtrace_string

#define arginfo_class_Gtk4_JSCException_report arginfo_class_Gtk4_JSCException_get_message

#define arginfo_class_Gtk4_JSCException_to_string arginfo_class_Gtk4_JSCException_get_message

#define arginfo_class_Gtk4_JSCValue___construct arginfo_class_Gtk4_JSCContext___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCValue_new_array_from_strv, 0, 2, Gtk4\\JSCValue, 0)
	ZEND_ARG_OBJ_INFO(0, context, Gtk4\\JSCContext, 0)
	ZEND_ARG_TYPE_INFO(0, strv, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCValue_new_boolean, 0, 2, Gtk4\\JSCValue, 0)
	ZEND_ARG_OBJ_INFO(0, context, Gtk4\\JSCContext, 0)
	ZEND_ARG_TYPE_INFO(0, value, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCValue_new_from_json, 0, 2, Gtk4\\JSCValue, 0)
	ZEND_ARG_OBJ_INFO(0, context, Gtk4\\JSCContext, 0)
	ZEND_ARG_TYPE_INFO(0, json, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCValue_new_null, 0, 1, Gtk4\\JSCValue, 0)
	ZEND_ARG_OBJ_INFO(0, context, Gtk4\\JSCContext, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCValue_new_number, 0, 2, Gtk4\\JSCValue, 0)
	ZEND_ARG_OBJ_INFO(0, context, Gtk4\\JSCContext, 0)
	ZEND_ARG_TYPE_INFO(0, number, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCValue_new_promise, 0, 2, Gtk4\\JSCValue, 0)
	ZEND_ARG_OBJ_INFO(0, context, Gtk4\\JSCContext, 0)
	ZEND_ARG_TYPE_INFO(0, executor, IS_CALLABLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCValue_new_string, 0, 1, Gtk4\\JSCValue, 0)
	ZEND_ARG_OBJ_INFO(0, context, Gtk4\\JSCContext, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, string, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCValue_new_string_from_bytes, 0, 1, Gtk4\\JSCValue, 0)
	ZEND_ARG_OBJ_INFO(0, context, Gtk4\\JSCContext, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, bytes, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_JSCValue_new_undefined arginfo_class_Gtk4_JSCValue_new_null

#define arginfo_class_Gtk4_JSCValue_array_buffer_get_data arginfo_class_Gtk4_JSCException_get_message

#define arginfo_class_Gtk4_JSCValue_array_buffer_get_size arginfo_class_Gtk4_JSCException_get_column_number

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCValue_get_context, 0, 0, Gtk4\\JSCContext, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCValue_is_array, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_JSCValue_is_array_buffer arginfo_class_Gtk4_JSCValue_is_array

#define arginfo_class_Gtk4_JSCValue_is_boolean arginfo_class_Gtk4_JSCValue_is_array

#define arginfo_class_Gtk4_JSCValue_is_constructor arginfo_class_Gtk4_JSCValue_is_array

#define arginfo_class_Gtk4_JSCValue_is_function arginfo_class_Gtk4_JSCValue_is_array

#define arginfo_class_Gtk4_JSCValue_is_null arginfo_class_Gtk4_JSCValue_is_array

#define arginfo_class_Gtk4_JSCValue_is_number arginfo_class_Gtk4_JSCValue_is_array

#define arginfo_class_Gtk4_JSCValue_is_object arginfo_class_Gtk4_JSCValue_is_array

#define arginfo_class_Gtk4_JSCValue_is_string arginfo_class_Gtk4_JSCValue_is_array

#define arginfo_class_Gtk4_JSCValue_is_typed_array arginfo_class_Gtk4_JSCValue_is_array

#define arginfo_class_Gtk4_JSCValue_is_undefined arginfo_class_Gtk4_JSCValue_is_array

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCValue_object_define_property_data, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, property_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, property_value, Gtk4\\JSCValue, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCValue_object_delete_property, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCValue_object_enumerate_properties, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_JSCValue_object_get_property arginfo_class_Gtk4_JSCContext_get_value

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCValue_object_get_property_at_index, 0, 1, Gtk4\\JSCValue, 0)
	ZEND_ARG_TYPE_INFO(0, index, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_JSCValue_object_has_property arginfo_class_Gtk4_JSCValue_object_delete_property

#define arginfo_class_Gtk4_JSCValue_object_is_instance_of arginfo_class_Gtk4_JSCValue_object_delete_property

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCValue_object_set_property, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO(0, property, Gtk4\\JSCValue, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCValue_object_set_property_at_index, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, index, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, property, Gtk4\\JSCValue, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_JSCValue_to_boolean arginfo_class_Gtk4_JSCValue_is_array

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCValue_to_double, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_JSCValue_to_int32 arginfo_class_Gtk4_JSCException_get_column_number

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_JSCValue_to_json, 0, 1, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, indent, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_JSCValue_to_string arginfo_class_Gtk4_JSCException_get_message

#define arginfo_class_Gtk4_JSCValue_to_string_as_bytes arginfo_class_Gtk4_JSCException_get_message

#define arginfo_class_Gtk4_JSCValue_typed_array_get_buffer arginfo_class_Gtk4_JSCContext_get_global_object

#define arginfo_class_Gtk4_JSCValue_typed_array_get_length arginfo_class_Gtk4_JSCException_get_column_number

#define arginfo_class_Gtk4_JSCValue_typed_array_get_offset arginfo_class_Gtk4_JSCException_get_column_number

#define arginfo_class_Gtk4_JSCValue_typed_array_get_size arginfo_class_Gtk4_JSCException_get_column_number

#define arginfo_class_Gtk4_JSCValue_typed_array_get_type arginfo_class_Gtk4_JSCException_get_column_number

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCValue_constructor_call, 0, 0, Gtk4\\JSCValue, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameters, IS_ARRAY, 0, "[]")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_JSCValue_function_call arginfo_class_Gtk4_JSCValue_constructor_call

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_JSCValue_object_invoke_method, 0, 1, Gtk4\\JSCValue, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, parameters, IS_ARRAY, 0, "[]")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_JSCVirtualMachine___construct arginfo_class_Gtk4_JSCContext___construct

ZEND_METHOD(Gtk4_JSCContext, __construct);
ZEND_METHOD(Gtk4_JSCContext, new_with_virtual_machine);
ZEND_METHOD(Gtk4_JSCContext, get_current);
ZEND_METHOD(Gtk4_JSCContext, clear_exception);
ZEND_METHOD(Gtk4_JSCContext, evaluate);
ZEND_METHOD(Gtk4_JSCContext, evaluate_with_source_uri);
ZEND_METHOD(Gtk4_JSCContext, get_exception);
ZEND_METHOD(Gtk4_JSCContext, get_global_object);
ZEND_METHOD(Gtk4_JSCContext, get_value);
ZEND_METHOD(Gtk4_JSCContext, get_virtual_machine);
ZEND_METHOD(Gtk4_JSCContext, pop_exception_handler);
ZEND_METHOD(Gtk4_JSCContext, set_value);
ZEND_METHOD(Gtk4_JSCContext, throw);
ZEND_METHOD(Gtk4_JSCContext, throw_exception);
ZEND_METHOD(Gtk4_JSCContext, throw_with_name);
ZEND_METHOD(Gtk4_JSCException, __construct);
ZEND_METHOD(Gtk4_JSCException, new_with_name);
ZEND_METHOD(Gtk4_JSCException, get_backtrace_string);
ZEND_METHOD(Gtk4_JSCException, get_column_number);
ZEND_METHOD(Gtk4_JSCException, get_line_number);
ZEND_METHOD(Gtk4_JSCException, get_message);
ZEND_METHOD(Gtk4_JSCException, get_name);
ZEND_METHOD(Gtk4_JSCException, get_source_uri);
ZEND_METHOD(Gtk4_JSCException, report);
ZEND_METHOD(Gtk4_JSCException, to_string);
ZEND_METHOD(Gtk4_JSCValue, __construct);
ZEND_METHOD(Gtk4_JSCValue, new_array_from_strv);
ZEND_METHOD(Gtk4_JSCValue, new_boolean);
ZEND_METHOD(Gtk4_JSCValue, new_from_json);
ZEND_METHOD(Gtk4_JSCValue, new_null);
ZEND_METHOD(Gtk4_JSCValue, new_number);
ZEND_METHOD(Gtk4_JSCValue, new_promise);
ZEND_METHOD(Gtk4_JSCValue, new_string);
ZEND_METHOD(Gtk4_JSCValue, new_string_from_bytes);
ZEND_METHOD(Gtk4_JSCValue, new_undefined);
ZEND_METHOD(Gtk4_JSCValue, array_buffer_get_data);
ZEND_METHOD(Gtk4_JSCValue, array_buffer_get_size);
ZEND_METHOD(Gtk4_JSCValue, get_context);
ZEND_METHOD(Gtk4_JSCValue, is_array);
ZEND_METHOD(Gtk4_JSCValue, is_array_buffer);
ZEND_METHOD(Gtk4_JSCValue, is_boolean);
ZEND_METHOD(Gtk4_JSCValue, is_constructor);
ZEND_METHOD(Gtk4_JSCValue, is_function);
ZEND_METHOD(Gtk4_JSCValue, is_null);
ZEND_METHOD(Gtk4_JSCValue, is_number);
ZEND_METHOD(Gtk4_JSCValue, is_object);
ZEND_METHOD(Gtk4_JSCValue, is_string);
ZEND_METHOD(Gtk4_JSCValue, is_typed_array);
ZEND_METHOD(Gtk4_JSCValue, is_undefined);
ZEND_METHOD(Gtk4_JSCValue, object_define_property_data);
ZEND_METHOD(Gtk4_JSCValue, object_delete_property);
ZEND_METHOD(Gtk4_JSCValue, object_enumerate_properties);
ZEND_METHOD(Gtk4_JSCValue, object_get_property);
ZEND_METHOD(Gtk4_JSCValue, object_get_property_at_index);
ZEND_METHOD(Gtk4_JSCValue, object_has_property);
ZEND_METHOD(Gtk4_JSCValue, object_is_instance_of);
ZEND_METHOD(Gtk4_JSCValue, object_set_property);
ZEND_METHOD(Gtk4_JSCValue, object_set_property_at_index);
ZEND_METHOD(Gtk4_JSCValue, to_boolean);
ZEND_METHOD(Gtk4_JSCValue, to_double);
ZEND_METHOD(Gtk4_JSCValue, to_int32);
ZEND_METHOD(Gtk4_JSCValue, to_json);
ZEND_METHOD(Gtk4_JSCValue, to_string);
ZEND_METHOD(Gtk4_JSCValue, to_string_as_bytes);
ZEND_METHOD(Gtk4_JSCValue, typed_array_get_buffer);
ZEND_METHOD(Gtk4_JSCValue, typed_array_get_length);
ZEND_METHOD(Gtk4_JSCValue, typed_array_get_offset);
ZEND_METHOD(Gtk4_JSCValue, typed_array_get_size);
ZEND_METHOD(Gtk4_JSCValue, typed_array_get_type);
ZEND_METHOD(Gtk4_JSCValue, constructor_call);
ZEND_METHOD(Gtk4_JSCValue, function_call);
ZEND_METHOD(Gtk4_JSCValue, object_invoke_method);
ZEND_METHOD(Gtk4_JSCVirtualMachine, __construct);

static const zend_function_entry class_Gtk4_JSCContext_methods[] = {
	ZEND_ME(Gtk4_JSCContext, __construct, arginfo_class_Gtk4_JSCContext___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCContext, new_with_virtual_machine, arginfo_class_Gtk4_JSCContext_new_with_virtual_machine, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_JSCContext, get_current, arginfo_class_Gtk4_JSCContext_get_current, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_JSCContext, clear_exception, arginfo_class_Gtk4_JSCContext_clear_exception, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCContext, evaluate, arginfo_class_Gtk4_JSCContext_evaluate, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCContext, evaluate_with_source_uri, arginfo_class_Gtk4_JSCContext_evaluate_with_source_uri, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCContext, get_exception, arginfo_class_Gtk4_JSCContext_get_exception, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCContext, get_global_object, arginfo_class_Gtk4_JSCContext_get_global_object, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCContext, get_value, arginfo_class_Gtk4_JSCContext_get_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCContext, get_virtual_machine, arginfo_class_Gtk4_JSCContext_get_virtual_machine, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCContext, pop_exception_handler, arginfo_class_Gtk4_JSCContext_pop_exception_handler, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCContext, set_value, arginfo_class_Gtk4_JSCContext_set_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCContext, throw, arginfo_class_Gtk4_JSCContext_throw, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCContext, throw_exception, arginfo_class_Gtk4_JSCContext_throw_exception, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCContext, throw_with_name, arginfo_class_Gtk4_JSCContext_throw_with_name, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_JSCException_methods[] = {
	ZEND_ME(Gtk4_JSCException, __construct, arginfo_class_Gtk4_JSCException___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCException, new_with_name, arginfo_class_Gtk4_JSCException_new_with_name, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_JSCException, get_backtrace_string, arginfo_class_Gtk4_JSCException_get_backtrace_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCException, get_column_number, arginfo_class_Gtk4_JSCException_get_column_number, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCException, get_line_number, arginfo_class_Gtk4_JSCException_get_line_number, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCException, get_message, arginfo_class_Gtk4_JSCException_get_message, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCException, get_name, arginfo_class_Gtk4_JSCException_get_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCException, get_source_uri, arginfo_class_Gtk4_JSCException_get_source_uri, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCException, report, arginfo_class_Gtk4_JSCException_report, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCException, to_string, arginfo_class_Gtk4_JSCException_to_string, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_JSCValue_methods[] = {
	ZEND_ME(Gtk4_JSCValue, __construct, arginfo_class_Gtk4_JSCValue___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_JSCValue, new_array_from_strv, arginfo_class_Gtk4_JSCValue_new_array_from_strv, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_JSCValue, new_boolean, arginfo_class_Gtk4_JSCValue_new_boolean, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_JSCValue, new_from_json, arginfo_class_Gtk4_JSCValue_new_from_json, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_JSCValue, new_null, arginfo_class_Gtk4_JSCValue_new_null, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_JSCValue, new_number, arginfo_class_Gtk4_JSCValue_new_number, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_JSCValue, new_promise, arginfo_class_Gtk4_JSCValue_new_promise, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_JSCValue, new_string, arginfo_class_Gtk4_JSCValue_new_string, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_JSCValue, new_string_from_bytes, arginfo_class_Gtk4_JSCValue_new_string_from_bytes, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_JSCValue, new_undefined, arginfo_class_Gtk4_JSCValue_new_undefined, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_JSCValue, array_buffer_get_data, arginfo_class_Gtk4_JSCValue_array_buffer_get_data, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, array_buffer_get_size, arginfo_class_Gtk4_JSCValue_array_buffer_get_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, get_context, arginfo_class_Gtk4_JSCValue_get_context, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, is_array, arginfo_class_Gtk4_JSCValue_is_array, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, is_array_buffer, arginfo_class_Gtk4_JSCValue_is_array_buffer, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, is_boolean, arginfo_class_Gtk4_JSCValue_is_boolean, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, is_constructor, arginfo_class_Gtk4_JSCValue_is_constructor, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, is_function, arginfo_class_Gtk4_JSCValue_is_function, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, is_null, arginfo_class_Gtk4_JSCValue_is_null, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, is_number, arginfo_class_Gtk4_JSCValue_is_number, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, is_object, arginfo_class_Gtk4_JSCValue_is_object, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, is_string, arginfo_class_Gtk4_JSCValue_is_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, is_typed_array, arginfo_class_Gtk4_JSCValue_is_typed_array, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, is_undefined, arginfo_class_Gtk4_JSCValue_is_undefined, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, object_define_property_data, arginfo_class_Gtk4_JSCValue_object_define_property_data, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, object_delete_property, arginfo_class_Gtk4_JSCValue_object_delete_property, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, object_enumerate_properties, arginfo_class_Gtk4_JSCValue_object_enumerate_properties, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, object_get_property, arginfo_class_Gtk4_JSCValue_object_get_property, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, object_get_property_at_index, arginfo_class_Gtk4_JSCValue_object_get_property_at_index, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, object_has_property, arginfo_class_Gtk4_JSCValue_object_has_property, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, object_is_instance_of, arginfo_class_Gtk4_JSCValue_object_is_instance_of, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, object_set_property, arginfo_class_Gtk4_JSCValue_object_set_property, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, object_set_property_at_index, arginfo_class_Gtk4_JSCValue_object_set_property_at_index, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, to_boolean, arginfo_class_Gtk4_JSCValue_to_boolean, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, to_double, arginfo_class_Gtk4_JSCValue_to_double, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, to_int32, arginfo_class_Gtk4_JSCValue_to_int32, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, to_json, arginfo_class_Gtk4_JSCValue_to_json, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, to_string, arginfo_class_Gtk4_JSCValue_to_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, to_string_as_bytes, arginfo_class_Gtk4_JSCValue_to_string_as_bytes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, typed_array_get_buffer, arginfo_class_Gtk4_JSCValue_typed_array_get_buffer, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, typed_array_get_length, arginfo_class_Gtk4_JSCValue_typed_array_get_length, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, typed_array_get_offset, arginfo_class_Gtk4_JSCValue_typed_array_get_offset, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, typed_array_get_size, arginfo_class_Gtk4_JSCValue_typed_array_get_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, typed_array_get_type, arginfo_class_Gtk4_JSCValue_typed_array_get_type, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, constructor_call, arginfo_class_Gtk4_JSCValue_constructor_call, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, function_call, arginfo_class_Gtk4_JSCValue_function_call, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_JSCValue, object_invoke_method, arginfo_class_Gtk4_JSCValue_object_invoke_method, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_JSCVirtualMachine_methods[] = {
	ZEND_ME(Gtk4_JSCVirtualMachine, __construct, arginfo_class_Gtk4_JSCVirtualMachine___construct, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static zend_class_entry *register_class_Gtk4_JSCContext(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "JSCContext", class_Gtk4_JSCContext_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_FINAL);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_JSCException(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "JSCException", class_Gtk4_JSCException_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_FINAL);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_JSCValue(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "JSCValue", class_Gtk4_JSCValue_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_FINAL);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_JSCVirtualMachine(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "JSCVirtualMachine", class_Gtk4_JSCVirtualMachine_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, ZEND_ACC_FINAL);

	return class_entry;
}
