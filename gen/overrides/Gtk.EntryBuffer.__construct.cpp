/**
 * public function __construct(?string $initial_chars, int $n_initial_chars)
 * Create a new `GtkEntryBuffer` object.
 */
ZEND_METHOD(Gtk4_GtkEntryBuffer, __construct) {
  zend_string *initial_chars = nullptr;
  zend_long n_initial_chars;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_STR_OR_NULL(initial_chars)
  Z_PARAM_LONG(n_initial_chars)
  ZEND_PARSE_PARAMETERS_END();
  // Not gtk_entry_buffer_new() first: that always instantiates the native GType, so a PHP
  // subclass' vfunc_insert_text()/vfunc_delete_text()/... would never run - silently, because
  // the slots themselves are bound and nothing reports them as unbound. n_initial_chars is a
  // length rather than a construct property, so the text is applied after the instance exists,
  // which is all gtk_entry_buffer_new() does with its two arguments.
  if (initial_chars != nullptr && !phpgtk::check_utf8(initial_chars, 1)) RETURN_THROWS();
  if (!phpgtk::check_range<int>(n_initial_chars, 2)) RETURN_THROWS();
  GObject *obj = subtype_new(ZEND_THIS, nullptr);
  if (obj == nullptr) {
    if (EG(exception) != nullptr) RETURN_THROWS();
    obj =
        G_OBJECT(gtk_entry_buffer_new(initial_chars != nullptr ? ZSTR_VAL(initial_chars) : nullptr,
                                      static_cast<int>(n_initial_chars)));
  } else if (initial_chars != nullptr) {
    gtk_entry_buffer_set_text(GTK_ENTRY_BUFFER(obj), ZSTR_VAL(initial_chars),
                              static_cast<int>(n_initial_chars));
  }
  if (obj == nullptr) {
    zend_throw_error(nullptr,
                     "%s(): GTK refused to create the object (see the "
                     "CRITICAL above)",
                     ZSTR_VAL(EX(func)->common.function_name));
    RETURN_THROWS();
  }
  attach_new(object_from_zval(ZEND_THIS), obj);
}
