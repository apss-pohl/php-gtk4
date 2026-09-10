/**
 * public function get_glyphs(): PangoGlyphString
 * The glyphs the node draws, as a glyph string.
 *
 * GSK answers with a bare array of glyph infos plus a count, which is also all a text node
 * keeps: gsk_text_node_new() copies the infos out of the glyph string it is given and never
 * reads its log clusters. So a glyph string holding that array is the node's glyphs without
 * loss - the same one `new GskTextNode()` would take to build this node again - except that its
 * log clusters are zero, because the node has none to give back.
 */
ZEND_METHOD(Gtk4_GskTextNode, get_glyphs) {
  ZEND_PARSE_PARAMETERS_NONE();
  auto *self = static_cast<GskRenderNode *>(fundamental_self(execute_data));
  if (self == nullptr) RETURN_THROWS();
  guint n = 0;
  const PangoGlyphInfo *infos = gsk_text_node_get_glyphs(self, &n);
  PangoGlyphString *glyphs = pango_glyph_string_new();
  pango_glyph_string_set_size(glyphs, static_cast<int>(n));
  if (n > 0) {
    memcpy(glyphs->glyphs, infos, n * sizeof(PangoGlyphInfo));
    memset(glyphs->log_clusters, 0, n * sizeof(int));
  }
  wrap_boxed(PANGO_TYPE_GLYPH_STRING, glyphs, return_value);
  pango_glyph_string_free(glyphs);
}
