/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 6ec812837a9964d016d2b77deb2c4b58c2cb9593 */

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_SoupCookie___construct, 0, 0, 5)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, value, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, domain, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, path, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, max_age, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupCookie_domain_matches, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, host, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupCookie_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, cookie2, Gtk4\\SoupCookie, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupCookie_get_domain, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupCookie_get_http_only, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_SoupCookie_get_name arginfo_class_Gtk4_SoupCookie_get_domain

#define arginfo_class_Gtk4_SoupCookie_get_path arginfo_class_Gtk4_SoupCookie_get_domain

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_SoupCookie_get_same_site_policy, 0, 0, Gtk4\\SoupSameSitePolicy, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_SoupCookie_get_secure arginfo_class_Gtk4_SoupCookie_get_http_only

#define arginfo_class_Gtk4_SoupCookie_get_value arginfo_class_Gtk4_SoupCookie_get_domain

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupCookie_set_domain, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, domain, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupCookie_set_http_only, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, http_only, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupCookie_set_max_age, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, max_age, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupCookie_set_name, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupCookie_set_path, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, path, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupCookie_set_same_site_policy, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, policy, Gtk4\\SoupSameSitePolicy, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupCookie_set_secure, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, secure, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupCookie_set_value, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, value, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_SoupCookie_to_cookie_header arginfo_class_Gtk4_SoupCookie_get_domain

