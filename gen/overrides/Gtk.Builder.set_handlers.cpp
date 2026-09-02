/**
 * public function set_handlers(array $handlers): void
 * Resolve every `<signal handler="name">` in what is parsed next against $handlers.
 *
 * GTK 4 connects the signals of a .ui document while parsing it and asks its GtkBuilderScope for
 * each handler, so this has to be called *before* `add_from_string()`/`add_from_file()`; a handler
 * a later call does not find still fails the way GTK words it. `swapped="yes"` and `object="..."`
 * are refused for a PHP handler - a closure already carries what it captured with `use`.
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

  auto *scope = reinterpret_cast<PhpBuilderScope *>(g_object_new(php_builder_scope_type(), nullptr));
  ZVAL_COPY(&scope->handlers, handlers);
  gtk_builder_set_scope(self, GTK_BUILDER_SCOPE(scope));
  g_object_unref(scope);  // the builder holds it now
}
