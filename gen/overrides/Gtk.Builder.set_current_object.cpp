/**
 * public function set_current_object(GObject $current_object): void
 * Set the object the next `<template>` document is built for.
 *
 * GIR annotates the parameter nullable, but GTK's own precondition is
 * `g_return_if_fail (current_object || G_IS_OBJECT (current_object))` - which rejects NULL
 * (observed on GTK 4.14.5): passing it emits a CRITICAL and changes nothing. So the
 * parameter is not nullable here; there is no way to clear the current object.
 */
ZEND_METHOD(Gtk4_GtkBuilder, set_current_object) {
  zval *current_object;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(current_object, class_for_gtype(G_TYPE_OBJECT))
  ZEND_PARSE_PARAMETERS_END();
  GtkBuilder *self = PHPGTK_SELF(GtkBuilder, GTK_TYPE_BUILDER);
  GObject *object = unwrap(current_object, G_TYPE_OBJECT);
  if (object == nullptr) RETURN_THROWS();
  gtk_builder_set_current_object(self, object);
}
