/**
 * public function set_line_index(int $byte_on_line): void
 * Moves $iter within its line, to the given byte index. The index counts from the start of
 * the line and must land on a UTF-8 character boundary; one past the end of the line is
 * clamped by GTK, a negative one is a `g_error()` that would end the process.
 */
ZEND_METHOD(Gtk4_GtkTextIter, set_line_index) {
  zend_long byte_on_line;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_LONG(byte_on_line)
  ZEND_PARSE_PARAMETERS_END();
  GtkTextIter *self = PHPGTK_BOXED_SELF(GtkTextIter);
  if (!phpgtk::check_range<int>(byte_on_line, 1)) RETURN_THROWS();
  if (byte_on_line < 0) {
    zend_argument_value_error(1, "must be greater than or equal to 0");
    RETURN_THROWS();
  }
  gtk_text_iter_set_line_index(self, static_cast<int>(byte_on_line));
}
