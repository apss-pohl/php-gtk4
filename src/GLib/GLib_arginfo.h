/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 9cbbfbb55f532f024df4a5b5056126e98bac80df */

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GDateTime___construct, 0, 0, 7)
	ZEND_ARG_OBJ_INFO(0, tz, Gtk4\\GTimeZone, 0)
	ZEND_ARG_TYPE_INFO(0, year, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, month, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, day, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, hour, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, minute, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, seconds, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_new_from_iso8601, 0, 1, Gtk4\\GDateTime, 0)
	ZEND_ARG_TYPE_INFO(0, text, IS_STRING, 0)
	ZEND_ARG_OBJ_INFO_WITH_DEFAULT_VALUE(0, default_tz, Gtk4\\GTimeZone, 1, "null")
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_new_from_unix_local, 0, 1, Gtk4\\GDateTime, 0)
	ZEND_ARG_TYPE_INFO(0, t, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_new_from_unix_local_usec, 0, 1, Gtk4\\GDateTime, 0)
	ZEND_ARG_TYPE_INFO(0, usecs, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDateTime_new_from_unix_utc arginfo_class_Gtk4_GDateTime_new_from_unix_local

#define arginfo_class_Gtk4_GDateTime_new_from_unix_utc_usec arginfo_class_Gtk4_GDateTime_new_from_unix_local_usec

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_new_local, 0, 6, Gtk4\\GDateTime, 0)
	ZEND_ARG_TYPE_INFO(0, year, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, month, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, day, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, hour, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, minute, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, seconds, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_new_now, 0, 1, Gtk4\\GDateTime, 0)
	ZEND_ARG_OBJ_INFO(0, tz, Gtk4\\GTimeZone, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_new_now_local, 0, 0, Gtk4\\GDateTime, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDateTime_new_now_utc arginfo_class_Gtk4_GDateTime_new_now_local

#define arginfo_class_Gtk4_GDateTime_new_utc arginfo_class_Gtk4_GDateTime_new_local

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_add, 0, 1, Gtk4\\GDateTime, 1)
	ZEND_ARG_TYPE_INFO(0, timespan, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_add_days, 0, 1, Gtk4\\GDateTime, 1)
	ZEND_ARG_TYPE_INFO(0, days, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_add_full, 0, 6, Gtk4\\GDateTime, 1)
	ZEND_ARG_TYPE_INFO(0, years, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, months, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, days, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, hours, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, minutes, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, seconds, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_add_hours, 0, 1, Gtk4\\GDateTime, 1)
	ZEND_ARG_TYPE_INFO(0, hours, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_add_minutes, 0, 1, Gtk4\\GDateTime, 1)
	ZEND_ARG_TYPE_INFO(0, minutes, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_add_months, 0, 1, Gtk4\\GDateTime, 1)
	ZEND_ARG_TYPE_INFO(0, months, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_add_seconds, 0, 1, Gtk4\\GDateTime, 1)
	ZEND_ARG_TYPE_INFO(0, seconds, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_add_weeks, 0, 1, Gtk4\\GDateTime, 1)
	ZEND_ARG_TYPE_INFO(0, weeks, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_add_years, 0, 1, Gtk4\\GDateTime, 1)
	ZEND_ARG_TYPE_INFO(0, years, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDateTime_compare, 0, 1, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, dt2, Gtk4\\GDateTime, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDateTime_difference, 0, 1, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, begin, Gtk4\\GDateTime, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDateTime_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, dt2, Gtk4\\GDateTime, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDateTime_format, 0, 1, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, format, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDateTime_format_iso8601, 0, 0, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDateTime_get_day_of_month, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDateTime_get_day_of_week arginfo_class_Gtk4_GDateTime_get_day_of_month

#define arginfo_class_Gtk4_GDateTime_get_day_of_year arginfo_class_Gtk4_GDateTime_get_day_of_month

#define arginfo_class_Gtk4_GDateTime_get_hour arginfo_class_Gtk4_GDateTime_get_day_of_month

#define arginfo_class_Gtk4_GDateTime_get_microsecond arginfo_class_Gtk4_GDateTime_get_day_of_month

#define arginfo_class_Gtk4_GDateTime_get_minute arginfo_class_Gtk4_GDateTime_get_day_of_month

#define arginfo_class_Gtk4_GDateTime_get_month arginfo_class_Gtk4_GDateTime_get_day_of_month

#define arginfo_class_Gtk4_GDateTime_get_second arginfo_class_Gtk4_GDateTime_get_day_of_month

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDateTime_get_seconds, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_get_timezone, 0, 0, Gtk4\\GTimeZone, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDateTime_get_timezone_abbreviation, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDateTime_get_utc_offset arginfo_class_Gtk4_GDateTime_get_day_of_month

#define arginfo_class_Gtk4_GDateTime_get_week_numbering_year arginfo_class_Gtk4_GDateTime_get_day_of_month

#define arginfo_class_Gtk4_GDateTime_get_week_of_year arginfo_class_Gtk4_GDateTime_get_day_of_month

#define arginfo_class_Gtk4_GDateTime_get_year arginfo_class_Gtk4_GDateTime_get_day_of_month

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDateTime_get_ymd, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDateTime_hash arginfo_class_Gtk4_GDateTime_get_day_of_month

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GDateTime_is_daylight_savings, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_to_local, 0, 0, Gtk4\\GDateTime, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GDateTime_to_timezone, 0, 1, Gtk4\\GDateTime, 1)
	ZEND_ARG_OBJ_INFO(0, tz, Gtk4\\GTimeZone, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GDateTime_to_unix arginfo_class_Gtk4_GDateTime_get_day_of_month

#define arginfo_class_Gtk4_GDateTime_to_unix_usec arginfo_class_Gtk4_GDateTime_get_day_of_month

#define arginfo_class_Gtk4_GDateTime_to_utc arginfo_class_Gtk4_GDateTime_to_local

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_GKeyFile___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_get_boolean, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_get_comment, 0, 2, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_get_double, 0, 2, IS_DOUBLE, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_get_int64, 0, 2, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GKeyFile_get_integer arginfo_class_Gtk4_GKeyFile_get_int64

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_get_locale_for_key, 0, 3, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, locale, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_get_locale_string, 0, 3, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, locale, IS_STRING, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GKeyFile_get_start_group arginfo_class_Gtk4_GDateTime_format_iso8601

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_get_string, 0, 2, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GKeyFile_get_uint64 arginfo_class_Gtk4_GKeyFile_get_int64

#define arginfo_class_Gtk4_GKeyFile_get_value arginfo_class_Gtk4_GKeyFile_get_string

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_has_group, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GKeyFile_has_key arginfo_class_Gtk4_GKeyFile_get_boolean

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_load_from_bytes, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, bytes, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_load_from_data, 0, 3, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, data, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, length, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_load_from_data_dirs, 0, 2, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, file, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_load_from_file, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, file, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_remove_comment, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GKeyFile_remove_group arginfo_class_Gtk4_GKeyFile_has_group

#define arginfo_class_Gtk4_GKeyFile_remove_key arginfo_class_Gtk4_GKeyFile_get_boolean

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_save_to_file, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, filename, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_set_boolean, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, value, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_set_comment, 0, 3, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 1)
	ZEND_ARG_TYPE_INFO(0, comment, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_set_double, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, value, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_set_int64, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, value, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GKeyFile_set_integer arginfo_class_Gtk4_GKeyFile_set_int64

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_set_list_separator, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, separator, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_set_locale_string, 0, 4, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, locale, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, string, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_set_string, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, string, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GKeyFile_set_uint64 arginfo_class_Gtk4_GKeyFile_set_int64

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GKeyFile_set_value, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, group_name, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, key, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, value, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GKeyFile_error_quark arginfo_class_Gtk4_GDateTime_get_day_of_month

#define arginfo_class_Gtk4_GTimeZone___construct arginfo_class_Gtk4_GKeyFile___construct

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GTimeZone_new_identifier, 0, 0, Gtk4\\GTimeZone, 0)
	ZEND_ARG_TYPE_INFO_WITH_DEFAULT_VALUE(0, identifier, IS_STRING, 1, "null")
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GTimeZone_new_local arginfo_class_Gtk4_GDateTime_get_timezone

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_GTimeZone_new_offset, 0, 1, Gtk4\\GTimeZone, 0)
	ZEND_ARG_TYPE_INFO(0, seconds, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GTimeZone_new_utc arginfo_class_Gtk4_GDateTime_get_timezone

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTimeZone_find_interval, 0, 2, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, type, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, time, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTimeZone_get_abbreviation, 0, 1, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, interval, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_GTimeZone_get_identifier arginfo_class_Gtk4_GDateTime_get_timezone_abbreviation

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTimeZone_get_offset, 0, 1, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, interval, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_GTimeZone_is_dst, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, interval, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_METHOD(Gtk4_GDateTime, __construct);
ZEND_METHOD(Gtk4_GDateTime, new_from_iso8601);
ZEND_METHOD(Gtk4_GDateTime, new_from_unix_local);
ZEND_METHOD(Gtk4_GDateTime, new_from_unix_local_usec);
ZEND_METHOD(Gtk4_GDateTime, new_from_unix_utc);
ZEND_METHOD(Gtk4_GDateTime, new_from_unix_utc_usec);
ZEND_METHOD(Gtk4_GDateTime, new_local);
ZEND_METHOD(Gtk4_GDateTime, new_now);
ZEND_METHOD(Gtk4_GDateTime, new_now_local);
ZEND_METHOD(Gtk4_GDateTime, new_now_utc);
ZEND_METHOD(Gtk4_GDateTime, new_utc);
ZEND_METHOD(Gtk4_GDateTime, add);
ZEND_METHOD(Gtk4_GDateTime, add_days);
ZEND_METHOD(Gtk4_GDateTime, add_full);
ZEND_METHOD(Gtk4_GDateTime, add_hours);
ZEND_METHOD(Gtk4_GDateTime, add_minutes);
ZEND_METHOD(Gtk4_GDateTime, add_months);
ZEND_METHOD(Gtk4_GDateTime, add_seconds);
ZEND_METHOD(Gtk4_GDateTime, add_weeks);
ZEND_METHOD(Gtk4_GDateTime, add_years);
ZEND_METHOD(Gtk4_GDateTime, compare);
ZEND_METHOD(Gtk4_GDateTime, difference);
ZEND_METHOD(Gtk4_GDateTime, equal);
ZEND_METHOD(Gtk4_GDateTime, format);
ZEND_METHOD(Gtk4_GDateTime, format_iso8601);
ZEND_METHOD(Gtk4_GDateTime, get_day_of_month);
ZEND_METHOD(Gtk4_GDateTime, get_day_of_week);
ZEND_METHOD(Gtk4_GDateTime, get_day_of_year);
ZEND_METHOD(Gtk4_GDateTime, get_hour);
ZEND_METHOD(Gtk4_GDateTime, get_microsecond);
ZEND_METHOD(Gtk4_GDateTime, get_minute);
ZEND_METHOD(Gtk4_GDateTime, get_month);
ZEND_METHOD(Gtk4_GDateTime, get_second);
ZEND_METHOD(Gtk4_GDateTime, get_seconds);
ZEND_METHOD(Gtk4_GDateTime, get_timezone);
ZEND_METHOD(Gtk4_GDateTime, get_timezone_abbreviation);
ZEND_METHOD(Gtk4_GDateTime, get_utc_offset);
ZEND_METHOD(Gtk4_GDateTime, get_week_numbering_year);
ZEND_METHOD(Gtk4_GDateTime, get_week_of_year);
ZEND_METHOD(Gtk4_GDateTime, get_year);
ZEND_METHOD(Gtk4_GDateTime, get_ymd);
ZEND_METHOD(Gtk4_GDateTime, hash);
ZEND_METHOD(Gtk4_GDateTime, is_daylight_savings);
ZEND_METHOD(Gtk4_GDateTime, to_local);
ZEND_METHOD(Gtk4_GDateTime, to_timezone);
ZEND_METHOD(Gtk4_GDateTime, to_unix);
ZEND_METHOD(Gtk4_GDateTime, to_unix_usec);
ZEND_METHOD(Gtk4_GDateTime, to_utc);
ZEND_METHOD(Gtk4_GKeyFile, __construct);
ZEND_METHOD(Gtk4_GKeyFile, get_boolean);
ZEND_METHOD(Gtk4_GKeyFile, get_comment);
ZEND_METHOD(Gtk4_GKeyFile, get_double);
ZEND_METHOD(Gtk4_GKeyFile, get_int64);
ZEND_METHOD(Gtk4_GKeyFile, get_integer);
ZEND_METHOD(Gtk4_GKeyFile, get_locale_for_key);
ZEND_METHOD(Gtk4_GKeyFile, get_locale_string);
ZEND_METHOD(Gtk4_GKeyFile, get_start_group);
ZEND_METHOD(Gtk4_GKeyFile, get_string);
ZEND_METHOD(Gtk4_GKeyFile, get_uint64);
ZEND_METHOD(Gtk4_GKeyFile, get_value);
ZEND_METHOD(Gtk4_GKeyFile, has_group);
ZEND_METHOD(Gtk4_GKeyFile, has_key);
ZEND_METHOD(Gtk4_GKeyFile, load_from_bytes);
ZEND_METHOD(Gtk4_GKeyFile, load_from_data);
ZEND_METHOD(Gtk4_GKeyFile, load_from_data_dirs);
ZEND_METHOD(Gtk4_GKeyFile, load_from_file);
ZEND_METHOD(Gtk4_GKeyFile, remove_comment);
ZEND_METHOD(Gtk4_GKeyFile, remove_group);
ZEND_METHOD(Gtk4_GKeyFile, remove_key);
ZEND_METHOD(Gtk4_GKeyFile, save_to_file);
ZEND_METHOD(Gtk4_GKeyFile, set_boolean);
ZEND_METHOD(Gtk4_GKeyFile, set_comment);
ZEND_METHOD(Gtk4_GKeyFile, set_double);
ZEND_METHOD(Gtk4_GKeyFile, set_int64);
ZEND_METHOD(Gtk4_GKeyFile, set_integer);
ZEND_METHOD(Gtk4_GKeyFile, set_list_separator);
ZEND_METHOD(Gtk4_GKeyFile, set_locale_string);
ZEND_METHOD(Gtk4_GKeyFile, set_string);
ZEND_METHOD(Gtk4_GKeyFile, set_uint64);
ZEND_METHOD(Gtk4_GKeyFile, set_value);
ZEND_METHOD(Gtk4_GKeyFile, error_quark);
ZEND_METHOD(Gtk4_GTimeZone, __construct);
ZEND_METHOD(Gtk4_GTimeZone, new_identifier);
ZEND_METHOD(Gtk4_GTimeZone, new_local);
ZEND_METHOD(Gtk4_GTimeZone, new_offset);
ZEND_METHOD(Gtk4_GTimeZone, new_utc);
ZEND_METHOD(Gtk4_GTimeZone, find_interval);
ZEND_METHOD(Gtk4_GTimeZone, get_abbreviation);
ZEND_METHOD(Gtk4_GTimeZone, get_identifier);
ZEND_METHOD(Gtk4_GTimeZone, get_offset);
ZEND_METHOD(Gtk4_GTimeZone, is_dst);

static const zend_function_entry class_Gtk4_GDateTime_methods[] = {
	ZEND_ME(Gtk4_GDateTime, __construct, arginfo_class_Gtk4_GDateTime___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, new_from_iso8601, arginfo_class_Gtk4_GDateTime_new_from_iso8601, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDateTime, new_from_unix_local, arginfo_class_Gtk4_GDateTime_new_from_unix_local, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDateTime, new_from_unix_local_usec, arginfo_class_Gtk4_GDateTime_new_from_unix_local_usec, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDateTime, new_from_unix_utc, arginfo_class_Gtk4_GDateTime_new_from_unix_utc, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDateTime, new_from_unix_utc_usec, arginfo_class_Gtk4_GDateTime_new_from_unix_utc_usec, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDateTime, new_local, arginfo_class_Gtk4_GDateTime_new_local, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDateTime, new_now, arginfo_class_Gtk4_GDateTime_new_now, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDateTime, new_now_local, arginfo_class_Gtk4_GDateTime_new_now_local, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDateTime, new_now_utc, arginfo_class_Gtk4_GDateTime_new_now_utc, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDateTime, new_utc, arginfo_class_Gtk4_GDateTime_new_utc, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GDateTime, add, arginfo_class_Gtk4_GDateTime_add, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, add_days, arginfo_class_Gtk4_GDateTime_add_days, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, add_full, arginfo_class_Gtk4_GDateTime_add_full, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, add_hours, arginfo_class_Gtk4_GDateTime_add_hours, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, add_minutes, arginfo_class_Gtk4_GDateTime_add_minutes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, add_months, arginfo_class_Gtk4_GDateTime_add_months, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, add_seconds, arginfo_class_Gtk4_GDateTime_add_seconds, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, add_weeks, arginfo_class_Gtk4_GDateTime_add_weeks, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, add_years, arginfo_class_Gtk4_GDateTime_add_years, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, compare, arginfo_class_Gtk4_GDateTime_compare, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, difference, arginfo_class_Gtk4_GDateTime_difference, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, equal, arginfo_class_Gtk4_GDateTime_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, format, arginfo_class_Gtk4_GDateTime_format, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, format_iso8601, arginfo_class_Gtk4_GDateTime_format_iso8601, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_day_of_month, arginfo_class_Gtk4_GDateTime_get_day_of_month, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_day_of_week, arginfo_class_Gtk4_GDateTime_get_day_of_week, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_day_of_year, arginfo_class_Gtk4_GDateTime_get_day_of_year, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_hour, arginfo_class_Gtk4_GDateTime_get_hour, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_microsecond, arginfo_class_Gtk4_GDateTime_get_microsecond, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_minute, arginfo_class_Gtk4_GDateTime_get_minute, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_month, arginfo_class_Gtk4_GDateTime_get_month, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_second, arginfo_class_Gtk4_GDateTime_get_second, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_seconds, arginfo_class_Gtk4_GDateTime_get_seconds, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_timezone, arginfo_class_Gtk4_GDateTime_get_timezone, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_timezone_abbreviation, arginfo_class_Gtk4_GDateTime_get_timezone_abbreviation, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_utc_offset, arginfo_class_Gtk4_GDateTime_get_utc_offset, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_week_numbering_year, arginfo_class_Gtk4_GDateTime_get_week_numbering_year, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_week_of_year, arginfo_class_Gtk4_GDateTime_get_week_of_year, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_year, arginfo_class_Gtk4_GDateTime_get_year, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, get_ymd, arginfo_class_Gtk4_GDateTime_get_ymd, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, hash, arginfo_class_Gtk4_GDateTime_hash, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, is_daylight_savings, arginfo_class_Gtk4_GDateTime_is_daylight_savings, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, to_local, arginfo_class_Gtk4_GDateTime_to_local, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, to_timezone, arginfo_class_Gtk4_GDateTime_to_timezone, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, to_unix, arginfo_class_Gtk4_GDateTime_to_unix, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, to_unix_usec, arginfo_class_Gtk4_GDateTime_to_unix_usec, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GDateTime, to_utc, arginfo_class_Gtk4_GDateTime_to_utc, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GKeyFile_methods[] = {
	ZEND_ME(Gtk4_GKeyFile, __construct, arginfo_class_Gtk4_GKeyFile___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, get_boolean, arginfo_class_Gtk4_GKeyFile_get_boolean, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, get_comment, arginfo_class_Gtk4_GKeyFile_get_comment, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, get_double, arginfo_class_Gtk4_GKeyFile_get_double, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, get_int64, arginfo_class_Gtk4_GKeyFile_get_int64, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, get_integer, arginfo_class_Gtk4_GKeyFile_get_integer, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, get_locale_for_key, arginfo_class_Gtk4_GKeyFile_get_locale_for_key, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, get_locale_string, arginfo_class_Gtk4_GKeyFile_get_locale_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, get_start_group, arginfo_class_Gtk4_GKeyFile_get_start_group, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, get_string, arginfo_class_Gtk4_GKeyFile_get_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, get_uint64, arginfo_class_Gtk4_GKeyFile_get_uint64, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, get_value, arginfo_class_Gtk4_GKeyFile_get_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, has_group, arginfo_class_Gtk4_GKeyFile_has_group, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, has_key, arginfo_class_Gtk4_GKeyFile_has_key, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, load_from_bytes, arginfo_class_Gtk4_GKeyFile_load_from_bytes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, load_from_data, arginfo_class_Gtk4_GKeyFile_load_from_data, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, load_from_data_dirs, arginfo_class_Gtk4_GKeyFile_load_from_data_dirs, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, load_from_file, arginfo_class_Gtk4_GKeyFile_load_from_file, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, remove_comment, arginfo_class_Gtk4_GKeyFile_remove_comment, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, remove_group, arginfo_class_Gtk4_GKeyFile_remove_group, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, remove_key, arginfo_class_Gtk4_GKeyFile_remove_key, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, save_to_file, arginfo_class_Gtk4_GKeyFile_save_to_file, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, set_boolean, arginfo_class_Gtk4_GKeyFile_set_boolean, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, set_comment, arginfo_class_Gtk4_GKeyFile_set_comment, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, set_double, arginfo_class_Gtk4_GKeyFile_set_double, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, set_int64, arginfo_class_Gtk4_GKeyFile_set_int64, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, set_integer, arginfo_class_Gtk4_GKeyFile_set_integer, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, set_list_separator, arginfo_class_Gtk4_GKeyFile_set_list_separator, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, set_locale_string, arginfo_class_Gtk4_GKeyFile_set_locale_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, set_string, arginfo_class_Gtk4_GKeyFile_set_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, set_uint64, arginfo_class_Gtk4_GKeyFile_set_uint64, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, set_value, arginfo_class_Gtk4_GKeyFile_set_value, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GKeyFile, error_quark, arginfo_class_Gtk4_GKeyFile_error_quark, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_GTimeZone_methods[] = {
	ZEND_ME(Gtk4_GTimeZone, __construct, arginfo_class_Gtk4_GTimeZone___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_GTimeZone, new_identifier, arginfo_class_Gtk4_GTimeZone_new_identifier, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GTimeZone, new_local, arginfo_class_Gtk4_GTimeZone_new_local, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GTimeZone, new_offset, arginfo_class_Gtk4_GTimeZone_new_offset, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GTimeZone, new_utc, arginfo_class_Gtk4_GTimeZone_new_utc, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_GTimeZone, find_interval, arginfo_class_Gtk4_GTimeZone_find_interval, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTimeZone, get_abbreviation, arginfo_class_Gtk4_GTimeZone_get_abbreviation, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTimeZone, get_identifier, arginfo_class_Gtk4_GTimeZone_get_identifier, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTimeZone, get_offset, arginfo_class_Gtk4_GTimeZone_get_offset, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_GTimeZone, is_dst, arginfo_class_Gtk4_GTimeZone_is_dst, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static zend_class_entry *register_class_Gtk4_GDateTime(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GDateTime", class_Gtk4_GDateTime_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GKeyFile(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GKeyFile", class_Gtk4_GKeyFile_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_GTimeZone(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "GTimeZone", class_Gtk4_GTimeZone_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}
