/**
 * public function set_gtypes(array $types): void
 * The types this target accepts, named as in the constructor.
 */
ZEND_METHOD(Gtk4_GtkDropTarget, set_gtypes) {
  HashTable *types;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_ARRAY_HT(types)
  ZEND_PARSE_PARAMETERS_END();
  GtkDropTarget *self = PHPGTK_SELF(GtkDropTarget, GTK_TYPE_DROP_TARGET);

  std::vector<GType> resolved;
  resolved.reserve(zend_hash_num_elements(types));
  zval *entry = nullptr;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
  ZEND_HASH_FOREACH_VAL(types, entry) {
    if (Z_TYPE_P(entry) != IS_STRING) {
      zend_argument_type_error(1, "must be a list of type names");
      RETURN_THROWS();
    }
    const GType t = gtype_from_php_name(Z_STR_P(entry), 1);
    if (t == 0) RETURN_THROWS();
    resolved.push_back(t);
  }
  ZEND_HASH_FOREACH_END();
  gtk_drop_target_set_gtypes(self, resolved.empty() ? nullptr : resolved.data(), resolved.size());
}
