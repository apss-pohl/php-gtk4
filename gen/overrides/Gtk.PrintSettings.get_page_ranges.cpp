/**
 * public function get_page_ranges(): array
 * The page ranges set_page_ranges() took, as `[int $start, int $end]` pairs; empty when none.
 *
 * @return list<array{int, int}>
 */
ZEND_METHOD(Gtk4_GtkPrintSettings, get_page_ranges) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkPrintSettings *self = PHPGTK_SELF(GtkPrintSettings, GTK_TYPE_PRINT_SETTINGS);
  int n = 0;
  GtkPageRange *ranges = gtk_print_settings_get_page_ranges(self, &n);
  array_init_size(return_value, n < 0 ? 0 : static_cast<uint32_t>(n));
  for (int i = 0; i < n; i++) {
    zval pair;
    array_init_size(&pair, 2);
    // NOLINTNEXTLINE(cppcoreguidelines-pro-bounds-pointer-arithmetic) GTK's array of n ranges
    add_next_index_long(&pair, ranges[i].start);
    // NOLINTNEXTLINE(cppcoreguidelines-pro-bounds-pointer-arithmetic) GTK's array of n ranges
    add_next_index_long(&pair, ranges[i].end);
    add_next_index_zval(return_value, &pair);
  }
  g_free(ranges);
}
