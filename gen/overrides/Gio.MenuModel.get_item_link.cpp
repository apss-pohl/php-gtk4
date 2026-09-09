/**
 * public function get_item_link(int $item_index, string $link): ?GMenuModel
 * Queries the item at position $item_index in $model for the link specified by $link.
 *
 * Generated but for the index check: GLib reads the item array unguarded, so an index outside
 * the model ends the process instead of raising. See the prelude.
 */
ZEND_METHOD(Gtk4_GMenuModel, get_item_link) {
  zend_long item_index;
  zend_string *link;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_LONG(item_index)
  Z_PARAM_STR(link)
  ZEND_PARSE_PARAMETERS_END();
  GMenuModel *self = PHPGTK_SELF(GMenuModel, G_TYPE_MENU_MODEL);
  if (!phpgtk::check_range<gint>(item_index, 1)) RETURN_THROWS();
  if (!phpgtk::check_utf8(link, 2)) RETURN_THROWS();
  if (!menu_item_index_is_valid(self, item_index)) RETURN_THROWS();
  GMenuModel *phpgtk_ret =
      g_menu_model_get_item_link(self, static_cast<gint>(item_index), ZSTR_VAL(link));
  wrap(phpgtk_ret != nullptr ? G_OBJECT(phpgtk_ret) : nullptr, return_value);
  if (phpgtk_ret != nullptr) g_object_unref(phpgtk_ret);  // the handle took its own ref
}
