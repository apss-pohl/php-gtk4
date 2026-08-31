/**
 * public function insert_interactive(GtkTextIter $iter, string $text, int $len, bool $default_editable): bool
 * Inserts $len bytes of $text at $iter like `insert`, but only if the location is
 * editable ($default_editable decides where no tag says). Pass -1 for all of $text; a
 * given count must end on a UTF-8 character boundary.
 */
ZEND_METHOD(Gtk4_GtkTextBuffer, insert_interactive) {
  zval *iter;
  zend_string *text;
  zend_long len;
  bool default_editable;
  ZEND_PARSE_PARAMETERS_START(4, 4)
  Z_PARAM_OBJECT_OF_CLASS(iter, boxed_class_for_type(GTK_TYPE_TEXT_ITER)->ce)
  Z_PARAM_STR(text)
  Z_PARAM_LONG(len)
  Z_PARAM_BOOL(default_editable)
  ZEND_PARSE_PARAMETERS_END();
  GtkTextBuffer *self = PHPGTK_SELF(GtkTextBuffer, GTK_TYPE_TEXT_BUFFER);
  gpointer iter_b = unwrap_boxed(iter, GTK_TYPE_TEXT_ITER);
  if (iter_b == nullptr) RETURN_THROWS();
  if (!phpgtk::check_utf8(text, 2)) RETURN_THROWS();
  if (!check_text_len(text, len, 3)) RETURN_THROWS();
  RETURN_BOOL(gtk_text_buffer_insert_interactive(self, static_cast<GtkTextIter *>(iter_b),
                                                 ZSTR_VAL(text), static_cast<int>(len),
                                                 default_editable));
}
