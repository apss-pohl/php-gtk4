/**
 * public function add_from_string(string $buffer): bool
 * Parse $buffer and merge what it describes into $builder.
 *
 * GTK takes the length separately; a PHP string carries its own, and letting the script pass one
 * meant `add_from_string($xml, PHP_INT_MAX)` read past the end of the buffer.
 */
ZEND_METHOD(Gtk4_GtkBuilder, add_from_string) {
  zend_string *buffer;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(buffer)
  ZEND_PARSE_PARAMETERS_END();
  GtkBuilder *self = PHPGTK_SELF(GtkBuilder, GTK_TYPE_BUILDER);
  if (!check_utf8(buffer, 1)) RETURN_THROWS();
  GError *error = nullptr;
  const gboolean ok = gtk_builder_add_from_string(self, ZSTR_VAL(buffer),
                                                  static_cast<gssize>(ZSTR_LEN(buffer)), &error);
  if (ok == FALSE) {
    throw_gerror(error);
    RETURN_THROWS();
  }
  RETURN_TRUE;
}
