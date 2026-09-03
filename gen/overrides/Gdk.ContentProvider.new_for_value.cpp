/**
 * public static function new_for_value(mixed $value, ?string $type = null): GdkContentProvider
 * A provider offering $value, typed as $type (inferred from $value when omitted).
 *
 * GIR takes a GValue, which PHP has no spelling for; the marshaller converts one either way, so
 * the payload is an ordinary PHP value and the GType is named the way a list store's item type
 * is - "string"/"int"/"float"/"bool" or a registered class name.
 */
ZEND_METHOD(Gtk4_GdkContentProvider, new_for_value) {
  zval *value;
  zend_string *type = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 2)
  Z_PARAM_ZVAL(value)
  Z_PARAM_OPTIONAL
  Z_PARAM_STR_OR_NULL(type)
  ZEND_PARSE_PARAMETERS_END();

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
      case IS_OBJECT: t = G_OBJECT_TYPE(unwrap(value, G_TYPE_OBJECT)); break;
      default:
        zend_argument_type_error(1, "must be a string, int, float, bool or GObject handle to be "
                                    "offered without naming a type");
        RETURN_THROWS();
    }
    if (EG(exception) != nullptr) RETURN_THROWS();
  }
  GValue gv = G_VALUE_INIT;
  if (!to_gvalue(value, t, &gv)) RETURN_THROWS();
  GdkContentProvider *provider = gdk_content_provider_new_for_value(&gv);
  g_value_unset(&gv);
  wrap(G_OBJECT(provider), return_value);
  g_object_unref(provider);
}
