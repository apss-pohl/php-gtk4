/**
 * public function insert_at_cursor(string $text, int $len = -1): void
 * Inserts $len bytes of $text at the cursor (-1, the default, inserts all of it; a given
 * count must end on a UTF-8 character boundary). Calls `insert`, using the current cursor
 * position as the insertion point.
 */
ZEND_METHOD(Gtk4_GtkTextBuffer, insert_at_cursor) {
  zend_string *text;
  zend_long len = -1;
  ZEND_PARSE_PARAMETERS_START(1, 2)
  Z_PARAM_STR(text)
  Z_PARAM_OPTIONAL
  Z_PARAM_LONG(len)
  ZEND_PARSE_PARAMETERS_END();
  GtkTextBuffer *self = PHPGTK_SELF(GtkTextBuffer, GTK_TYPE_TEXT_BUFFER);
  if (!phpgtk::check_utf8(text, 1)) RETURN_THROWS();
  if (!check_text_len(text, len, 2)) RETURN_THROWS();
  gtk_text_buffer_insert_at_cursor(self, ZSTR_VAL(text), static_cast<int>(len));
}
