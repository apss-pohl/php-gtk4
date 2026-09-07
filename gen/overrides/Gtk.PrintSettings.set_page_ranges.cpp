/**
 * public function set_page_ranges(array $page_ranges): void
 * The pages to print, as `[int $start, int $end]` pairs of zero-based, inclusive page numbers
 * (what GtkPrintPages::Ranges selects).
 *
 * GIR takes a C array of GtkPageRange with its length; a PHP list of pairs is the same thing.
 */
ZEND_METHOD(Gtk4_GtkPrintSettings, set_page_ranges) {
  zval *ranges;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_ARRAY(ranges)
  ZEND_PARSE_PARAMETERS_END();
  GtkPrintSettings *self = PHPGTK_SELF(GtkPrintSettings, GTK_TYPE_PRINT_SETTINGS);
  std::vector<GtkPageRange> list;
  zend_long index = 0;
  zval *item;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
  ZEND_HASH_FOREACH_VAL(Z_ARRVAL_P(ranges), item) {
    zval *start = Z_TYPE_P(item) == IS_ARRAY ? zend_hash_index_find(Z_ARRVAL_P(item), 0) : nullptr;
    zval *end = Z_TYPE_P(item) == IS_ARRAY ? zend_hash_index_find(Z_ARRVAL_P(item), 1) : nullptr;
    if (start == nullptr || end == nullptr || Z_TYPE_P(start) != IS_LONG || Z_TYPE_P(end) != IS_LONG ||
        zend_hash_num_elements(Z_ARRVAL_P(item)) != 2) {
      zend_argument_value_error(1, "item " ZEND_LONG_FMT " must be an [int $start, int $end] pair", index);
      RETURN_THROWS();
    }
    if (Z_LVAL_P(start) < 0 || Z_LVAL_P(end) < Z_LVAL_P(start) || Z_LVAL_P(end) > INT_MAX) {
      zend_argument_value_error(1, "item " ZEND_LONG_FMT " must run from a page number >= 0 to one not before it",
                                index);
      RETURN_THROWS();
    }
    list.push_back(GtkPageRange{.start = static_cast<int>(Z_LVAL_P(start)), .end = static_cast<int>(Z_LVAL_P(end))});
    index++;
  }
  ZEND_HASH_FOREACH_END();
  gtk_print_settings_set_page_ranges(self, list.empty() ? nullptr : list.data(), static_cast<int>(list.size()));
}
