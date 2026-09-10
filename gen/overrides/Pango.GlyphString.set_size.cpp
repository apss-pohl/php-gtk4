/**
 * public function set_size(int $new_len): void
 * Resize the glyph string to $new_len glyphs.
 *
 * Pango reallocates and leaves the new entries as the allocator left them, so a string that
 * grew would answer extents() and get_width() from uninitialised memory; here every grown entry
 * is PANGO_GLYPH_EMPTY with no geometry and cluster 0 - the glyph Pango draws and measures as
 * nothing - so a fresh string measures as empty until something shapes into it. A negative
 * length is refused before Pango's own assertion sees it.
 */
ZEND_METHOD(Gtk4_PangoGlyphString, set_size) {
  zend_long new_len;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_LONG(new_len)
  ZEND_PARSE_PARAMETERS_END();
  PangoGlyphString *self = PHPGTK_BOXED_SELF(PangoGlyphString);
  if (!phpgtk::check_range<int>(new_len, 1)) RETURN_THROWS();
  if (new_len < 0) {
    zend_argument_value_error(1, "must be greater than or equal to 0");
    RETURN_THROWS();
  }
  const int old_len = self->num_glyphs;
  pango_glyph_string_set_size(self, static_cast<int>(new_len));
  if (new_len > old_len) {
    const auto grown = static_cast<size_t>(new_len - old_len);
    memset(self->glyphs + old_len, 0, grown * sizeof(PangoGlyphInfo));
    memset(self->log_clusters + old_len, 0, grown * sizeof(int));
    for (int i = old_len; i < new_len; i++) self->glyphs[i].glyph = PANGO_GLYPH_EMPTY;
  }
}
