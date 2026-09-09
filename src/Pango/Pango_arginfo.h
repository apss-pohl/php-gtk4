/* This is a generated file, edit the .stub.php file instead.
 * Stub hash: 146d8c59653749ceb3429b3bd796ea1150042831 */

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_PangoAttrList___construct, 0, 0, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoAttrList_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, other_list, Gtk4\\PangoAttrList, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoAttrList_splice, 0, 3, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, other, Gtk4\\PangoAttrList, 0)
	ZEND_ARG_TYPE_INFO(0, pos, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, len, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoAttrList_to_string, 0, 0, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoAttrList_update, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, pos, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, remove, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, add, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoAttrList_from_string, 0, 1, Gtk4\\PangoAttrList, 1)
	ZEND_ARG_TYPE_INFO(0, text, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoContext___construct arginfo_class_Gtk4_PangoAttrList___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoContext_changed, 0, 0, IS_VOID, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoContext_get_base_dir, 0, 0, Gtk4\\PangoDirection, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoContext_get_base_gravity, 0, 0, Gtk4\\PangoGravity, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoContext_get_font_description, 0, 0, Gtk4\\PangoFontDescription, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoContext_get_font_map, 0, 0, Gtk4\\PangoFontMap, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoContext_get_gravity arginfo_class_Gtk4_PangoContext_get_base_gravity

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoContext_get_gravity_hint, 0, 0, Gtk4\\PangoGravityHint, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoContext_get_round_glyph_positions, 0, 0, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoContext_get_serial, 0, 0, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoContext_set_base_dir, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, direction, Gtk4\\PangoDirection, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoContext_set_base_gravity, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, gravity, Gtk4\\PangoGravity, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoContext_set_font_description, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, desc, Gtk4\\PangoFontDescription, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoContext_set_font_map, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, font_map, Gtk4\\PangoFontMap, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoContext_set_gravity_hint, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, hint, Gtk4\\PangoGravityHint, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoContext_set_round_glyph_positions, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, round_positions, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoFontDescription___construct arginfo_class_Gtk4_PangoAttrList___construct

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_better_match, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, old_match, Gtk4\\PangoFontDescription, 1)
	ZEND_ARG_OBJ_INFO(0, new_match, Gtk4\\PangoFontDescription, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoFontDescription_copy_static arginfo_class_Gtk4_PangoContext_get_font_description

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_equal, 0, 1, _IS_BOOL, 0)
	ZEND_ARG_OBJ_INFO(0, desc2, Gtk4\\PangoFontDescription, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_get_family, 0, 0, IS_STRING, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoFontDescription_get_gravity arginfo_class_Gtk4_PangoContext_get_base_gravity

#define arginfo_class_Gtk4_PangoFontDescription_get_set_fields arginfo_class_Gtk4_PangoContext_get_serial

#define arginfo_class_Gtk4_PangoFontDescription_get_size arginfo_class_Gtk4_PangoContext_get_serial

#define arginfo_class_Gtk4_PangoFontDescription_get_size_is_absolute arginfo_class_Gtk4_PangoContext_get_round_glyph_positions

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_get_stretch, 0, 0, Gtk4\\PangoStretch, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_get_style, 0, 0, Gtk4\\PangoStyle, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_get_variant, 0, 0, Gtk4\\PangoVariant, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoFontDescription_get_variations arginfo_class_Gtk4_PangoFontDescription_get_family

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_get_weight, 0, 0, Gtk4\\PangoWeight, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoFontDescription_hash arginfo_class_Gtk4_PangoContext_get_serial

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_merge, 0, 2, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, desc_to_merge, Gtk4\\PangoFontDescription, 1)
	ZEND_ARG_TYPE_INFO(0, replace_existing, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_merge_static, 0, 2, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, desc_to_merge, Gtk4\\PangoFontDescription, 0)
	ZEND_ARG_TYPE_INFO(0, replace_existing, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_set_absolute_size, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, size, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_set_family, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, family, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoFontDescription_set_family_static arginfo_class_Gtk4_PangoFontDescription_set_family

#define arginfo_class_Gtk4_PangoFontDescription_set_gravity arginfo_class_Gtk4_PangoContext_set_base_gravity

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_set_size, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, size, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_set_stretch, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, stretch, Gtk4\\PangoStretch, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_set_style, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, style, Gtk4\\PangoStyle, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_set_variant, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, variant, Gtk4\\PangoVariant, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_set_variations, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, variations, IS_STRING, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_set_variations_static, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, variations, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_set_weight, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, weight, Gtk4\\PangoWeight, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoFontDescription_to_filename arginfo_class_Gtk4_PangoFontDescription_get_family

#define arginfo_class_Gtk4_PangoFontDescription_to_string arginfo_class_Gtk4_PangoAttrList_to_string

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_unset_fields, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, to_unset, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoFontDescription_from_string, 0, 1, Gtk4\\PangoFontDescription, 0)
	ZEND_ARG_TYPE_INFO(0, str, IS_STRING, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoFontMap___construct arginfo_class_Gtk4_PangoAttrList___construct

#define arginfo_class_Gtk4_PangoFontMap_changed arginfo_class_Gtk4_PangoContext_changed

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoFontMap_create_context, 0, 0, Gtk4\\PangoContext, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoFontMap_get_serial arginfo_class_Gtk4_PangoContext_get_serial

#define arginfo_class_Gtk4_PangoFontMap_get_item_type arginfo_class_Gtk4_PangoAttrList_to_string

#define arginfo_class_Gtk4_PangoFontMap_get_n_items arginfo_class_Gtk4_PangoContext_get_serial

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoFontMap_get_item, 0, 1, Gtk4\\GObject, 1)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoFontMap_items_changed, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, position, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, removed, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, added, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoFontMap_vfunc_changed arginfo_class_Gtk4_PangoContext_changed

#define arginfo_class_Gtk4_PangoFontMap_vfunc_get_serial arginfo_class_Gtk4_PangoContext_get_serial

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_PangoLayout___construct, 0, 0, 1)
	ZEND_ARG_OBJ_INFO(0, context, Gtk4\\PangoContext, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoLayout_deserialize, 0, 3, Gtk4\\PangoLayout, 1)
	ZEND_ARG_OBJ_INFO(0, context, Gtk4\\PangoContext, 0)
	ZEND_ARG_TYPE_INFO(0, bytes, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoLayout_context_changed arginfo_class_Gtk4_PangoContext_changed

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoLayout_copy, 0, 0, Gtk4\\PangoLayout, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoLayout_get_alignment, 0, 0, Gtk4\\PangoAlignment, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoLayout_get_attributes, 0, 0, Gtk4\\PangoAttrList, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoLayout_get_auto_dir arginfo_class_Gtk4_PangoContext_get_round_glyph_positions

#define arginfo_class_Gtk4_PangoLayout_get_baseline arginfo_class_Gtk4_PangoContext_get_serial

#define arginfo_class_Gtk4_PangoLayout_get_character_count arginfo_class_Gtk4_PangoContext_get_serial

#define arginfo_class_Gtk4_PangoLayout_get_context arginfo_class_Gtk4_PangoFontMap_create_context

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoLayout_get_direction, 0, 1, Gtk4\\PangoDirection, 0)
	ZEND_ARG_TYPE_INFO(0, index, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoLayout_get_ellipsize, 0, 0, Gtk4\\PangoEllipsizeMode, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoLayout_get_font_description arginfo_class_Gtk4_PangoContext_get_font_description

#define arginfo_class_Gtk4_PangoLayout_get_height arginfo_class_Gtk4_PangoContext_get_serial

#define arginfo_class_Gtk4_PangoLayout_get_indent arginfo_class_Gtk4_PangoContext_get_serial

#define arginfo_class_Gtk4_PangoLayout_get_justify arginfo_class_Gtk4_PangoContext_get_round_glyph_positions

#define arginfo_class_Gtk4_PangoLayout_get_justify_last_line arginfo_class_Gtk4_PangoContext_get_round_glyph_positions

#define arginfo_class_Gtk4_PangoLayout_get_line_count arginfo_class_Gtk4_PangoContext_get_serial

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_get_line_spacing, 0, 0, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_get_pixel_size, 0, 0, IS_ARRAY, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoLayout_get_serial arginfo_class_Gtk4_PangoContext_get_serial

#define arginfo_class_Gtk4_PangoLayout_get_single_paragraph_mode arginfo_class_Gtk4_PangoContext_get_round_glyph_positions

#define arginfo_class_Gtk4_PangoLayout_get_size arginfo_class_Gtk4_PangoLayout_get_pixel_size

#define arginfo_class_Gtk4_PangoLayout_get_spacing arginfo_class_Gtk4_PangoContext_get_serial

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoLayout_get_tabs, 0, 0, Gtk4\\PangoTabArray, 1)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoLayout_get_text arginfo_class_Gtk4_PangoAttrList_to_string

#define arginfo_class_Gtk4_PangoLayout_get_unknown_glyphs_count arginfo_class_Gtk4_PangoContext_get_serial

