/**
 * public static function parse(string $string): ?GskTransform
 * The transform to_string() wrote (or CSS transform syntax: "translate(5, 5) scale(2)"), or
 * null when $string is not one - an empty string is the identity, also null.
 *
 * GIR answers through an out parameter next to a boolean; PHP gets the transform or null.
 */
ZEND_METHOD(Gtk4_GskTransform, parse) {
  zend_string *text;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(text)
  ZEND_PARSE_PARAMETERS_END();
  if (!phpgtk::check_utf8(text, 1)) RETURN_THROWS();
  GskTransform *out = nullptr;
  if (!gsk_transform_parse(ZSTR_VAL(text), &out) || out == nullptr) RETURN_NULL();
  wrap_boxed(GSK_TYPE_TRANSFORM, out, return_value);
  gsk_transform_unref(out);
}
