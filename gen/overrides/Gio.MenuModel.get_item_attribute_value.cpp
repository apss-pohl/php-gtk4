/**
 * public function get_item_attribute_value(int $item_index, string $attribute, ?string $expected_type): mixed
 * Queries the item at position $item_index in $model for the attribute specified by $attribute.
 *
 * Generated but for the index check: GLib reads the item array unguarded, so an index outside
 * the model ends the process instead of raising. See the prelude.
 */
ZEND_METHOD(Gtk4_GMenuModel, get_item_attribute_value) {
  zend_long item_index;
  zend_string *attribute;
  zend_string *expected_type = nullptr;
  ZEND_PARSE_PARAMETERS_START(3, 3)
  Z_PARAM_LONG(item_index)
  Z_PARAM_STR(attribute)
  Z_PARAM_STR_OR_NULL(expected_type)
  ZEND_PARSE_PARAMETERS_END();
  GMenuModel *self = PHPGTK_SELF(GMenuModel, G_TYPE_MENU_MODEL);
  if (!phpgtk::check_range<gint>(item_index, 1)) RETURN_THROWS();
  if (!phpgtk::check_utf8(attribute, 2)) RETURN_THROWS();
  if (!menu_item_index_is_valid(self, item_index)) RETURN_THROWS();
  GVariantType *expected_type_t = nullptr;
  if (expected_type != nullptr) {
    if (!g_variant_type_string_is_valid(ZSTR_VAL(expected_type))) {
      zend_argument_value_error(3, "must be a valid GVariant type string, \"%s\" given",
                                ZSTR_VAL(expected_type));
      RETURN_THROWS();
    }
    expected_type_t = g_variant_type_new(ZSTR_VAL(expected_type));
  }
  GVariant *call_result = g_menu_model_get_item_attribute_value(
      self, static_cast<gint>(item_index), ZSTR_VAL(attribute), expected_type_t);
  if (expected_type_t != nullptr) g_variant_type_free(expected_type_t);
  GVariant *phpgtk_ret = call_result;
  if (phpgtk_ret == nullptr) RETURN_NULL();
  variant_to_php(phpgtk_ret, return_value);
  g_variant_unref(phpgtk_ret);
}
