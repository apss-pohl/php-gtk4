/**
 * public function insert_markup(GtkTextIter $iter, string $markup, int $len = -1): void
 * Inserts $len bytes of Pango markup at position $iter (-1, the default, inserts all of
 * it; a given count must end on a UTF-8 character boundary). $iter is revalidated to
 * point to the end of the inserted text.
 */
ZEND_METHOD(Gtk4_GtkTextBuffer, insert_markup) {
  zval *iter;
  zend_string *markup;
  zend_long len = -1;
  ZEND_PARSE_PARAMETERS_START(2, 3)
  Z_PARAM_OBJECT_OF_CLASS(iter, boxed_class_for_type(GTK_TYPE_TEXT_ITER)->ce)
  Z_PARAM_STR(markup)
  Z_PARAM_OPTIONAL
  Z_PARAM_LONG(len)
  ZEND_PARSE_PARAMETERS_END();
  GtkTextBuffer *self = PHPGTK_SELF(GtkTextBuffer, GTK_TYPE_TEXT_BUFFER);
  gpointer iter_b = unwrap_boxed(iter, GTK_TYPE_TEXT_ITER);
  if (iter_b == nullptr) RETURN_THROWS();
  if (!phpgtk::check_utf8(markup, 2)) RETURN_THROWS();
  if (!check_text_len(markup, len, 3)) RETURN_THROWS();
  gtk_text_buffer_insert_markup(self, static_cast<GtkTextIter *>(iter_b), ZSTR_VAL(markup),
                                static_cast<int>(len));
}
