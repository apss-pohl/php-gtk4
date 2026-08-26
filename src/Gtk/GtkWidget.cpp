// Gtk4\GtkWidget - the abstract widget base.
#include "php_gtk4.h"
#include "core/object.h"
#include "core/variant.h"
#include "core/enums.h"
#include "core/collections.h"

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
 * Gtk4\GtkWidget::set_hexpand(bool $expand): void
 *
 * Whether the widget takes the horizontal space its parent has spare.
 */
WIDGET_METHOD(set_hexpand) {
  bool expand = false;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_BOOL(expand)
  ZEND_PARSE_PARAMETERS_END();
  SELF_WIDGET;
  gtk_widget_set_hexpand(w, static_cast<gboolean>(expand));
}

/**
 * Gtk4\GtkWidget::get_hexpand(): bool
 */
WIDGET_METHOD(get_hexpand) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  RETURN_BOOL(gtk_widget_get_hexpand(w));
}

/**
 * Gtk4\GtkWidget::set_vexpand(bool $expand): void
 */
WIDGET_METHOD(set_vexpand) {
  bool expand = false;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_BOOL(expand)
  ZEND_PARSE_PARAMETERS_END();
  SELF_WIDGET;
  gtk_widget_set_vexpand(w, static_cast<gboolean>(expand));
}

/**
 * Gtk4\GtkWidget::get_vexpand(): bool
 */
WIDGET_METHOD(get_vexpand) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  RETURN_BOOL(gtk_widget_get_vexpand(w));
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
  strv_to_php(gtk_widget_get_css_classes(w), Transfer::Full, return_value);
}

/**
 * Gtk4\GtkWidget::set_css_classes(array $classes): void
 */
WIDGET_METHOD(set_css_classes) {
  zval *classes;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_ARRAY(classes)
  ZEND_PARSE_PARAMETERS_END();
  SELF_WIDGET;
  char **strv = strv_from_php(classes);
  if (strv == nullptr) RETURN_THROWS();
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

// set_halign / set_valign share the enum parsing.
static void set_align(INTERNAL_FUNCTION_PARAMETERS, void (*setter)(GtkWidget *, GtkAlign)) {
  zval *align;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(align, enum_class_for_type(GTK_TYPE_ALIGN))
  ZEND_PARSE_PARAMETERS_END();
  SELF_WIDGET;
  gint value = 0;
  if (!enum_from_php(align, GTK_TYPE_ALIGN, &value)) RETURN_THROWS();
  setter(w, static_cast<GtkAlign>(value));
}

/**
 * Gtk4\GtkWidget::set_halign(GtkAlign $align): void
 */
WIDGET_METHOD(set_halign) {
  set_align(INTERNAL_FUNCTION_PARAM_PASSTHRU, gtk_widget_set_halign);
}

/**
 * Gtk4\GtkWidget::get_halign(): GtkAlign
 */
WIDGET_METHOD(get_halign) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  enum_to_php(GTK_TYPE_ALIGN, gtk_widget_get_halign(w), return_value);
}

/**
 * Gtk4\GtkWidget::set_valign(GtkAlign $align): void
 */
WIDGET_METHOD(set_valign) {
  set_align(INTERNAL_FUNCTION_PARAM_PASSTHRU, gtk_widget_set_valign);
}

/**
 * Gtk4\GtkWidget::get_valign(): GtkAlign
 */
WIDGET_METHOD(get_valign) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  enum_to_php(GTK_TYPE_ALIGN, gtk_widget_get_valign(w), return_value);
}

/**
 * Gtk4\GtkWidget::get_size_request(): array
 *
 * The size request as [width, height] (out parameters become a list).
 */
WIDGET_METHOD(get_size_request) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  int width = 0;
  int height = 0;
  gtk_widget_get_size_request(w, &width, &height);
  array_init_size(return_value, 2);
  add_next_index_long(return_value, width);
  add_next_index_long(return_value, height);
}

/**
 * Gtk4\GtkWidget::list_mnemonic_labels(): array
 *
 * Widgets whose mnemonic activates this widget.
 */
WIDGET_METHOD(list_mnemonic_labels) {
  ZEND_PARSE_PARAMETERS_NONE();
  SELF_WIDGET;
  glist_to_php(gtk_widget_list_mnemonic_labels(w), GTK_TYPE_WIDGET, Transfer::Container,
               return_value);
}
