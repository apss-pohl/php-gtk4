/**
 * public function get_gtypes(): array
 * The types this target accepts, in the spelling set_gtypes() takes.
 *
 * @return list<string>
 */
ZEND_METHOD(Gtk4_GtkDropTarget, get_gtypes) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkDropTarget *self = PHPGTK_SELF(GtkDropTarget, GTK_TYPE_DROP_TARGET);
  gsize count = 0;
  const GType *types = gtk_drop_target_get_gtypes(self, &count);
  array_init_size(return_value, static_cast<uint32_t>(count));
  for (gsize i = 0; types != nullptr && i < count; i++) {
    zend_string *name = php_name_for_gtype(types[i]);
    add_next_index_str(return_value, name);
  }
}
