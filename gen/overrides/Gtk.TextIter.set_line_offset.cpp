/**
 * public function set_line_offset(int $char_on_line): void
 * Moves $iter within its line, to the given character offset. One past the end of the line
 * is clamped by GTK, a negative offset is a `g_error()` that would end the process.
 */
ZEND_METHOD(Gtk4_GtkTextIter, set_line_offset) {
  zend_long char_on_line;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_LONG(char_on_line)
  ZEND_PARSE_PARAMETERS_END();
  GtkTextIter *self = PHPGTK_BOXED_SELF(GtkTextIter);
  if (!phpgtk::check_range<int>(char_on_line, 1)) RETURN_THROWS();
  if (char_on_line < 0) {
    zend_argument_value_error(1, "must be greater than or equal to 0");
    RETURN_THROWS();
  }
  gtk_text_iter_set_line_offset(self, static_cast<int>(char_on_line));
}