#define arginfo_class_Gtk4_SoupCookie_to_set_cookie_header arginfo_class_Gtk4_SoupCookie_get_domain

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders___construct, 0, 0, 1)
	ZEND_ARG_OBJ_INFO(0, type, Gtk4\\SoupMessageHeadersType, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_append, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, value, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_clean_connection_headers, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_SoupMessageHeaders_clear arginfo_class_Gtk4_SoupMessageHeaders_clean_connection_headers

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_foreach, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, func, IS_CALLABLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_get_content_length, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_get_content_range, 0, 0, IS_ARRAY, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_get_encoding, 0, 0, Gtk4\\SoupEncoding, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_SoupMessageHeaders_get_expectations arginfo_class_Gtk4_SoupMessageHeaders_get_content_length

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_get_headers_type, 0, 0, Gtk4\\SoupMessageHeadersType, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_get_list, 0, 1, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_SoupMessageHeaders_get_one arginfo_class_Gtk4_SoupMessageHeaders_get_list

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_header_contains, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, token, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_header_equals, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, value, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_SoupMessageHeaders_remove arginfo_class_Gtk4_SoupCookie_set_name

#define arginfo_class_Gtk4_SoupMessageHeaders_replace arginfo_class_Gtk4_SoupMessageHeaders_append

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_set_content_length, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, content_length, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_set_content_range, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, start, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, end, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, total_length, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_set_encoding, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, encoding, Gtk4\\SoupEncoding, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_set_expectations, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, expectations, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_SoupMessageHeaders_set_range, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, start, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, end, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_METHOD(Gtk4_SoupCookie, __construct);
ZEND_METHOD(Gtk4_SoupCookie, domain_matches);
ZEND_METHOD(Gtk4_SoupCookie, equal);
ZEND_METHOD(Gtk4_SoupCookie, get_domain);
ZEND_METHOD(Gtk4_SoupCookie, get_http_only);
ZEND_METHOD(Gtk4_SoupCookie, get_name);
ZEND_METHOD(Gtk4_SoupCookie, get_path);
ZEND_METHOD(Gtk4_SoupCookie, get_same_site_policy);
ZEND_METHOD(Gtk4_SoupCookie, get_secure);
ZEND_METHOD(Gtk4_SoupCookie, get_value);
ZEND_METHOD(Gtk4_SoupCookie, set_domain);
ZEND_METHOD(Gtk4_SoupCookie, set_http_only);
ZEND_METHOD(Gtk4_SoupCookie, set_max_age);
ZEND_METHOD(Gtk4_SoupCookie, set_name);
ZEND_METHOD(Gtk4_SoupCookie, set_path);
ZEND_METHOD(Gtk4_SoupCookie, set_same_site_policy);
ZEND_METHOD(Gtk4_SoupCookie, set_secure);
ZEND_METHOD(Gtk4_SoupCookie, set_value);
ZEND_METHOD(Gtk4_SoupCookie, to_cookie_header);
ZEND_METHOD(Gtk4_SoupCookie, to_set_cookie_header);
ZEND_METHOD(Gtk4_SoupMessageHeaders, __construct);
ZEND_METHOD(Gtk4_SoupMessageHeaders, append);
ZEND_METHOD(Gtk4_SoupMessageHeaders, clean_connection_headers);
ZEND_METHOD(Gtk4_SoupMessageHeaders, clear);
ZEND_METHOD(Gtk4_SoupMessageHeaders, foreach);
ZEND_METHOD(Gtk4_SoupMessageHeaders, get_content_length);
ZEND_METHOD(Gtk4_SoupMessageHeaders, get_content_range);
ZEND_METHOD(Gtk4_SoupMessageHeaders, get_encoding);
ZEND_METHOD(Gtk4_SoupMessageHeaders, get_expectations);
ZEND_METHOD(Gtk4_SoupMessageHeaders, get_headers_type);
ZEND_METHOD(Gtk4_SoupMessageHeaders, get_list);
ZEND_METHOD(Gtk4_SoupMessageHeaders, get_one);
ZEND_METHOD(Gtk4_SoupMessageHeaders, header_contains);
ZEND_METHOD(Gtk4_SoupMessageHeaders, header_equals);
ZEND_METHOD(Gtk4_SoupMessageHeaders, remove);
ZEND_METHOD(Gtk4_SoupMessageHeaders, replace);
ZEND_METHOD(Gtk4_SoupMessageHeaders, set_content_length);
ZEND_METHOD(Gtk4_SoupMessageHeaders, set_content_range);
ZEND_METHOD(Gtk4_SoupMessageHeaders, set_encoding);
ZEND_METHOD(Gtk4_SoupMessageHeaders, set_expectations);
ZEND_METHOD(Gtk4_SoupMessageHeaders, set_range);

static const zend_function_entry class_Gtk4_SoupCookie_methods[] = {
	ZEND_ME(Gtk4_SoupCookie, __construct, arginfo_class_Gtk4_SoupCookie___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, domain_matches, arginfo_class_Gtk4_SoupCookie_domain_matches, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, equal, arginfo_class_Gtk4_SoupCookie_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, get_domain, arginfo_class_Gtk4_SoupCookie_get_domain, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, get_http_only, arginfo_class_Gtk4_SoupCookie_get_http_only, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, get_name, arginfo_class_Gtk4_SoupCookie_get_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, get_path, arginfo_class_Gtk4_SoupCookie_get_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, get_same_site_policy, arginfo_class_Gtk4_SoupCookie_get_same_site_policy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, get_secure, arginfo_class_Gtk4_SoupCookie_get_secure, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, get_value, arginfo_class_Gtk4_SoupCookie_get_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, set_domain, arginfo_class_Gtk4_SoupCookie_set_domain, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, set_http_only, arginfo_class_Gtk4_SoupCookie_set_http_only, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, set_max_age, arginfo_class_Gtk4_SoupCookie_set_max_age, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, set_name, arginfo_class_Gtk4_SoupCookie_set_name, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, set_path, arginfo_class_Gtk4_SoupCookie_set_path, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, set_same_site_policy, arginfo_class_Gtk4_SoupCookie_set_same_site_policy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, set_secure, arginfo_class_Gtk4_SoupCookie_set_secure, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, set_value, arginfo_class_Gtk4_SoupCookie_set_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, to_cookie_header, arginfo_class_Gtk4_SoupCookie_to_cookie_header, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupCookie, to_set_cookie_header, arginfo_class_Gtk4_SoupCookie_to_set_cookie_header, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_SoupMessageHeaders_methods[] = {
	ZEND_ME(Gtk4_SoupMessageHeaders, __construct, arginfo_class_Gtk4_SoupMessageHeaders___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, append, arginfo_class_Gtk4_SoupMessageHeaders_append, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, clean_connection_headers, arginfo_class_Gtk4_SoupMessageHeaders_clean_connection_headers, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, clear, arginfo_class_Gtk4_SoupMessageHeaders_clear, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, foreach, arginfo_class_Gtk4_SoupMessageHeaders_foreach, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, get_content_length, arginfo_class_Gtk4_SoupMessageHeaders_get_content_length, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, get_content_range, arginfo_class_Gtk4_SoupMessageHeaders_get_content_range, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, get_encoding, arginfo_class_Gtk4_SoupMessageHeaders_get_encoding, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, get_expectations, arginfo_class_Gtk4_SoupMessageHeaders_get_expectations, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, get_headers_type, arginfo_class_Gtk4_SoupMessageHeaders_get_headers_type, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, get_list, arginfo_class_Gtk4_SoupMessageHeaders_get_list, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, get_one, arginfo_class_Gtk4_SoupMessageHeaders_get_one, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, header_contains, arginfo_class_Gtk4_SoupMessageHeaders_header_contains, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, header_equals, arginfo_class_Gtk4_SoupMessageHeaders_header_equals, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, remove, arginfo_class_Gtk4_SoupMessageHeaders_remove, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, replace, arginfo_class_Gtk4_SoupMessageHeaders_replace, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, set_content_length, arginfo_class_Gtk4_SoupMessageHeaders_set_content_length, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, set_content_range, arginfo_class_Gtk4_SoupMessageHeaders_set_content_range, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, set_encoding, arginfo_class_Gtk4_SoupMessageHeaders_set_encoding, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, set_expectations, arginfo_class_Gtk4_SoupMessageHeaders_set_expectations, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_SoupMessageHeaders, set_range, arginfo_class_Gtk4_SoupMessageHeaders_set_range, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static zend_class_entry *register_class_Gtk4_SoupCookie(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "SoupCookie", class_Gtk4_SoupCookie_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_SoupEncoding(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\SoupEncoding", IS_LONG, NULL);

	zval enum_case_Unrecognized_value;
	ZVAL_LONG(&enum_case_Unrecognized_value, 0);
	zend_enum_add_case_cstr(class_entry, "Unrecognized", &enum_case_Unrecognized_value);

	zval enum_case_None_value;
	ZVAL_LONG(&enum_case_None_value, 1);
	zend_enum_add_case_cstr(class_entry, "None", &enum_case_None_value);

	zval enum_case_ContentLength_value;
	ZVAL_LONG(&enum_case_ContentLength_value, 2);
	zend_enum_add_case_cstr(class_entry, "ContentLength", &enum_case_ContentLength_value);

	zval enum_case_Eof_value;
	ZVAL_LONG(&enum_case_Eof_value, 3);
	zend_enum_add_case_cstr(class_entry, "Eof", &enum_case_Eof_value);

	zval enum_case_Chunked_value;
	ZVAL_LONG(&enum_case_Chunked_value, 4);
	zend_enum_add_case_cstr(class_entry, "Chunked", &enum_case_Chunked_value);

	zval enum_case_Byteranges_value;
	ZVAL_LONG(&enum_case_Byteranges_value, 5);
	zend_enum_add_case_cstr(class_entry, "Byteranges", &enum_case_Byteranges_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_SoupExpectation(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "SoupExpectation", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_UNRECOGNIZED_value;
	ZVAL_LONG(&const_UNRECOGNIZED_value, 1);
	zend_string *const_UNRECOGNIZED_name = zend_string_init_interned("UNRECOGNIZED", sizeof("UNRECOGNIZED") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_UNRECOGNIZED_name, &const_UNRECOGNIZED_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_UNRECOGNIZED_name);

	zval const_CONTINUE_value;
	ZVAL_LONG(&const_CONTINUE_value, 2);
	zend_string *const_CONTINUE_name = zend_string_init_interned("CONTINUE", sizeof("CONTINUE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_CONTINUE_name, &const_CONTINUE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_CONTINUE_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_SoupMessageHeaders(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "SoupMessageHeaders", class_Gtk4_SoupMessageHeaders_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_SoupMessageHeadersType(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\SoupMessageHeadersType", IS_LONG, NULL);

	zval enum_case_Request_value;
	ZVAL_LONG(&enum_case_Request_value, 0);
	zend_enum_add_case_cstr(class_entry, "Request", &enum_case_Request_value);

	zval enum_case_Response_value;
	ZVAL_LONG(&enum_case_Response_value, 1);
	zend_enum_add_case_cstr(class_entry, "Response", &enum_case_Response_value);

	zval enum_case_Multipart_value;
	ZVAL_LONG(&enum_case_Multipart_value, 2);
	zend_enum_add_case_cstr(class_entry, "Multipart", &enum_case_Multipart_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_SoupSameSitePolicy(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\SoupSameSitePolicy", IS_LONG, NULL);

	zval enum_case_None_value;
	ZVAL_LONG(&enum_case_None_value, 0);
	zend_enum_add_case_cstr(class_entry, "None", &enum_case_None_value);

	zval enum_case_Lax_value;
	ZVAL_LONG(&enum_case_Lax_value, 1);
	zend_enum_add_case_cstr(class_entry, "Lax", &enum_case_Lax_value);

	zval enum_case_Strict_value;
	ZVAL_LONG(&enum_case_Strict_value, 2);
	zend_enum_add_case_cstr(class_entry, "Strict", &enum_case_Strict_value);

	return class_entry;
}
