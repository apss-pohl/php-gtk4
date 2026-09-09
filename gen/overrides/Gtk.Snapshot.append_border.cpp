/**
 * public function append_border(GskRoundedRect $outline, array $widths, array $colors): void
 * Appends a border inside $outline: $widths are the four widths (top, right, bottom, left) as
 * floats, $colors the four GdkRGBA colours in the same order - what a GskBorderNode is.
 *
 * GIR takes two fixed-size C arrays; PHP lists of exactly four are the same thing.
 */
ZEND_METHOD(Gtk4_GtkSnapshot, append_border) {
  zval *outline;
  zval *widths;
  zval *colors;
  ZEND_PARSE_PARAMETERS_START(3, 3)
  Z_PARAM_OBJECT_OF_CLASS(outline, boxed_class_for_type(PHPGTK_TYPE_GSK_ROUNDED_RECT)->ce)
  Z_PARAM_ARRAY(widths)
  Z_PARAM_ARRAY(colors)
  ZEND_PARSE_PARAMETERS_END();
  GtkSnapshot *self = PHPGTK_SELF(GtkSnapshot, GTK_TYPE_SNAPSHOT);
  std::array<float, 4> border_width{};
  std::array<GdkRGBA, 4> border_color{};
  if (!border_from_php(widths, 2, colors, 3, border_width, border_color)) RETURN_THROWS();
  gtk_snapshot_append_border(
      self, static_cast<const GskRoundedRect *>(unwrap_boxed(outline, PHPGTK_TYPE_GSK_ROUNDED_RECT)),
      border_width.data(), border_color.data());
}
