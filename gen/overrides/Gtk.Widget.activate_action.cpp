/**
 * public function activate_action(string $name, mixed $args = null): bool
 * Looks up the action in the action groups associated with $widget and its ancestors, and
 * activates it.
 *
 * $args is converted to the parameter type the action declares - a class action's
 * (`list.activate-item` takes a `u`, `list.select-item` a `(ubb)` tuple from a list), or the
 * type the group inserted under the name's prefix, the application window (`win.`) or the
 * application (`app.`) declares for it - and inferred from the value only when nothing answers
 * to the name. A value that does not fit is a TypeError, a missing required parameter a
 * ValueError.
 */
ZEND_METHOD(Gtk4_GtkWidget, activate_action) {
  zend_string *name;
  zval *args = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 2)
  Z_PARAM_STR(name)
  Z_PARAM_OPTIONAL
  Z_PARAM_ZVAL(args)
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *self = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  if (!phpgtk::check_utf8(name, 1)) RETURN_THROWS();
  const GVariantType *type = widget_action_parameter_type(self, ZSTR_VAL(name));
  const bool null_given = args == nullptr || Z_TYPE_P(args) == IS_NULL;
  if (type != nullptr && null_given && g_variant_type_is_maybe(type) == FALSE) {
    zend_argument_value_error(2, "is required: action '%s' takes a parameter of type %s",
                              ZSTR_VAL(name), g_variant_type_peek_string(type));
    RETURN_THROWS();
  }
  GVariant *args_v = nullptr;
  if (!null_given) {
    args_v = php_to_variant(args, type);
    if (args_v == nullptr) RETURN_THROWS();
    g_variant_ref_sink(args_v);
  }
  gboolean call_result = gtk_widget_activate_action_variant(self, ZSTR_VAL(name), args_v);
  if (args_v != nullptr) g_variant_unref(args_v);
  RETURN_BOOL(call_result);
}
