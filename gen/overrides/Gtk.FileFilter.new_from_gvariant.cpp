/**
 * public static function new_from_gvariant(mixed $variant): GtkFileFilter
 * Deserialize a file filter from a GVariant.
 *
 * The variant is what to_gvariant() produced: `[name, [[rule, pattern], ...]]`. GTK wants
 * exactly `(sa(us))` and inference cannot build a tuple from a PHP list, so the type is named
 * here - without it the filter comes back empty. The argument is required: GTK dereferences it.
 */
ZEND_METHOD(Gtk4_GtkFileFilter, new_from_gvariant) {
  zval *variant = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_ZVAL(variant)
  ZEND_PARSE_PARAMETERS_END();
  GVariant *variant_v = php_to_variant(variant, G_VARIANT_TYPE("(sa(us))"));
  if (variant_v == nullptr) RETURN_THROWS();
  g_variant_ref_sink(variant_v);
  GtkFileFilter *filter = gtk_file_filter_new_from_gvariant(variant_v);
  g_variant_unref(variant_v);
  GObject *obj = G_OBJECT(filter);
  wrap(obj, return_value);
  if (obj != nullptr) g_object_unref(obj);  // the handle took its own reference
}
