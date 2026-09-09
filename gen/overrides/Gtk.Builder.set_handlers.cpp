/**
 * public function set_handlers(array $handlers): void
 * Resolve every `<signal handler="name">` in what is parsed next against $handlers.
 *
 * GTK 4 connects the signals of a .ui document while parsing it and asks its GtkBuilderScope for
 * each handler, so this has to be called *before* `add_from_string()`/`add_from_file()`. A
 * handler name the array does not have is a `GError` from the parse - it is never looked up as
 * a C function the way GTK's own scope would, so a document cannot name what it may call.
 * `swapped="yes"` and `object="..."` are refused for a PHP handler - a closure already carries
 * what it captured with `use`.
 */
ZEND_METHOD(Gtk4_GtkBuilder, set_handlers) {
  zval *handlers;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_ARRAY(handlers)
  ZEND_PARSE_PARAMETERS_END();
  GtkBuilder *self = PHPGTK_SELF(GtkBuilder, GTK_TYPE_BUILDER);

  zend_string *name = nullptr;
  zval *entry = nullptr;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
  ZEND_HASH_FOREACH_STR_KEY_VAL(Z_ARRVAL_P(handlers), name, entry) {
    if (name == nullptr) {
      zend_argument_value_error(1, "must be keyed by handler name, not by position");
      RETURN_THROWS();
    }
    if (!zend_is_callable(entry, 0, nullptr)) {
      zend_argument_type_error(1, "handler '%s' is not callable", ZSTR_VAL(name));
      RETURN_THROWS();
    }
  }
  ZEND_HASH_FOREACH_END();

  install_php_scope(self, handlers);
}
