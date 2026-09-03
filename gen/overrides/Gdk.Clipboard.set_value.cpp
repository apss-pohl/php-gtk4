/**
 * public function set_value(mixed $value, ?string $type = null): void
 * Put $value on the clipboard, typed as $type (inferred from $value when omitted).
 *
 * The typed counterpart of set_text()/set_texture(): GIR takes a GValue, and the marshaller is
 * what turns an ordinary PHP value into one. The type is named as everywhere else -
 * "string"/"int"/"float"/"bool" or a registered class name.
 */
ZEND_METHOD(Gtk4_GdkClipboard, set_value) {
  zval *value;
  zend_string *type = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 2)
  Z_PARAM_ZVAL(value)
  Z_PARAM_OPTIONAL
  Z_PARAM_STR_OR_NULL(type)
  ZEND_PARSE_PARAMETERS_END();
  GdkClipboard *self = PHPGTK_SELF(GdkClipboard, GDK_TYPE_CLIPBOARD);

  GType t = 0;
  if (type != nullptr) {
    t = gtype_from_php_name(type, 2);
    if (t == 0) RETURN_THROWS();
  } else {
    switch (Z_TYPE_P(value)) {
      case IS_STRING: t = G_TYPE_STRING; break;
      case IS_LONG: t = G_TYPE_INT64; break;
      case IS_DOUBLE: t = G_TYPE_DOUBLE; break;
      case IS_TRUE:
      case IS_FALSE: t = G_TYPE_BOOLEAN; break;
      case IS_OBJECT: {
        GObject *obj = unwrap(value, G_TYPE_OBJECT);
        if (obj == nullptr) RETURN_THROWS();
        t = G_OBJECT_TYPE(obj);
        break;
      }
      default:
        zend_argument_type_error(1, "must be a string, int, float, bool or GObject handle to be "
                                    "set without naming a type");
        RETURN_THROWS();
    }
  }
  GValue gv = G_VALUE_INIT;
  if (!to_gvalue(value, t, &gv)) RETURN_THROWS();
  gdk_clipboard_set_value(self, &gv);
  g_value_unset(&gv);
}
