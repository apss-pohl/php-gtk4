/**
 * public function set_text(string $text, int $len = -1): void
 * Deletes the current contents of the buffer and inserts $text instead. $len is a byte
 * count into $text (-1, the default, inserts all of it) and must end on a UTF-8 character
 * boundary. This is automatically marked as an irreversible action in the undo stack.
 */
ZEND_METHOD(Gtk4_GtkTextBuffer, set_text) {
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
  gtk_text_buffer_set_text(self, ZSTR_VAL(text), static_cast<int>(len));
}
