/**
 * public function __construct()
 * Creates a new empty builder object.
 *
 * Signal handlers named by a `.ui` document resolve only through `set_handlers()`: the builder
 * gets php-gtk4's own scope here, never GTK's C-symbol lookup.
 */
ZEND_METHOD(Gtk4_GtkBuilder, __construct) {
  ZEND_PARSE_PARAMETERS_NONE();
  GObject *obj = subtype_new(ZEND_THIS, nullptr);
  if (obj == nullptr) {
    if (EG(exception) != nullptr) RETURN_THROWS();
    obj = G_OBJECT(gtk_builder_new());
  }
  if (obj == nullptr) {
    zend_throw_error(nullptr,
                     "%s(): GTK refused to create the object (see the "
                     "CRITICAL above)",
                     ZSTR_VAL(EX(func)->common.function_name));
    RETURN_THROWS();
  }
  install_php_scope(GTK_BUILDER(obj), nullptr);
  attach_new(object_from_zval(ZEND_THIS), obj);
}
