/**
 * public function add_objects_from_string(string $buffer, array $object_ids): bool
 * Parse $buffer, merging only the objects $object_ids names (and whatever they need).
 *
 * The length comes from the PHP string, as in {@see GtkBuilder::add_from_string()}.
 */
ZEND_METHOD(Gtk4_GtkBuilder, add_objects_from_string) {
  zend_string *buffer;
  zval *object_ids;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_STR(buffer)
  Z_PARAM_ARRAY(object_ids)
  ZEND_PARSE_PARAMETERS_END();
  GtkBuilder *self = PHPGTK_SELF(GtkBuilder, GTK_TYPE_BUILDER);
  if (!check_utf8(buffer, 1)) RETURN_THROWS();
  char **ids = strv_from_php(object_ids);
  if (ids == nullptr) RETURN_THROWS();
  GError *error = nullptr;
  const gboolean ok = gtk_builder_add_objects_from_string(
      self, ZSTR_VAL(buffer), static_cast<gssize>(ZSTR_LEN(buffer)),
      const_cast<const char **>(ids), &error);
  g_strfreev(ids);
  if (ok == FALSE) {
    throw_gerror(error);
    RETURN_THROWS();
  }
  RETURN_TRUE;
}
