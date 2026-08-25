// Gtk4\GtkWidget - the abstract widget base.
#include "php_gtk4.h"
#include "core/object.h"
#include "core/variant.h"

using namespace phpgtk;

#define WIDGET_METHOD(name) ZEND_METHOD(Gtk4_GtkWidget, name)
#define SELF_WIDGET GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET)

/**
 * Gtk4\GtkWidget::show(): void
 */
WIDGET_METHOD(show) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  gtk_widget_set_visible(w, TRUE);
}

/**
 * Gtk4\GtkWidget::hide(): void
 */
WIDGET_METHOD(hide) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  gtk_widget_set_visible(w, FALSE);
}

/**
 * Gtk4\GtkWidget::set_visible(bool $visible): void
 */
WIDGET_METHOD(set_visible) {
  bool visible;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_BOOL(visible)
  ZEND_PARSE_PARAMETERS_END();
  SELF_WIDGET;
  gtk_widget_set_visible(w, visible);
}

/**
 * Gtk4\GtkWidget::get_visible(): bool
 */
WIDGET_METHOD(get_visible) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  RETURN_BOOL(gtk_widget_get_visible(w));
}

/**
 * Gtk4\GtkWidget::is_visible(): bool
 *
 * Whether the widget and all its ancestors are visible.
 */
WIDGET_METHOD(is_visible) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  RETURN_BOOL(gtk_widget_is_visible(w));
}

/**
 * Gtk4\GtkWidget::set_sensitive(bool $sensitive): void
 */
WIDGET_METHOD(set_sensitive) {
  bool sensitive;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_BOOL(sensitive)
  ZEND_PARSE_PARAMETERS_END();
  SELF_WIDGET;
  gtk_widget_set_sensitive(w, sensitive);
}

/**
 * Gtk4\GtkWidget::get_sensitive(): bool
 */
WIDGET_METHOD(get_sensitive) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  RETURN_BOOL(gtk_widget_get_sensitive(w));
}

/**
 * Gtk4\GtkWidget::set_size_request(int $width, int $height): void
 *
 * Minimum size in pixels; -1 = natural size.
 */
WIDGET_METHOD(set_size_request) {
  zend_long width, height;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_LONG(width)
  Z_PARAM_LONG(height)
  ZEND_PARSE_PARAMETERS_END();
  SELF_WIDGET;
  gtk_widget_set_size_request(w, static_cast<int>(width), static_cast<int>(height));
}

/**
 * Gtk4\GtkWidget::get_parent(): ?GtkWidget
 */
WIDGET_METHOD(get_parent) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  GtkWidget *p = gtk_widget_get_parent(w);
  wrap(p != nullptr ? G_OBJECT(p) : nullptr, return_value);
}

/**
 * Gtk4\GtkWidget::get_root(): ?GtkWidget
 *
 * The toplevel GtkWindow (or other root) this widget is in, if any.
 */
WIDGET_METHOD(get_root) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  GtkRoot *root = gtk_widget_get_root(w);
  wrap(root != nullptr ? G_OBJECT(root) : nullptr, return_value);
}

/**
 * Gtk4\GtkWidget::grab_focus(): bool
 */
WIDGET_METHOD(grab_focus) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  RETURN_BOOL(gtk_widget_grab_focus(w));
}

/**
 * Gtk4\GtkWidget::activate(): bool
 *
 * Activate the widget (a button emits `clicked`); false if it is not activatable.
 */
WIDGET_METHOD(activate) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  RETURN_BOOL(gtk_widget_activate(w));
}

/**
 * Gtk4\GtkWidget::set_tooltip_text(?string $text): void
 */
WIDGET_METHOD(set_tooltip_text) {
  zend_string *text = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR_OR_NULL(text)
  ZEND_PARSE_PARAMETERS_END();
  SELF_WIDGET;
  gtk_widget_set_tooltip_text(w, text != nullptr ? ZSTR_VAL(text) : nullptr);
}

/**
 * Gtk4\GtkWidget::get_tooltip_text(): ?string
 */
WIDGET_METHOD(get_tooltip_text) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  const char *t = gtk_widget_get_tooltip_text(w);
  if (t == nullptr) RETURN_NULL();
  RETURN_STRING(t);
}

