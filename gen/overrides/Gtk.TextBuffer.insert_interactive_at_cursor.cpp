/**
 * public function insert_interactive_at_cursor(string $text, int $len, bool $default_editable): bool
 * Inserts $len bytes of $text at the cursor like `insert_interactive`, but only if the
 * cursor position is editable. Pass -1 for all of $text; a given count must end on a
 * UTF-8 character boundary.
 */
ZEND_METHOD(Gtk4_GtkTextBuffer, insert_interactive_at_cursor) {
  zend_string *text;
  zend_long len;
  bool default_editable;
  ZEND_PARSE_PARAMETERS_START(3, 3)
  Z_PARAM_STR(text)
  Z_PARAM_LONG(len)
  Z_PARAM_BOOL(default_editable)
  ZEND_PARSE_PARAMETERS_END();
  GtkTextBuffer *self = PHPGTK_SELF(GtkTextBuffer, GTK_TYPE_TEXT_BUFFER);
  if (!phpgtk::check_utf8(text, 1)) RETURN_THROWS();
  if (!check_text_len(text, len, 2)) RETURN_THROWS();
  RETURN_BOOL(gtk_text_buffer_insert_interactive_at_cursor(
      self, ZSTR_VAL(text), static_cast<int>(len), default_editable));
}
