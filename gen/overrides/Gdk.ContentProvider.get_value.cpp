/**
 * public function get_value(?string $type = null): mixed
 * The value this provider offers, as $type (the first type it advertises when omitted).
 *
 * GIR's GValue is caller-allocated *and* caller-typed: GDK fills it only if it can supply that
 * type, and asserts on an uninitialised one. So the type is asked for explicitly or taken from
 * the provider's own formats, and the marshaller turns the result back into a PHP value.
 */
ZEND_METHOD(Gtk4_GdkContentProvider, get_value) {
  zend_string *type = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_STR_OR_NULL(type)
  ZEND_PARSE_PARAMETERS_END();
  GdkContentProvider *self = PHPGTK_SELF(GdkContentProvider, GDK_TYPE_CONTENT_PROVIDER);

  GType wanted = 0;
  if (type != nullptr) {
    wanted = gtype_from_php_name(type, 1);
    if (wanted == 0) RETURN_THROWS();
  } else {
    GdkContentFormats *formats = gdk_content_provider_ref_formats(self);
    gsize count = 0;
    const GType *types = gdk_content_formats_get_gtypes(formats, &count);
    if (types != nullptr && count > 0) wanted = types[0];
    gdk_content_formats_unref(formats);
    if (wanted == 0) {
      zend_throw_exception(spl_ce_LogicException,
                           "Gtk4\\GdkContentProvider::get_value(): this provider offers no typed "
                           "value; name the type you want",
                           0);
      RETURN_THROWS();
    }
  }

  GValue gv = G_VALUE_INIT;
  g_value_init(&gv, wanted);
  GError *error = nullptr;
  if (gdk_content_provider_get_value(self, &gv, &error) == FALSE) {
    g_value_unset(&gv);
    throw_gerror(error);
    RETURN_THROWS();
  }
  to_php(&gv, return_value);
  g_value_unset(&gv);
}