/**
 * Gtk4\GtkWidget::set_name(?string $name): void
 */
WIDGET_METHOD(set_name) {
  zend_string *name = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR_OR_NULL(name)
  ZEND_PARSE_PARAMETERS_END();
  SELF_WIDGET;
  gtk_widget_set_name(w, name != nullptr ? ZSTR_VAL(name) : nullptr);
}

/**
 * Gtk4\GtkWidget::get_name(): ?string
 */
WIDGET_METHOD(get_name) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  const char *n = gtk_widget_get_name(w);
  if (n == nullptr) RETURN_NULL();
  RETURN_STRING(n);
}

/**
 * Gtk4\GtkWidget::add_css_class(string $cssClass): void
 */
WIDGET_METHOD(add_css_class) {
  zend_string *cls;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(cls)
  ZEND_PARSE_PARAMETERS_END();
  SELF_WIDGET;
  gtk_widget_add_css_class(w, ZSTR_VAL(cls));
}

/**
 * Gtk4\GtkWidget::remove_css_class(string $cssClass): void
 */
WIDGET_METHOD(remove_css_class) {
  zend_string *cls;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(cls)
  ZEND_PARSE_PARAMETERS_END();
  SELF_WIDGET;
  gtk_widget_remove_css_class(w, ZSTR_VAL(cls));
}

/**
 * Gtk4\GtkWidget::has_css_class(string $cssClass): bool
 */
WIDGET_METHOD(has_css_class) {
  zend_string *cls;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(cls)
  ZEND_PARSE_PARAMETERS_END();
  SELF_WIDGET;
  RETURN_BOOL(gtk_widget_has_css_class(w, ZSTR_VAL(cls)));
}

/**
 * Gtk4\GtkWidget::queue_draw(): void
 *
 * Queue a redraw of the widget.
 */
WIDGET_METHOD(queue_draw) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  gtk_widget_queue_draw(w);
}

/**
 * Gtk4\GtkWidget::get_css_classes(): array
 */
WIDGET_METHOD(get_css_classes) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  char **classes = gtk_widget_get_css_classes(w);
  array_init(return_value);
  for (char **c = classes; c != nullptr && *c != nullptr; c++)
    add_next_index_string(return_value, *c);
  g_strfreev(classes);
}

/**
 * Gtk4\GtkWidget::set_css_classes(array $classes): void
 */
WIDGET_METHOD(set_css_classes) {
  HashTable *classes;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_ARRAY_HT(classes)
  ZEND_PARSE_PARAMETERS_END();
  SELF_WIDGET;
  GStrvBuilder *builder = g_strv_builder_new();
  zval *item;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
  ZEND_HASH_FOREACH_VAL(classes, item) {
    zend_string *s = zval_get_string(item);
    g_strv_builder_add(builder, ZSTR_VAL(s));
    zend_string_release(s);
  }
  ZEND_HASH_FOREACH_END();
  char **strv = g_strv_builder_end(builder);
  g_strv_builder_unref(builder);
  gtk_widget_set_css_classes(w, const_cast<const char **>(strv));
  g_strfreev(strv);
}

/**
 * Gtk4\GtkWidget::activate_action(string $name, mixed $parameter = null): bool
 *
 * Activate a named action ("app.quit", "win.close") found on this widget's ancestry. $parameter is
 * converted to the action's parameter type. False if no such action.
 */
WIDGET_METHOD(activate_action) {
  zend_string *name;
  zval *param = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 2)
  Z_PARAM_STR(name)
  Z_PARAM_OPTIONAL
  Z_PARAM_ZVAL(param)
  ZEND_PARSE_PARAMETERS_END();
  SELF_WIDGET;
  GVariant *v = nullptr;
  if (param != nullptr && Z_TYPE_P(param) != IS_NULL) {
    v = php_to_variant(param, nullptr);
    if (v == nullptr) RETURN_THROWS();
    g_variant_ref_sink(v);
  }
  const gboolean found = gtk_widget_activate_action_variant(w, ZSTR_VAL(name), v);
  if (v != nullptr) g_variant_unref(v);
  if (EG(exception) != nullptr) RETURN_THROWS();
  RETURN_BOOL(found);
}
