// The item type comes in as a PHP class name (PhpValue::class), not a GType.
namespace {
// Resolve "Gtk4\PhpValue" / "PhpValue" / any registered class name to a GObject GType.
GType item_type_from_string(zend_string *name) {
  const char *s = ZSTR_VAL(name);
  const char *slash = strrchr(s, '\\');
  const char *gtype_name = slash != nullptr ? slash + 1 : s;
  zend_class_entry *ce = class_for_gtype_name(gtype_name);
  GType t = ce != nullptr ? gtype_for_class(ce) : 0;
  if (t == 0 || g_type_is_a(t, G_TYPE_OBJECT) == FALSE) {
    zend_argument_value_error(1, "must name a registered GObject class, \"%s\" given", s);
    return 0;
  }
  return t;
}
}  // namespace