#define arginfo_class_Gtk4_PangoLayout_get_width arginfo_class_Gtk4_PangoContext_get_serial

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoLayout_get_wrap, 0, 0, Gtk4\\PangoWrapMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_index_to_line_x, 0, 2, IS_ARRAY, 0)
	ZEND_ARG_TYPE_INFO(0, index, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, trailing, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoLayout_is_ellipsized arginfo_class_Gtk4_PangoContext_get_round_glyph_positions

#define arginfo_class_Gtk4_PangoLayout_is_wrapped arginfo_class_Gtk4_PangoContext_get_round_glyph_positions

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_move_cursor_visually, 0, 4, IS_ARRAY, 0)
	ZEND_ARG_TYPE_INFO(0, strong, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, old_index, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, old_trailing, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, direction, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_serialize, 0, 1, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_alignment, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, alignment, Gtk4\\PangoAlignment, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_attributes, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, attrs, Gtk4\\PangoAttrList, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_auto_dir, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, auto_dir, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_ellipsize, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, ellipsize, Gtk4\\PangoEllipsizeMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_font_description, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, desc, Gtk4\\PangoFontDescription, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_height, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, height, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_indent, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, indent, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_justify, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, justify, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoLayout_set_justify_last_line arginfo_class_Gtk4_PangoLayout_set_justify

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_line_spacing, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, factor, IS_DOUBLE, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_markup, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, markup, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, length, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_markup_with_accel, 0, 3, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, markup, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, length, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, accel_marker, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_single_paragraph_mode, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, setting, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_spacing, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, spacing, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_tabs, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, tabs, Gtk4\\PangoTabArray, 1)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_text, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, text, IS_STRING, 0)
	ZEND_ARG_TYPE_INFO(0, length, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_width, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, width, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_set_wrap, 0, 1, IS_VOID, 0)
	ZEND_ARG_OBJ_INFO(0, wrap, Gtk4\\PangoWrapMode, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_write_to_file, 0, 2, _IS_BOOL, 0)
	ZEND_ARG_TYPE_INFO(0, flags, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, filename, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoLayout_xy_to_index, 0, 2, IS_ARRAY, 1)
	ZEND_ARG_TYPE_INFO(0, x, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, y, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_INFO_EX(arginfo_class_Gtk4_PangoTabArray___construct, 0, 0, 2)
	ZEND_ARG_TYPE_INFO(0, initial_size, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, positions_in_pixels, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoTabArray_get_decimal_point, 0, 1, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, tab_index, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoTabArray_get_positions_in_pixels arginfo_class_Gtk4_PangoContext_get_round_glyph_positions

#define arginfo_class_Gtk4_PangoTabArray_get_size arginfo_class_Gtk4_PangoContext_get_serial

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoTabArray_get_tab, 0, 1, IS_ARRAY, 0)
	ZEND_ARG_TYPE_INFO(0, tab_index, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoTabArray_resize, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, new_size, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoTabArray_set_decimal_point, 0, 2, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, tab_index, IS_LONG, 0)
	ZEND_ARG_TYPE_INFO(0, decimal_point, IS_LONG, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoTabArray_set_positions_in_pixels, 0, 1, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, positions_in_pixels, _IS_BOOL, 0)
ZEND_END_ARG_INFO()

ZEND_BEGIN_ARG_WITH_RETURN_TYPE_INFO_EX(arginfo_class_Gtk4_PangoTabArray_set_tab, 0, 3, IS_VOID, 0)
	ZEND_ARG_TYPE_INFO(0, tab_index, IS_LONG, 0)
	ZEND_ARG_OBJ_INFO(0, alignment, Gtk4\\PangoTabAlign, 0)
	ZEND_ARG_TYPE_INFO(0, location, IS_LONG, 0)
ZEND_END_ARG_INFO()

#define arginfo_class_Gtk4_PangoTabArray_sort arginfo_class_Gtk4_PangoContext_changed

#define arginfo_class_Gtk4_PangoTabArray_to_string arginfo_class_Gtk4_PangoAttrList_to_string

ZEND_BEGIN_ARG_WITH_RETURN_OBJ_INFO_EX(arginfo_class_Gtk4_PangoTabArray_from_string, 0, 1, Gtk4\\PangoTabArray, 1)
	ZEND_ARG_TYPE_INFO(0, text, IS_STRING, 0)
ZEND_END_ARG_INFO()

ZEND_METHOD(Gtk4_PangoAttrList, __construct);
ZEND_METHOD(Gtk4_PangoAttrList, equal);
ZEND_METHOD(Gtk4_PangoAttrList, splice);
ZEND_METHOD(Gtk4_PangoAttrList, to_string);
ZEND_METHOD(Gtk4_PangoAttrList, update);
ZEND_METHOD(Gtk4_PangoAttrList, from_string);
ZEND_METHOD(Gtk4_PangoContext, __construct);
ZEND_METHOD(Gtk4_PangoContext, changed);
ZEND_METHOD(Gtk4_PangoContext, get_base_dir);
ZEND_METHOD(Gtk4_PangoContext, get_base_gravity);
ZEND_METHOD(Gtk4_PangoContext, get_font_description);
ZEND_METHOD(Gtk4_PangoContext, get_font_map);
ZEND_METHOD(Gtk4_PangoContext, get_gravity);
ZEND_METHOD(Gtk4_PangoContext, get_gravity_hint);
ZEND_METHOD(Gtk4_PangoContext, get_round_glyph_positions);
ZEND_METHOD(Gtk4_PangoContext, get_serial);
ZEND_METHOD(Gtk4_PangoContext, set_base_dir);
ZEND_METHOD(Gtk4_PangoContext, set_base_gravity);
ZEND_METHOD(Gtk4_PangoContext, set_font_description);
ZEND_METHOD(Gtk4_PangoContext, set_font_map);
ZEND_METHOD(Gtk4_PangoContext, set_gravity_hint);
ZEND_METHOD(Gtk4_PangoContext, set_round_glyph_positions);
ZEND_METHOD(Gtk4_PangoFontDescription, __construct);
ZEND_METHOD(Gtk4_PangoFontDescription, better_match);
ZEND_METHOD(Gtk4_PangoFontDescription, copy_static);
ZEND_METHOD(Gtk4_PangoFontDescription, equal);
ZEND_METHOD(Gtk4_PangoFontDescription, get_family);
ZEND_METHOD(Gtk4_PangoFontDescription, get_gravity);
ZEND_METHOD(Gtk4_PangoFontDescription, get_set_fields);
ZEND_METHOD(Gtk4_PangoFontDescription, get_size);
ZEND_METHOD(Gtk4_PangoFontDescription, get_size_is_absolute);
ZEND_METHOD(Gtk4_PangoFontDescription, get_stretch);
ZEND_METHOD(Gtk4_PangoFontDescription, get_style);
ZEND_METHOD(Gtk4_PangoFontDescription, get_variant);
ZEND_METHOD(Gtk4_PangoFontDescription, get_variations);
ZEND_METHOD(Gtk4_PangoFontDescription, get_weight);
ZEND_METHOD(Gtk4_PangoFontDescription, hash);
ZEND_METHOD(Gtk4_PangoFontDescription, merge);
ZEND_METHOD(Gtk4_PangoFontDescription, merge_static);
ZEND_METHOD(Gtk4_PangoFontDescription, set_absolute_size);
ZEND_METHOD(Gtk4_PangoFontDescription, set_family);
ZEND_METHOD(Gtk4_PangoFontDescription, set_family_static);
ZEND_METHOD(Gtk4_PangoFontDescription, set_gravity);
ZEND_METHOD(Gtk4_PangoFontDescription, set_size);
ZEND_METHOD(Gtk4_PangoFontDescription, set_stretch);
ZEND_METHOD(Gtk4_PangoFontDescription, set_style);
ZEND_METHOD(Gtk4_PangoFontDescription, set_variant);
ZEND_METHOD(Gtk4_PangoFontDescription, set_variations);
ZEND_METHOD(Gtk4_PangoFontDescription, set_variations_static);
ZEND_METHOD(Gtk4_PangoFontDescription, set_weight);
ZEND_METHOD(Gtk4_PangoFontDescription, to_filename);
ZEND_METHOD(Gtk4_PangoFontDescription, to_string);
ZEND_METHOD(Gtk4_PangoFontDescription, unset_fields);
ZEND_METHOD(Gtk4_PangoFontDescription, from_string);
ZEND_METHOD(Gtk4_PangoFontMap, __construct);
ZEND_METHOD(Gtk4_PangoFontMap, changed);
ZEND_METHOD(Gtk4_PangoFontMap, create_context);
ZEND_METHOD(Gtk4_PangoFontMap, get_serial);
ZEND_METHOD(Gtk4_GListModel, get_item_type);
ZEND_METHOD(Gtk4_GListModel, get_n_items);
ZEND_METHOD(Gtk4_GListModel, get_item);
ZEND_METHOD(Gtk4_GListModel, items_changed);
ZEND_METHOD(Gtk4_PangoFontMap, vfunc_changed);
ZEND_METHOD(Gtk4_PangoFontMap, vfunc_get_serial);
ZEND_METHOD(Gtk4_PangoLayout, __construct);
ZEND_METHOD(Gtk4_PangoLayout, deserialize);
ZEND_METHOD(Gtk4_PangoLayout, context_changed);
ZEND_METHOD(Gtk4_PangoLayout, copy);
ZEND_METHOD(Gtk4_PangoLayout, get_alignment);
ZEND_METHOD(Gtk4_PangoLayout, get_attributes);
ZEND_METHOD(Gtk4_PangoLayout, get_auto_dir);
ZEND_METHOD(Gtk4_PangoLayout, get_baseline);
ZEND_METHOD(Gtk4_PangoLayout, get_character_count);
ZEND_METHOD(Gtk4_PangoLayout, get_context);
ZEND_METHOD(Gtk4_PangoLayout, get_direction);
ZEND_METHOD(Gtk4_PangoLayout, get_ellipsize);
ZEND_METHOD(Gtk4_PangoLayout, get_font_description);
ZEND_METHOD(Gtk4_PangoLayout, get_height);
ZEND_METHOD(Gtk4_PangoLayout, get_indent);
ZEND_METHOD(Gtk4_PangoLayout, get_justify);
ZEND_METHOD(Gtk4_PangoLayout, get_justify_last_line);
ZEND_METHOD(Gtk4_PangoLayout, get_line_count);
ZEND_METHOD(Gtk4_PangoLayout, get_line_spacing);
ZEND_METHOD(Gtk4_PangoLayout, get_pixel_size);
ZEND_METHOD(Gtk4_PangoLayout, get_serial);
ZEND_METHOD(Gtk4_PangoLayout, get_single_paragraph_mode);
ZEND_METHOD(Gtk4_PangoLayout, get_size);
ZEND_METHOD(Gtk4_PangoLayout, get_spacing);
ZEND_METHOD(Gtk4_PangoLayout, get_tabs);
ZEND_METHOD(Gtk4_PangoLayout, get_text);
ZEND_METHOD(Gtk4_PangoLayout, get_unknown_glyphs_count);
ZEND_METHOD(Gtk4_PangoLayout, get_width);
ZEND_METHOD(Gtk4_PangoLayout, get_wrap);
ZEND_METHOD(Gtk4_PangoLayout, index_to_line_x);
ZEND_METHOD(Gtk4_PangoLayout, is_ellipsized);
ZEND_METHOD(Gtk4_PangoLayout, is_wrapped);
ZEND_METHOD(Gtk4_PangoLayout, move_cursor_visually);
ZEND_METHOD(Gtk4_PangoLayout, serialize);
ZEND_METHOD(Gtk4_PangoLayout, set_alignment);
ZEND_METHOD(Gtk4_PangoLayout, set_attributes);
ZEND_METHOD(Gtk4_PangoLayout, set_auto_dir);
ZEND_METHOD(Gtk4_PangoLayout, set_ellipsize);
ZEND_METHOD(Gtk4_PangoLayout, set_font_description);
ZEND_METHOD(Gtk4_PangoLayout, set_height);
ZEND_METHOD(Gtk4_PangoLayout, set_indent);
ZEND_METHOD(Gtk4_PangoLayout, set_justify);
ZEND_METHOD(Gtk4_PangoLayout, set_justify_last_line);
ZEND_METHOD(Gtk4_PangoLayout, set_line_spacing);
ZEND_METHOD(Gtk4_PangoLayout, set_markup);
ZEND_METHOD(Gtk4_PangoLayout, set_markup_with_accel);
ZEND_METHOD(Gtk4_PangoLayout, set_single_paragraph_mode);
ZEND_METHOD(Gtk4_PangoLayout, set_spacing);
ZEND_METHOD(Gtk4_PangoLayout, set_tabs);
ZEND_METHOD(Gtk4_PangoLayout, set_text);
ZEND_METHOD(Gtk4_PangoLayout, set_width);
ZEND_METHOD(Gtk4_PangoLayout, set_wrap);
ZEND_METHOD(Gtk4_PangoLayout, write_to_file);
ZEND_METHOD(Gtk4_PangoLayout, xy_to_index);
ZEND_METHOD(Gtk4_PangoTabArray, __construct);
ZEND_METHOD(Gtk4_PangoTabArray, get_decimal_point);
ZEND_METHOD(Gtk4_PangoTabArray, get_positions_in_pixels);
ZEND_METHOD(Gtk4_PangoTabArray, get_size);
ZEND_METHOD(Gtk4_PangoTabArray, get_tab);
ZEND_METHOD(Gtk4_PangoTabArray, resize);
ZEND_METHOD(Gtk4_PangoTabArray, set_decimal_point);
ZEND_METHOD(Gtk4_PangoTabArray, set_positions_in_pixels);
ZEND_METHOD(Gtk4_PangoTabArray, set_tab);
ZEND_METHOD(Gtk4_PangoTabArray, sort);
ZEND_METHOD(Gtk4_PangoTabArray, to_string);
ZEND_METHOD(Gtk4_PangoTabArray, from_string);

static const zend_function_entry class_Gtk4_PangoAttrList_methods[] = {
	ZEND_ME(Gtk4_PangoAttrList, __construct, arginfo_class_Gtk4_PangoAttrList___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoAttrList, equal, arginfo_class_Gtk4_PangoAttrList_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoAttrList, splice, arginfo_class_Gtk4_PangoAttrList_splice, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoAttrList, to_string, arginfo_class_Gtk4_PangoAttrList_to_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoAttrList, update, arginfo_class_Gtk4_PangoAttrList_update, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoAttrList, from_string, arginfo_class_Gtk4_PangoAttrList_from_string, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_PangoContext_methods[] = {
	ZEND_ME(Gtk4_PangoContext, __construct, arginfo_class_Gtk4_PangoContext___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, changed, arginfo_class_Gtk4_PangoContext_changed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, get_base_dir, arginfo_class_Gtk4_PangoContext_get_base_dir, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, get_base_gravity, arginfo_class_Gtk4_PangoContext_get_base_gravity, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, get_font_description, arginfo_class_Gtk4_PangoContext_get_font_description, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, get_font_map, arginfo_class_Gtk4_PangoContext_get_font_map, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, get_gravity, arginfo_class_Gtk4_PangoContext_get_gravity, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, get_gravity_hint, arginfo_class_Gtk4_PangoContext_get_gravity_hint, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, get_round_glyph_positions, arginfo_class_Gtk4_PangoContext_get_round_glyph_positions, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, get_serial, arginfo_class_Gtk4_PangoContext_get_serial, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, set_base_dir, arginfo_class_Gtk4_PangoContext_set_base_dir, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, set_base_gravity, arginfo_class_Gtk4_PangoContext_set_base_gravity, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, set_font_description, arginfo_class_Gtk4_PangoContext_set_font_description, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, set_font_map, arginfo_class_Gtk4_PangoContext_set_font_map, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, set_gravity_hint, arginfo_class_Gtk4_PangoContext_set_gravity_hint, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoContext, set_round_glyph_positions, arginfo_class_Gtk4_PangoContext_set_round_glyph_positions, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_PangoFontDescription_methods[] = {
	ZEND_ME(Gtk4_PangoFontDescription, __construct, arginfo_class_Gtk4_PangoFontDescription___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, better_match, arginfo_class_Gtk4_PangoFontDescription_better_match, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, copy_static, arginfo_class_Gtk4_PangoFontDescription_copy_static, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, equal, arginfo_class_Gtk4_PangoFontDescription_equal, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, get_family, arginfo_class_Gtk4_PangoFontDescription_get_family, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, get_gravity, arginfo_class_Gtk4_PangoFontDescription_get_gravity, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, get_set_fields, arginfo_class_Gtk4_PangoFontDescription_get_set_fields, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, get_size, arginfo_class_Gtk4_PangoFontDescription_get_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, get_size_is_absolute, arginfo_class_Gtk4_PangoFontDescription_get_size_is_absolute, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, get_stretch, arginfo_class_Gtk4_PangoFontDescription_get_stretch, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, get_style, arginfo_class_Gtk4_PangoFontDescription_get_style, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, get_variant, arginfo_class_Gtk4_PangoFontDescription_get_variant, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, get_variations, arginfo_class_Gtk4_PangoFontDescription_get_variations, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, get_weight, arginfo_class_Gtk4_PangoFontDescription_get_weight, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, hash, arginfo_class_Gtk4_PangoFontDescription_hash, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, merge, arginfo_class_Gtk4_PangoFontDescription_merge, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, merge_static, arginfo_class_Gtk4_PangoFontDescription_merge_static, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, set_absolute_size, arginfo_class_Gtk4_PangoFontDescription_set_absolute_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, set_family, arginfo_class_Gtk4_PangoFontDescription_set_family, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, set_family_static, arginfo_class_Gtk4_PangoFontDescription_set_family_static, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, set_gravity, arginfo_class_Gtk4_PangoFontDescription_set_gravity, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, set_size, arginfo_class_Gtk4_PangoFontDescription_set_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, set_stretch, arginfo_class_Gtk4_PangoFontDescription_set_stretch, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, set_style, arginfo_class_Gtk4_PangoFontDescription_set_style, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, set_variant, arginfo_class_Gtk4_PangoFontDescription_set_variant, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, set_variations, arginfo_class_Gtk4_PangoFontDescription_set_variations, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, set_variations_static, arginfo_class_Gtk4_PangoFontDescription_set_variations_static, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, set_weight, arginfo_class_Gtk4_PangoFontDescription_set_weight, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, to_filename, arginfo_class_Gtk4_PangoFontDescription_to_filename, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, to_string, arginfo_class_Gtk4_PangoFontDescription_to_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, unset_fields, arginfo_class_Gtk4_PangoFontDescription_unset_fields, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontDescription, from_string, arginfo_class_Gtk4_PangoFontDescription_from_string, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_PangoFontMap_methods[] = {
	ZEND_ME(Gtk4_PangoFontMap, __construct, arginfo_class_Gtk4_PangoFontMap___construct, ZEND_ACC_PRIVATE)
	ZEND_ME(Gtk4_PangoFontMap, changed, arginfo_class_Gtk4_PangoFontMap_changed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontMap, create_context, arginfo_class_Gtk4_PangoFontMap_create_context, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontMap, get_serial, arginfo_class_Gtk4_PangoFontMap_get_serial, ZEND_ACC_PUBLIC)
	ZEND_RAW_FENTRY("get_item_type", zim_Gtk4_GListModel_get_item_type, arginfo_class_Gtk4_PangoFontMap_get_item_type, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_n_items", zim_Gtk4_GListModel_get_n_items, arginfo_class_Gtk4_PangoFontMap_get_n_items, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("get_item", zim_Gtk4_GListModel_get_item, arginfo_class_Gtk4_PangoFontMap_get_item, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_RAW_FENTRY("items_changed", zim_Gtk4_GListModel_items_changed, arginfo_class_Gtk4_PangoFontMap_items_changed, ZEND_ACC_PUBLIC, NULL, NULL)
	ZEND_ME(Gtk4_PangoFontMap, vfunc_changed, arginfo_class_Gtk4_PangoFontMap_vfunc_changed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoFontMap, vfunc_get_serial, arginfo_class_Gtk4_PangoFontMap_vfunc_get_serial, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_PangoLayout_methods[] = {
	ZEND_ME(Gtk4_PangoLayout, __construct, arginfo_class_Gtk4_PangoLayout___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, deserialize, arginfo_class_Gtk4_PangoLayout_deserialize, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_ME(Gtk4_PangoLayout, context_changed, arginfo_class_Gtk4_PangoLayout_context_changed, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, copy, arginfo_class_Gtk4_PangoLayout_copy, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_alignment, arginfo_class_Gtk4_PangoLayout_get_alignment, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_attributes, arginfo_class_Gtk4_PangoLayout_get_attributes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_auto_dir, arginfo_class_Gtk4_PangoLayout_get_auto_dir, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_baseline, arginfo_class_Gtk4_PangoLayout_get_baseline, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_character_count, arginfo_class_Gtk4_PangoLayout_get_character_count, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_context, arginfo_class_Gtk4_PangoLayout_get_context, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_direction, arginfo_class_Gtk4_PangoLayout_get_direction, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_ellipsize, arginfo_class_Gtk4_PangoLayout_get_ellipsize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_font_description, arginfo_class_Gtk4_PangoLayout_get_font_description, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_height, arginfo_class_Gtk4_PangoLayout_get_height, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_indent, arginfo_class_Gtk4_PangoLayout_get_indent, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_justify, arginfo_class_Gtk4_PangoLayout_get_justify, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_justify_last_line, arginfo_class_Gtk4_PangoLayout_get_justify_last_line, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_line_count, arginfo_class_Gtk4_PangoLayout_get_line_count, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_line_spacing, arginfo_class_Gtk4_PangoLayout_get_line_spacing, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_pixel_size, arginfo_class_Gtk4_PangoLayout_get_pixel_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_serial, arginfo_class_Gtk4_PangoLayout_get_serial, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_single_paragraph_mode, arginfo_class_Gtk4_PangoLayout_get_single_paragraph_mode, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_size, arginfo_class_Gtk4_PangoLayout_get_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_spacing, arginfo_class_Gtk4_PangoLayout_get_spacing, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_tabs, arginfo_class_Gtk4_PangoLayout_get_tabs, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_text, arginfo_class_Gtk4_PangoLayout_get_text, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_unknown_glyphs_count, arginfo_class_Gtk4_PangoLayout_get_unknown_glyphs_count, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_width, arginfo_class_Gtk4_PangoLayout_get_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, get_wrap, arginfo_class_Gtk4_PangoLayout_get_wrap, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, index_to_line_x, arginfo_class_Gtk4_PangoLayout_index_to_line_x, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, is_ellipsized, arginfo_class_Gtk4_PangoLayout_is_ellipsized, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, is_wrapped, arginfo_class_Gtk4_PangoLayout_is_wrapped, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, move_cursor_visually, arginfo_class_Gtk4_PangoLayout_move_cursor_visually, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, serialize, arginfo_class_Gtk4_PangoLayout_serialize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_alignment, arginfo_class_Gtk4_PangoLayout_set_alignment, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_attributes, arginfo_class_Gtk4_PangoLayout_set_attributes, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_auto_dir, arginfo_class_Gtk4_PangoLayout_set_auto_dir, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_ellipsize, arginfo_class_Gtk4_PangoLayout_set_ellipsize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_font_description, arginfo_class_Gtk4_PangoLayout_set_font_description, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_height, arginfo_class_Gtk4_PangoLayout_set_height, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_indent, arginfo_class_Gtk4_PangoLayout_set_indent, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_justify, arginfo_class_Gtk4_PangoLayout_set_justify, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_justify_last_line, arginfo_class_Gtk4_PangoLayout_set_justify_last_line, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_line_spacing, arginfo_class_Gtk4_PangoLayout_set_line_spacing, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_markup, arginfo_class_Gtk4_PangoLayout_set_markup, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_markup_with_accel, arginfo_class_Gtk4_PangoLayout_set_markup_with_accel, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_single_paragraph_mode, arginfo_class_Gtk4_PangoLayout_set_single_paragraph_mode, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_spacing, arginfo_class_Gtk4_PangoLayout_set_spacing, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_tabs, arginfo_class_Gtk4_PangoLayout_set_tabs, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_text, arginfo_class_Gtk4_PangoLayout_set_text, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_width, arginfo_class_Gtk4_PangoLayout_set_width, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, set_wrap, arginfo_class_Gtk4_PangoLayout_set_wrap, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, write_to_file, arginfo_class_Gtk4_PangoLayout_write_to_file, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoLayout, xy_to_index, arginfo_class_Gtk4_PangoLayout_xy_to_index, ZEND_ACC_PUBLIC)
	ZEND_FE_END
};

static const zend_function_entry class_Gtk4_PangoTabArray_methods[] = {
	ZEND_ME(Gtk4_PangoTabArray, __construct, arginfo_class_Gtk4_PangoTabArray___construct, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoTabArray, get_decimal_point, arginfo_class_Gtk4_PangoTabArray_get_decimal_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoTabArray, get_positions_in_pixels, arginfo_class_Gtk4_PangoTabArray_get_positions_in_pixels, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoTabArray, get_size, arginfo_class_Gtk4_PangoTabArray_get_size, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoTabArray, get_tab, arginfo_class_Gtk4_PangoTabArray_get_tab, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoTabArray, resize, arginfo_class_Gtk4_PangoTabArray_resize, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoTabArray, set_decimal_point, arginfo_class_Gtk4_PangoTabArray_set_decimal_point, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoTabArray, set_positions_in_pixels, arginfo_class_Gtk4_PangoTabArray_set_positions_in_pixels, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoTabArray, set_tab, arginfo_class_Gtk4_PangoTabArray_set_tab, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoTabArray, sort, arginfo_class_Gtk4_PangoTabArray_sort, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoTabArray, to_string, arginfo_class_Gtk4_PangoTabArray_to_string, ZEND_ACC_PUBLIC)
	ZEND_ME(Gtk4_PangoTabArray, from_string, arginfo_class_Gtk4_PangoTabArray_from_string, ZEND_ACC_PUBLIC|ZEND_ACC_STATIC)
	ZEND_FE_END
};

static zend_class_entry *register_class_Gtk4_PangoAlignment(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\PangoAlignment", IS_LONG, NULL);

	zval enum_case_Left_value;
	ZVAL_LONG(&enum_case_Left_value, 0);
	zend_enum_add_case_cstr(class_entry, "Left", &enum_case_Left_value);

	zval enum_case_Center_value;
	ZVAL_LONG(&enum_case_Center_value, 1);
	zend_enum_add_case_cstr(class_entry, "Center", &enum_case_Center_value);

	zval enum_case_Right_value;
	ZVAL_LONG(&enum_case_Right_value, 2);
	zend_enum_add_case_cstr(class_entry, "Right", &enum_case_Right_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoAttrList(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "PangoAttrList", class_Gtk4_PangoAttrList_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoContext(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "PangoContext", class_Gtk4_PangoContext_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoDirection(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\PangoDirection", IS_LONG, NULL);

	zval enum_case_Ltr_value;
	ZVAL_LONG(&enum_case_Ltr_value, 0);
	zend_enum_add_case_cstr(class_entry, "Ltr", &enum_case_Ltr_value);

	zval enum_case_Rtl_value;
	ZVAL_LONG(&enum_case_Rtl_value, 1);
	zend_enum_add_case_cstr(class_entry, "Rtl", &enum_case_Rtl_value);

	zval enum_case_TtbLtr_value;
	ZVAL_LONG(&enum_case_TtbLtr_value, 2);
	zend_enum_add_case_cstr(class_entry, "TtbLtr", &enum_case_TtbLtr_value);

	zval enum_case_TtbRtl_value;
	ZVAL_LONG(&enum_case_TtbRtl_value, 3);
	zend_enum_add_case_cstr(class_entry, "TtbRtl", &enum_case_TtbRtl_value);

	zval enum_case_WeakLtr_value;
	ZVAL_LONG(&enum_case_WeakLtr_value, 4);
	zend_enum_add_case_cstr(class_entry, "WeakLtr", &enum_case_WeakLtr_value);

	zval enum_case_WeakRtl_value;
	ZVAL_LONG(&enum_case_WeakRtl_value, 5);
	zend_enum_add_case_cstr(class_entry, "WeakRtl", &enum_case_WeakRtl_value);

	zval enum_case_Neutral_value;
	ZVAL_LONG(&enum_case_Neutral_value, 6);
	zend_enum_add_case_cstr(class_entry, "Neutral", &enum_case_Neutral_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoEllipsizeMode(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\PangoEllipsizeMode", IS_LONG, NULL);

	zval enum_case_None_value;
	ZVAL_LONG(&enum_case_None_value, 0);
	zend_enum_add_case_cstr(class_entry, "None", &enum_case_None_value);

	zval enum_case_Start_value;
	ZVAL_LONG(&enum_case_Start_value, 1);
	zend_enum_add_case_cstr(class_entry, "Start", &enum_case_Start_value);

	zval enum_case_Middle_value;
	ZVAL_LONG(&enum_case_Middle_value, 2);
	zend_enum_add_case_cstr(class_entry, "Middle", &enum_case_Middle_value);

	zval enum_case_End_value;
	ZVAL_LONG(&enum_case_End_value, 3);
	zend_enum_add_case_cstr(class_entry, "End", &enum_case_End_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoFontDescription(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "PangoFontDescription", class_Gtk4_PangoFontDescription_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoFontMap(zend_class_entry *class_entry_Gtk4_GObject, zend_class_entry *class_entry_Gtk4_GListModel)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "PangoFontMap", class_Gtk4_PangoFontMap_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);
	zend_class_implements(class_entry, 1, class_entry_Gtk4_GListModel);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoFontMask(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "PangoFontMask", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_FAMILY_value;
	ZVAL_LONG(&const_FAMILY_value, 1);
	zend_string *const_FAMILY_name = zend_string_init_interned("FAMILY", sizeof("FAMILY") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_FAMILY_name, &const_FAMILY_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_FAMILY_name);

	zval const_STYLE_value;
	ZVAL_LONG(&const_STYLE_value, 2);
	zend_string *const_STYLE_name = zend_string_init_interned("STYLE", sizeof("STYLE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_STYLE_name, &const_STYLE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_STYLE_name);

	zval const_VARIANT_value;
	ZVAL_LONG(&const_VARIANT_value, 4);
	zend_string *const_VARIANT_name = zend_string_init_interned("VARIANT", sizeof("VARIANT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_VARIANT_name, &const_VARIANT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_VARIANT_name);

	zval const_WEIGHT_value;
	ZVAL_LONG(&const_WEIGHT_value, 8);
	zend_string *const_WEIGHT_name = zend_string_init_interned("WEIGHT", sizeof("WEIGHT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_WEIGHT_name, &const_WEIGHT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_WEIGHT_name);

	zval const_STRETCH_value;
	ZVAL_LONG(&const_STRETCH_value, 16);
	zend_string *const_STRETCH_name = zend_string_init_interned("STRETCH", sizeof("STRETCH") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_STRETCH_name, &const_STRETCH_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_STRETCH_name);

	zval const_SIZE_value;
	ZVAL_LONG(&const_SIZE_value, 32);
	zend_string *const_SIZE_name = zend_string_init_interned("SIZE", sizeof("SIZE") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_SIZE_name, &const_SIZE_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_SIZE_name);

	zval const_GRAVITY_value;
	ZVAL_LONG(&const_GRAVITY_value, 64);
	zend_string *const_GRAVITY_name = zend_string_init_interned("GRAVITY", sizeof("GRAVITY") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_GRAVITY_name, &const_GRAVITY_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_GRAVITY_name);

	zval const_VARIATIONS_value;
	ZVAL_LONG(&const_VARIATIONS_value, 128);
	zend_string *const_VARIATIONS_name = zend_string_init_interned("VARIATIONS", sizeof("VARIATIONS") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_VARIATIONS_name, &const_VARIATIONS_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_VARIATIONS_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoGravity(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\PangoGravity", IS_LONG, NULL);

	zval enum_case_South_value;
	ZVAL_LONG(&enum_case_South_value, 0);
	zend_enum_add_case_cstr(class_entry, "South", &enum_case_South_value);

	zval enum_case_East_value;
	ZVAL_LONG(&enum_case_East_value, 1);
	zend_enum_add_case_cstr(class_entry, "East", &enum_case_East_value);

	zval enum_case_North_value;
	ZVAL_LONG(&enum_case_North_value, 2);
	zend_enum_add_case_cstr(class_entry, "North", &enum_case_North_value);

	zval enum_case_West_value;
	ZVAL_LONG(&enum_case_West_value, 3);
	zend_enum_add_case_cstr(class_entry, "West", &enum_case_West_value);

	zval enum_case_Auto_value;
	ZVAL_LONG(&enum_case_Auto_value, 4);
	zend_enum_add_case_cstr(class_entry, "Auto", &enum_case_Auto_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoGravityHint(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\PangoGravityHint", IS_LONG, NULL);

	zval enum_case_Natural_value;
	ZVAL_LONG(&enum_case_Natural_value, 0);
	zend_enum_add_case_cstr(class_entry, "Natural", &enum_case_Natural_value);

	zval enum_case_Strong_value;
	ZVAL_LONG(&enum_case_Strong_value, 1);
	zend_enum_add_case_cstr(class_entry, "Strong", &enum_case_Strong_value);

	zval enum_case_Line_value;
	ZVAL_LONG(&enum_case_Line_value, 2);
	zend_enum_add_case_cstr(class_entry, "Line", &enum_case_Line_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoLayout(zend_class_entry *class_entry_Gtk4_GObject)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "PangoLayout", class_Gtk4_PangoLayout_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, class_entry_Gtk4_GObject, 0);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoLayoutDeserializeFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "PangoLayoutDeserializeFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_DEFAULT_value;
	ZVAL_LONG(&const_DEFAULT_value, 0);
	zend_string *const_DEFAULT_name = zend_string_init_interned("DEFAULT", sizeof("DEFAULT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_DEFAULT_name, &const_DEFAULT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_DEFAULT_name);

	zval const_CONTEXT_value;
	ZVAL_LONG(&const_CONTEXT_value, 1);
	zend_string *const_CONTEXT_name = zend_string_init_interned("CONTEXT", sizeof("CONTEXT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_CONTEXT_name, &const_CONTEXT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_CONTEXT_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoLayoutSerializeFlags(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "PangoLayoutSerializeFlags", NULL);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL);

	zval const_DEFAULT_value;
	ZVAL_LONG(&const_DEFAULT_value, 0);
	zend_string *const_DEFAULT_name = zend_string_init_interned("DEFAULT", sizeof("DEFAULT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_DEFAULT_name, &const_DEFAULT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_DEFAULT_name);

	zval const_CONTEXT_value;
	ZVAL_LONG(&const_CONTEXT_value, 1);
	zend_string *const_CONTEXT_name = zend_string_init_interned("CONTEXT", sizeof("CONTEXT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_CONTEXT_name, &const_CONTEXT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_CONTEXT_name);

	zval const_OUTPUT_value;
	ZVAL_LONG(&const_OUTPUT_value, 2);
	zend_string *const_OUTPUT_name = zend_string_init_interned("OUTPUT", sizeof("OUTPUT") - 1, 1);
	zend_declare_typed_class_constant(class_entry, const_OUTPUT_name, &const_OUTPUT_value, ZEND_ACC_PUBLIC, NULL, (zend_type) ZEND_TYPE_INIT_MASK(MAY_BE_LONG));
	zend_string_release(const_OUTPUT_name);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoScript(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\PangoScript", IS_LONG, NULL);

	zval enum_case_InvalidCode_value;
	ZVAL_LONG(&enum_case_InvalidCode_value, -1);
	zend_enum_add_case_cstr(class_entry, "InvalidCode", &enum_case_InvalidCode_value);

	zval enum_case_Common_value;
	ZVAL_LONG(&enum_case_Common_value, 0);
	zend_enum_add_case_cstr(class_entry, "Common", &enum_case_Common_value);

	zval enum_case_Inherited_value;
	ZVAL_LONG(&enum_case_Inherited_value, 1);
	zend_enum_add_case_cstr(class_entry, "Inherited", &enum_case_Inherited_value);

	zval enum_case_Arabic_value;
	ZVAL_LONG(&enum_case_Arabic_value, 2);
	zend_enum_add_case_cstr(class_entry, "Arabic", &enum_case_Arabic_value);

	zval enum_case_Armenian_value;
	ZVAL_LONG(&enum_case_Armenian_value, 3);
	zend_enum_add_case_cstr(class_entry, "Armenian", &enum_case_Armenian_value);

	zval enum_case_Bengali_value;
	ZVAL_LONG(&enum_case_Bengali_value, 4);
	zend_enum_add_case_cstr(class_entry, "Bengali", &enum_case_Bengali_value);

	zval enum_case_Bopomofo_value;
	ZVAL_LONG(&enum_case_Bopomofo_value, 5);
	zend_enum_add_case_cstr(class_entry, "Bopomofo", &enum_case_Bopomofo_value);

	zval enum_case_Cherokee_value;
	ZVAL_LONG(&enum_case_Cherokee_value, 6);
	zend_enum_add_case_cstr(class_entry, "Cherokee", &enum_case_Cherokee_value);

	zval enum_case_Coptic_value;
	ZVAL_LONG(&enum_case_Coptic_value, 7);
	zend_enum_add_case_cstr(class_entry, "Coptic", &enum_case_Coptic_value);

	zval enum_case_Cyrillic_value;
	ZVAL_LONG(&enum_case_Cyrillic_value, 8);
	zend_enum_add_case_cstr(class_entry, "Cyrillic", &enum_case_Cyrillic_value);

	zval enum_case_Deseret_value;
	ZVAL_LONG(&enum_case_Deseret_value, 9);
	zend_enum_add_case_cstr(class_entry, "Deseret", &enum_case_Deseret_value);

	zval enum_case_Devanagari_value;
	ZVAL_LONG(&enum_case_Devanagari_value, 10);
	zend_enum_add_case_cstr(class_entry, "Devanagari", &enum_case_Devanagari_value);

	zval enum_case_Ethiopic_value;
	ZVAL_LONG(&enum_case_Ethiopic_value, 11);
	zend_enum_add_case_cstr(class_entry, "Ethiopic", &enum_case_Ethiopic_value);

	zval enum_case_Georgian_value;
	ZVAL_LONG(&enum_case_Georgian_value, 12);
	zend_enum_add_case_cstr(class_entry, "Georgian", &enum_case_Georgian_value);

	zval enum_case_Gothic_value;
	ZVAL_LONG(&enum_case_Gothic_value, 13);
	zend_enum_add_case_cstr(class_entry, "Gothic", &enum_case_Gothic_value);

	zval enum_case_Greek_value;
	ZVAL_LONG(&enum_case_Greek_value, 14);
	zend_enum_add_case_cstr(class_entry, "Greek", &enum_case_Greek_value);

	zval enum_case_Gujarati_value;
	ZVAL_LONG(&enum_case_Gujarati_value, 15);
	zend_enum_add_case_cstr(class_entry, "Gujarati", &enum_case_Gujarati_value);

	zval enum_case_Gurmukhi_value;
	ZVAL_LONG(&enum_case_Gurmukhi_value, 16);
	zend_enum_add_case_cstr(class_entry, "Gurmukhi", &enum_case_Gurmukhi_value);

	zval enum_case_Han_value;
	ZVAL_LONG(&enum_case_Han_value, 17);
	zend_enum_add_case_cstr(class_entry, "Han", &enum_case_Han_value);

	zval enum_case_Hangul_value;
	ZVAL_LONG(&enum_case_Hangul_value, 18);
	zend_enum_add_case_cstr(class_entry, "Hangul", &enum_case_Hangul_value);

	zval enum_case_Hebrew_value;
	ZVAL_LONG(&enum_case_Hebrew_value, 19);
	zend_enum_add_case_cstr(class_entry, "Hebrew", &enum_case_Hebrew_value);

	zval enum_case_Hiragana_value;
	ZVAL_LONG(&enum_case_Hiragana_value, 20);
	zend_enum_add_case_cstr(class_entry, "Hiragana", &enum_case_Hiragana_value);

	zval enum_case_Kannada_value;
	ZVAL_LONG(&enum_case_Kannada_value, 21);
	zend_enum_add_case_cstr(class_entry, "Kannada", &enum_case_Kannada_value);

	zval enum_case_Katakana_value;
	ZVAL_LONG(&enum_case_Katakana_value, 22);
	zend_enum_add_case_cstr(class_entry, "Katakana", &enum_case_Katakana_value);

	zval enum_case_Khmer_value;
	ZVAL_LONG(&enum_case_Khmer_value, 23);
	zend_enum_add_case_cstr(class_entry, "Khmer", &enum_case_Khmer_value);

	zval enum_case_Lao_value;
	ZVAL_LONG(&enum_case_Lao_value, 24);
	zend_enum_add_case_cstr(class_entry, "Lao", &enum_case_Lao_value);

	zval enum_case_Latin_value;
	ZVAL_LONG(&enum_case_Latin_value, 25);
	zend_enum_add_case_cstr(class_entry, "Latin", &enum_case_Latin_value);

	zval enum_case_Malayalam_value;
	ZVAL_LONG(&enum_case_Malayalam_value, 26);
	zend_enum_add_case_cstr(class_entry, "Malayalam", &enum_case_Malayalam_value);

	zval enum_case_Mongolian_value;
	ZVAL_LONG(&enum_case_Mongolian_value, 27);
	zend_enum_add_case_cstr(class_entry, "Mongolian", &enum_case_Mongolian_value);

	zval enum_case_Myanmar_value;
	ZVAL_LONG(&enum_case_Myanmar_value, 28);
	zend_enum_add_case_cstr(class_entry, "Myanmar", &enum_case_Myanmar_value);

	zval enum_case_Ogham_value;
	ZVAL_LONG(&enum_case_Ogham_value, 29);
	zend_enum_add_case_cstr(class_entry, "Ogham", &enum_case_Ogham_value);

	zval enum_case_OldItalic_value;
	ZVAL_LONG(&enum_case_OldItalic_value, 30);
	zend_enum_add_case_cstr(class_entry, "OldItalic", &enum_case_OldItalic_value);

	zval enum_case_Oriya_value;
	ZVAL_LONG(&enum_case_Oriya_value, 31);
	zend_enum_add_case_cstr(class_entry, "Oriya", &enum_case_Oriya_value);

	zval enum_case_Runic_value;
	ZVAL_LONG(&enum_case_Runic_value, 32);
	zend_enum_add_case_cstr(class_entry, "Runic", &enum_case_Runic_value);

	zval enum_case_Sinhala_value;
	ZVAL_LONG(&enum_case_Sinhala_value, 33);
	zend_enum_add_case_cstr(class_entry, "Sinhala", &enum_case_Sinhala_value);

	zval enum_case_Syriac_value;
	ZVAL_LONG(&enum_case_Syriac_value, 34);
	zend_enum_add_case_cstr(class_entry, "Syriac", &enum_case_Syriac_value);

	zval enum_case_Tamil_value;
	ZVAL_LONG(&enum_case_Tamil_value, 35);
	zend_enum_add_case_cstr(class_entry, "Tamil", &enum_case_Tamil_value);

	zval enum_case_Telugu_value;
	ZVAL_LONG(&enum_case_Telugu_value, 36);
	zend_enum_add_case_cstr(class_entry, "Telugu", &enum_case_Telugu_value);

	zval enum_case_Thaana_value;
	ZVAL_LONG(&enum_case_Thaana_value, 37);
	zend_enum_add_case_cstr(class_entry, "Thaana", &enum_case_Thaana_value);

	zval enum_case_Thai_value;
	ZVAL_LONG(&enum_case_Thai_value, 38);
	zend_enum_add_case_cstr(class_entry, "Thai", &enum_case_Thai_value);

	zval enum_case_Tibetan_value;
	ZVAL_LONG(&enum_case_Tibetan_value, 39);
	zend_enum_add_case_cstr(class_entry, "Tibetan", &enum_case_Tibetan_value);

	zval enum_case_CanadianAboriginal_value;
	ZVAL_LONG(&enum_case_CanadianAboriginal_value, 40);
	zend_enum_add_case_cstr(class_entry, "CanadianAboriginal", &enum_case_CanadianAboriginal_value);

	zval enum_case_Yi_value;
	ZVAL_LONG(&enum_case_Yi_value, 41);
	zend_enum_add_case_cstr(class_entry, "Yi", &enum_case_Yi_value);

	zval enum_case_Tagalog_value;
	ZVAL_LONG(&enum_case_Tagalog_value, 42);
	zend_enum_add_case_cstr(class_entry, "Tagalog", &enum_case_Tagalog_value);

	zval enum_case_Hanunoo_value;
	ZVAL_LONG(&enum_case_Hanunoo_value, 43);
	zend_enum_add_case_cstr(class_entry, "Hanunoo", &enum_case_Hanunoo_value);

	zval enum_case_Buhid_value;
	ZVAL_LONG(&enum_case_Buhid_value, 44);
	zend_enum_add_case_cstr(class_entry, "Buhid", &enum_case_Buhid_value);

	zval enum_case_Tagbanwa_value;
	ZVAL_LONG(&enum_case_Tagbanwa_value, 45);
	zend_enum_add_case_cstr(class_entry, "Tagbanwa", &enum_case_Tagbanwa_value);

	zval enum_case_Braille_value;
	ZVAL_LONG(&enum_case_Braille_value, 46);
	zend_enum_add_case_cstr(class_entry, "Braille", &enum_case_Braille_value);

	zval enum_case_Cypriot_value;
	ZVAL_LONG(&enum_case_Cypriot_value, 47);
	zend_enum_add_case_cstr(class_entry, "Cypriot", &enum_case_Cypriot_value);

	zval enum_case_Limbu_value;
	ZVAL_LONG(&enum_case_Limbu_value, 48);
	zend_enum_add_case_cstr(class_entry, "Limbu", &enum_case_Limbu_value);

	zval enum_case_Osmanya_value;
	ZVAL_LONG(&enum_case_Osmanya_value, 49);
	zend_enum_add_case_cstr(class_entry, "Osmanya", &enum_case_Osmanya_value);

	zval enum_case_Shavian_value;
	ZVAL_LONG(&enum_case_Shavian_value, 50);
	zend_enum_add_case_cstr(class_entry, "Shavian", &enum_case_Shavian_value);

	zval enum_case_LinearB_value;
	ZVAL_LONG(&enum_case_LinearB_value, 51);
	zend_enum_add_case_cstr(class_entry, "LinearB", &enum_case_LinearB_value);

	zval enum_case_TaiLe_value;
	ZVAL_LONG(&enum_case_TaiLe_value, 52);
	zend_enum_add_case_cstr(class_entry, "TaiLe", &enum_case_TaiLe_value);

	zval enum_case_Ugaritic_value;
	ZVAL_LONG(&enum_case_Ugaritic_value, 53);
	zend_enum_add_case_cstr(class_entry, "Ugaritic", &enum_case_Ugaritic_value);

	zval enum_case_NewTaiLue_value;
	ZVAL_LONG(&enum_case_NewTaiLue_value, 54);
	zend_enum_add_case_cstr(class_entry, "NewTaiLue", &enum_case_NewTaiLue_value);

	zval enum_case_Buginese_value;
	ZVAL_LONG(&enum_case_Buginese_value, 55);
	zend_enum_add_case_cstr(class_entry, "Buginese", &enum_case_Buginese_value);

	zval enum_case_Glagolitic_value;
	ZVAL_LONG(&enum_case_Glagolitic_value, 56);
	zend_enum_add_case_cstr(class_entry, "Glagolitic", &enum_case_Glagolitic_value);

	zval enum_case_Tifinagh_value;
	ZVAL_LONG(&enum_case_Tifinagh_value, 57);
	zend_enum_add_case_cstr(class_entry, "Tifinagh", &enum_case_Tifinagh_value);

	zval enum_case_SylotiNagri_value;
	ZVAL_LONG(&enum_case_SylotiNagri_value, 58);
	zend_enum_add_case_cstr(class_entry, "SylotiNagri", &enum_case_SylotiNagri_value);

	zval enum_case_OldPersian_value;
	ZVAL_LONG(&enum_case_OldPersian_value, 59);
	zend_enum_add_case_cstr(class_entry, "OldPersian", &enum_case_OldPersian_value);

	zval enum_case_Kharoshthi_value;
	ZVAL_LONG(&enum_case_Kharoshthi_value, 60);
	zend_enum_add_case_cstr(class_entry, "Kharoshthi", &enum_case_Kharoshthi_value);

	zval enum_case_Unknown_value;
	ZVAL_LONG(&enum_case_Unknown_value, 61);
	zend_enum_add_case_cstr(class_entry, "Unknown", &enum_case_Unknown_value);

	zval enum_case_Balinese_value;
	ZVAL_LONG(&enum_case_Balinese_value, 62);
	zend_enum_add_case_cstr(class_entry, "Balinese", &enum_case_Balinese_value);

	zval enum_case_Cuneiform_value;
	ZVAL_LONG(&enum_case_Cuneiform_value, 63);
	zend_enum_add_case_cstr(class_entry, "Cuneiform", &enum_case_Cuneiform_value);

	zval enum_case_Phoenician_value;
	ZVAL_LONG(&enum_case_Phoenician_value, 64);
	zend_enum_add_case_cstr(class_entry, "Phoenician", &enum_case_Phoenician_value);

	zval enum_case_PhagsPa_value;
	ZVAL_LONG(&enum_case_PhagsPa_value, 65);
	zend_enum_add_case_cstr(class_entry, "PhagsPa", &enum_case_PhagsPa_value);

	zval enum_case_Nko_value;
	ZVAL_LONG(&enum_case_Nko_value, 66);
	zend_enum_add_case_cstr(class_entry, "Nko", &enum_case_Nko_value);

	zval enum_case_KayahLi_value;
	ZVAL_LONG(&enum_case_KayahLi_value, 67);
	zend_enum_add_case_cstr(class_entry, "KayahLi", &enum_case_KayahLi_value);

	zval enum_case_Lepcha_value;
	ZVAL_LONG(&enum_case_Lepcha_value, 68);
	zend_enum_add_case_cstr(class_entry, "Lepcha", &enum_case_Lepcha_value);

	zval enum_case_Rejang_value;
	ZVAL_LONG(&enum_case_Rejang_value, 69);
	zend_enum_add_case_cstr(class_entry, "Rejang", &enum_case_Rejang_value);

	zval enum_case_Sundanese_value;
	ZVAL_LONG(&enum_case_Sundanese_value, 70);
	zend_enum_add_case_cstr(class_entry, "Sundanese", &enum_case_Sundanese_value);

	zval enum_case_Saurashtra_value;
	ZVAL_LONG(&enum_case_Saurashtra_value, 71);
	zend_enum_add_case_cstr(class_entry, "Saurashtra", &enum_case_Saurashtra_value);

	zval enum_case_Cham_value;
	ZVAL_LONG(&enum_case_Cham_value, 72);
	zend_enum_add_case_cstr(class_entry, "Cham", &enum_case_Cham_value);

	zval enum_case_OlChiki_value;
	ZVAL_LONG(&enum_case_OlChiki_value, 73);
	zend_enum_add_case_cstr(class_entry, "OlChiki", &enum_case_OlChiki_value);

	zval enum_case_Vai_value;
	ZVAL_LONG(&enum_case_Vai_value, 74);
	zend_enum_add_case_cstr(class_entry, "Vai", &enum_case_Vai_value);

	zval enum_case_Carian_value;
	ZVAL_LONG(&enum_case_Carian_value, 75);
	zend_enum_add_case_cstr(class_entry, "Carian", &enum_case_Carian_value);

	zval enum_case_Lycian_value;
	ZVAL_LONG(&enum_case_Lycian_value, 76);
	zend_enum_add_case_cstr(class_entry, "Lycian", &enum_case_Lycian_value);

	zval enum_case_Lydian_value;
	ZVAL_LONG(&enum_case_Lydian_value, 77);
	zend_enum_add_case_cstr(class_entry, "Lydian", &enum_case_Lydian_value);

	zval enum_case_Batak_value;
	ZVAL_LONG(&enum_case_Batak_value, 78);
	zend_enum_add_case_cstr(class_entry, "Batak", &enum_case_Batak_value);

	zval enum_case_Brahmi_value;
	ZVAL_LONG(&enum_case_Brahmi_value, 79);
	zend_enum_add_case_cstr(class_entry, "Brahmi", &enum_case_Brahmi_value);

	zval enum_case_Mandaic_value;
	ZVAL_LONG(&enum_case_Mandaic_value, 80);
	zend_enum_add_case_cstr(class_entry, "Mandaic", &enum_case_Mandaic_value);

	zval enum_case_Chakma_value;
	ZVAL_LONG(&enum_case_Chakma_value, 81);
	zend_enum_add_case_cstr(class_entry, "Chakma", &enum_case_Chakma_value);

	zval enum_case_MeroiticCursive_value;
	ZVAL_LONG(&enum_case_MeroiticCursive_value, 82);
	zend_enum_add_case_cstr(class_entry, "MeroiticCursive", &enum_case_MeroiticCursive_value);

	zval enum_case_MeroiticHieroglyphs_value;
	ZVAL_LONG(&enum_case_MeroiticHieroglyphs_value, 83);
	zend_enum_add_case_cstr(class_entry, "MeroiticHieroglyphs", &enum_case_MeroiticHieroglyphs_value);

	zval enum_case_Miao_value;
	ZVAL_LONG(&enum_case_Miao_value, 84);
	zend_enum_add_case_cstr(class_entry, "Miao", &enum_case_Miao_value);

	zval enum_case_Sharada_value;
	ZVAL_LONG(&enum_case_Sharada_value, 85);
	zend_enum_add_case_cstr(class_entry, "Sharada", &enum_case_Sharada_value);

	zval enum_case_SoraSompeng_value;
	ZVAL_LONG(&enum_case_SoraSompeng_value, 86);
	zend_enum_add_case_cstr(class_entry, "SoraSompeng", &enum_case_SoraSompeng_value);

	zval enum_case_Takri_value;
	ZVAL_LONG(&enum_case_Takri_value, 87);
	zend_enum_add_case_cstr(class_entry, "Takri", &enum_case_Takri_value);

	zval enum_case_BassaVah_value;
	ZVAL_LONG(&enum_case_BassaVah_value, 88);
	zend_enum_add_case_cstr(class_entry, "BassaVah", &enum_case_BassaVah_value);

	zval enum_case_CaucasianAlbanian_value;
	ZVAL_LONG(&enum_case_CaucasianAlbanian_value, 89);
	zend_enum_add_case_cstr(class_entry, "CaucasianAlbanian", &enum_case_CaucasianAlbanian_value);

	zval enum_case_Duployan_value;
	ZVAL_LONG(&enum_case_Duployan_value, 90);
	zend_enum_add_case_cstr(class_entry, "Duployan", &enum_case_Duployan_value);

	zval enum_case_Elbasan_value;
	ZVAL_LONG(&enum_case_Elbasan_value, 91);
	zend_enum_add_case_cstr(class_entry, "Elbasan", &enum_case_Elbasan_value);

	zval enum_case_Grantha_value;
	ZVAL_LONG(&enum_case_Grantha_value, 92);
	zend_enum_add_case_cstr(class_entry, "Grantha", &enum_case_Grantha_value);

	zval enum_case_Khojki_value;
	ZVAL_LONG(&enum_case_Khojki_value, 93);
	zend_enum_add_case_cstr(class_entry, "Khojki", &enum_case_Khojki_value);

	zval enum_case_Khudawadi_value;
	ZVAL_LONG(&enum_case_Khudawadi_value, 94);
	zend_enum_add_case_cstr(class_entry, "Khudawadi", &enum_case_Khudawadi_value);

	zval enum_case_LinearA_value;
	ZVAL_LONG(&enum_case_LinearA_value, 95);
	zend_enum_add_case_cstr(class_entry, "LinearA", &enum_case_LinearA_value);

	zval enum_case_Mahajani_value;
	ZVAL_LONG(&enum_case_Mahajani_value, 96);
	zend_enum_add_case_cstr(class_entry, "Mahajani", &enum_case_Mahajani_value);

	zval enum_case_Manichaean_value;
	ZVAL_LONG(&enum_case_Manichaean_value, 97);
	zend_enum_add_case_cstr(class_entry, "Manichaean", &enum_case_Manichaean_value);

	zval enum_case_MendeKikakui_value;
	ZVAL_LONG(&enum_case_MendeKikakui_value, 98);
	zend_enum_add_case_cstr(class_entry, "MendeKikakui", &enum_case_MendeKikakui_value);

	zval enum_case_Modi_value;
	ZVAL_LONG(&enum_case_Modi_value, 99);
	zend_enum_add_case_cstr(class_entry, "Modi", &enum_case_Modi_value);

	zval enum_case_Mro_value;
	ZVAL_LONG(&enum_case_Mro_value, 100);
	zend_enum_add_case_cstr(class_entry, "Mro", &enum_case_Mro_value);

	zval enum_case_Nabataean_value;
	ZVAL_LONG(&enum_case_Nabataean_value, 101);
	zend_enum_add_case_cstr(class_entry, "Nabataean", &enum_case_Nabataean_value);

	zval enum_case_OldNorthArabian_value;
	ZVAL_LONG(&enum_case_OldNorthArabian_value, 102);
	zend_enum_add_case_cstr(class_entry, "OldNorthArabian", &enum_case_OldNorthArabian_value);

	zval enum_case_OldPermic_value;
	ZVAL_LONG(&enum_case_OldPermic_value, 103);
	zend_enum_add_case_cstr(class_entry, "OldPermic", &enum_case_OldPermic_value);

	zval enum_case_PahawhHmong_value;
	ZVAL_LONG(&enum_case_PahawhHmong_value, 104);
	zend_enum_add_case_cstr(class_entry, "PahawhHmong", &enum_case_PahawhHmong_value);

	zval enum_case_Palmyrene_value;
	ZVAL_LONG(&enum_case_Palmyrene_value, 105);
	zend_enum_add_case_cstr(class_entry, "Palmyrene", &enum_case_Palmyrene_value);

	zval enum_case_PauCinHau_value;
	ZVAL_LONG(&enum_case_PauCinHau_value, 106);
	zend_enum_add_case_cstr(class_entry, "PauCinHau", &enum_case_PauCinHau_value);

	zval enum_case_PsalterPahlavi_value;
	ZVAL_LONG(&enum_case_PsalterPahlavi_value, 107);
	zend_enum_add_case_cstr(class_entry, "PsalterPahlavi", &enum_case_PsalterPahlavi_value);

	zval enum_case_Siddham_value;
	ZVAL_LONG(&enum_case_Siddham_value, 108);
	zend_enum_add_case_cstr(class_entry, "Siddham", &enum_case_Siddham_value);

	zval enum_case_Tirhuta_value;
	ZVAL_LONG(&enum_case_Tirhuta_value, 109);
	zend_enum_add_case_cstr(class_entry, "Tirhuta", &enum_case_Tirhuta_value);

	zval enum_case_WarangCiti_value;
	ZVAL_LONG(&enum_case_WarangCiti_value, 110);
	zend_enum_add_case_cstr(class_entry, "WarangCiti", &enum_case_WarangCiti_value);

	zval enum_case_Ahom_value;
	ZVAL_LONG(&enum_case_Ahom_value, 111);
	zend_enum_add_case_cstr(class_entry, "Ahom", &enum_case_Ahom_value);

	zval enum_case_AnatolianHieroglyphs_value;
	ZVAL_LONG(&enum_case_AnatolianHieroglyphs_value, 112);
	zend_enum_add_case_cstr(class_entry, "AnatolianHieroglyphs", &enum_case_AnatolianHieroglyphs_value);

	zval enum_case_Hatran_value;
	ZVAL_LONG(&enum_case_Hatran_value, 113);
	zend_enum_add_case_cstr(class_entry, "Hatran", &enum_case_Hatran_value);

	zval enum_case_Multani_value;
	ZVAL_LONG(&enum_case_Multani_value, 114);
	zend_enum_add_case_cstr(class_entry, "Multani", &enum_case_Multani_value);

	zval enum_case_OldHungarian_value;
	ZVAL_LONG(&enum_case_OldHungarian_value, 115);
	zend_enum_add_case_cstr(class_entry, "OldHungarian", &enum_case_OldHungarian_value);

	zval enum_case_Signwriting_value;
	ZVAL_LONG(&enum_case_Signwriting_value, 116);
	zend_enum_add_case_cstr(class_entry, "Signwriting", &enum_case_Signwriting_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoStretch(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\PangoStretch", IS_LONG, NULL);

	zval enum_case_UltraCondensed_value;
	ZVAL_LONG(&enum_case_UltraCondensed_value, 0);
	zend_enum_add_case_cstr(class_entry, "UltraCondensed", &enum_case_UltraCondensed_value);

	zval enum_case_ExtraCondensed_value;
	ZVAL_LONG(&enum_case_ExtraCondensed_value, 1);
	zend_enum_add_case_cstr(class_entry, "ExtraCondensed", &enum_case_ExtraCondensed_value);

	zval enum_case_Condensed_value;
	ZVAL_LONG(&enum_case_Condensed_value, 2);
	zend_enum_add_case_cstr(class_entry, "Condensed", &enum_case_Condensed_value);

	zval enum_case_SemiCondensed_value;
	ZVAL_LONG(&enum_case_SemiCondensed_value, 3);
	zend_enum_add_case_cstr(class_entry, "SemiCondensed", &enum_case_SemiCondensed_value);

	zval enum_case_Normal_value;
	ZVAL_LONG(&enum_case_Normal_value, 4);
	zend_enum_add_case_cstr(class_entry, "Normal", &enum_case_Normal_value);

	zval enum_case_SemiExpanded_value;
	ZVAL_LONG(&enum_case_SemiExpanded_value, 5);
	zend_enum_add_case_cstr(class_entry, "SemiExpanded", &enum_case_SemiExpanded_value);

	zval enum_case_Expanded_value;
	ZVAL_LONG(&enum_case_Expanded_value, 6);
	zend_enum_add_case_cstr(class_entry, "Expanded", &enum_case_Expanded_value);

	zval enum_case_ExtraExpanded_value;
	ZVAL_LONG(&enum_case_ExtraExpanded_value, 7);
	zend_enum_add_case_cstr(class_entry, "ExtraExpanded", &enum_case_ExtraExpanded_value);

	zval enum_case_UltraExpanded_value;
	ZVAL_LONG(&enum_case_UltraExpanded_value, 8);
	zend_enum_add_case_cstr(class_entry, "UltraExpanded", &enum_case_UltraExpanded_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoStyle(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\PangoStyle", IS_LONG, NULL);

	zval enum_case_Normal_value;
	ZVAL_LONG(&enum_case_Normal_value, 0);
	zend_enum_add_case_cstr(class_entry, "Normal", &enum_case_Normal_value);

	zval enum_case_Oblique_value;
	ZVAL_LONG(&enum_case_Oblique_value, 1);
	zend_enum_add_case_cstr(class_entry, "Oblique", &enum_case_Oblique_value);

	zval enum_case_Italic_value;
	ZVAL_LONG(&enum_case_Italic_value, 2);
	zend_enum_add_case_cstr(class_entry, "Italic", &enum_case_Italic_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoTabAlign(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\PangoTabAlign", IS_LONG, NULL);

	zval enum_case_Left_value;
	ZVAL_LONG(&enum_case_Left_value, 0);
	zend_enum_add_case_cstr(class_entry, "Left", &enum_case_Left_value);

	zval enum_case_Right_value;
	ZVAL_LONG(&enum_case_Right_value, 1);
	zend_enum_add_case_cstr(class_entry, "Right", &enum_case_Right_value);

	zval enum_case_Center_value;
	ZVAL_LONG(&enum_case_Center_value, 2);
	zend_enum_add_case_cstr(class_entry, "Center", &enum_case_Center_value);

	zval enum_case_Decimal_value;
	ZVAL_LONG(&enum_case_Decimal_value, 3);
	zend_enum_add_case_cstr(class_entry, "Decimal", &enum_case_Decimal_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoTabArray(void)
{
	zend_class_entry ce, *class_entry;

	INIT_NS_CLASS_ENTRY(ce, "Gtk4", "PangoTabArray", class_Gtk4_PangoTabArray_methods);
	class_entry = zend_register_internal_class_with_flags(&ce, NULL, ZEND_ACC_FINAL|ZEND_ACC_NOT_SERIALIZABLE);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoVariant(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\PangoVariant", IS_LONG, NULL);

	zval enum_case_Normal_value;
	ZVAL_LONG(&enum_case_Normal_value, 0);
	zend_enum_add_case_cstr(class_entry, "Normal", &enum_case_Normal_value);

	zval enum_case_SmallCaps_value;
	ZVAL_LONG(&enum_case_SmallCaps_value, 1);
	zend_enum_add_case_cstr(class_entry, "SmallCaps", &enum_case_SmallCaps_value);

	zval enum_case_AllSmallCaps_value;
	ZVAL_LONG(&enum_case_AllSmallCaps_value, 2);
	zend_enum_add_case_cstr(class_entry, "AllSmallCaps", &enum_case_AllSmallCaps_value);

	zval enum_case_PetiteCaps_value;
	ZVAL_LONG(&enum_case_PetiteCaps_value, 3);
	zend_enum_add_case_cstr(class_entry, "PetiteCaps", &enum_case_PetiteCaps_value);

	zval enum_case_AllPetiteCaps_value;
	ZVAL_LONG(&enum_case_AllPetiteCaps_value, 4);
	zend_enum_add_case_cstr(class_entry, "AllPetiteCaps", &enum_case_AllPetiteCaps_value);

	zval enum_case_Unicase_value;
	ZVAL_LONG(&enum_case_Unicase_value, 5);
	zend_enum_add_case_cstr(class_entry, "Unicase", &enum_case_Unicase_value);

	zval enum_case_TitleCaps_value;
	ZVAL_LONG(&enum_case_TitleCaps_value, 6);
	zend_enum_add_case_cstr(class_entry, "TitleCaps", &enum_case_TitleCaps_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoWeight(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\PangoWeight", IS_LONG, NULL);

	zval enum_case_Thin_value;
	ZVAL_LONG(&enum_case_Thin_value, 100);
	zend_enum_add_case_cstr(class_entry, "Thin", &enum_case_Thin_value);

	zval enum_case_Ultralight_value;
	ZVAL_LONG(&enum_case_Ultralight_value, 200);
	zend_enum_add_case_cstr(class_entry, "Ultralight", &enum_case_Ultralight_value);

	zval enum_case_Light_value;
	ZVAL_LONG(&enum_case_Light_value, 300);
	zend_enum_add_case_cstr(class_entry, "Light", &enum_case_Light_value);

	zval enum_case_Semilight_value;
	ZVAL_LONG(&enum_case_Semilight_value, 350);
	zend_enum_add_case_cstr(class_entry, "Semilight", &enum_case_Semilight_value);

	zval enum_case_Book_value;
	ZVAL_LONG(&enum_case_Book_value, 380);
	zend_enum_add_case_cstr(class_entry, "Book", &enum_case_Book_value);

	zval enum_case_Normal_value;
	ZVAL_LONG(&enum_case_Normal_value, 400);
	zend_enum_add_case_cstr(class_entry, "Normal", &enum_case_Normal_value);

	zval enum_case_Medium_value;
	ZVAL_LONG(&enum_case_Medium_value, 500);
	zend_enum_add_case_cstr(class_entry, "Medium", &enum_case_Medium_value);

	zval enum_case_Semibold_value;
	ZVAL_LONG(&enum_case_Semibold_value, 600);
	zend_enum_add_case_cstr(class_entry, "Semibold", &enum_case_Semibold_value);

	zval enum_case_Bold_value;
	ZVAL_LONG(&enum_case_Bold_value, 700);
	zend_enum_add_case_cstr(class_entry, "Bold", &enum_case_Bold_value);

	zval enum_case_Ultrabold_value;
	ZVAL_LONG(&enum_case_Ultrabold_value, 800);
	zend_enum_add_case_cstr(class_entry, "Ultrabold", &enum_case_Ultrabold_value);

	zval enum_case_Heavy_value;
	ZVAL_LONG(&enum_case_Heavy_value, 900);
	zend_enum_add_case_cstr(class_entry, "Heavy", &enum_case_Heavy_value);

	zval enum_case_Ultraheavy_value;
	ZVAL_LONG(&enum_case_Ultraheavy_value, 1000);
	zend_enum_add_case_cstr(class_entry, "Ultraheavy", &enum_case_Ultraheavy_value);

	return class_entry;
}

static zend_class_entry *register_class_Gtk4_PangoWrapMode(void)
{
	zend_class_entry *class_entry = zend_register_internal_enum("Gtk4\\PangoWrapMode", IS_LONG, NULL);

	zval enum_case_Word_value;
	ZVAL_LONG(&enum_case_Word_value, 0);
	zend_enum_add_case_cstr(class_entry, "Word", &enum_case_Word_value);

	zval enum_case_Char_value;
	ZVAL_LONG(&enum_case_Char_value, 1);
	zend_enum_add_case_cstr(class_entry, "Char", &enum_case_Char_value);

	zval enum_case_WordChar_value;
	ZVAL_LONG(&enum_case_WordChar_value, 2);
	zend_enum_add_case_cstr(class_entry, "WordChar", &enum_case_WordChar_value);

	return class_entry;
}
