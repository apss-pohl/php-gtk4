// Prelude for Gtk4\GMenuModel: the item-index precondition its two item queries share.

namespace {

// g_menu_model_get_item_link() and g_menu_model_get_item_attribute_value() have no defence
// against an index outside the model: a negative one indexes before the item array and
// segfaults, and one past the end reaches g_menu_model_real_*'s `g_assert_not_reached()`,
// which is a g_error() and ends the process. Neither is a CRITICAL a test could survive, so
// the range is checked here - a value the script can build must not end it (CLAUDE.md, "A
// value PHP can build must not end the process").
bool menu_item_index_is_valid(GMenuModel *self, zend_long item_index) {
  gint n_items = g_menu_model_get_n_items(self);
  if (item_index < 0 || item_index >= n_items) {
    if (n_items == 0) {
      zend_argument_value_error(1, "cannot be used on an empty menu, " ZEND_LONG_FMT " given",
                                item_index);
    } else {
      zend_argument_value_error(1, "must be between 0 and %d, " ZEND_LONG_FMT " given", n_items - 1,
                                item_index);
    }
    return false;
  }
  return true;
}

}  // namespace
