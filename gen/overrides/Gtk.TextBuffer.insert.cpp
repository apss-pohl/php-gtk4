/**
 * public function insert(GtkTextIter $iter, string $text, int $len = -1): void
 * Inserts $len bytes of $text at position $iter (-1, the default, inserts all of it; a
 * given count must end on a UTF-8 character boundary). Emits the "insert-text" signal;
 * $iter is revalidated to point to the end of the inserted text.
 */
ZEND_METHOD(Gtk4_GtkTextBuffer, insert) {
  zval *iter;
  zend_string *text;
  zend_long len = -1;
  ZEND_PARSE_PARAMETERS_START(2, 3)
  Z_PARAM_OBJECT_OF_CLASS(iter, boxed_class_for_type(GTK_TYPE_TEXT_ITER)->ce)
  Z_PARAM_STR(text)
  Z_PARAM_OPTIONAL
  Z_PARAM_LONG(len)
  ZEND_PARSE_PARAMETERS_END();
  GtkTextBuffer *self = PHPGTK_SELF(GtkTextBuffer, GTK_TYPE_TEXT_BUFFER);
  gpointer iter_b = unwrap_boxed(iter, GTK_TYPE_TEXT_ITER);
  if (iter_b == nullptr) RETURN_THROWS();
  if (!phpgtk::check_utf8(text, 2)) RETURN_THROWS();
  if (!check_text_len(text, len, 3)) RETURN_THROWS();
  gtk_text_buffer_insert(self, static_cast<GtkTextIter *>(iter_b), ZSTR_VAL(text),
                         static_cast<int>(len));
}
