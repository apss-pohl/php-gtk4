// Gtk4\GtkWidget - the abstract widget base.
#include "php_gtk4.h"
#include "core/object.h"
#include "core/variant.h"
#include "core/enums.h"
#include "core/collections.h"

using namespace phpgtk;

/**
 * Gtk4\GtkWidget::show(): void
 */
ZEND_METHOD(Gtk4_GtkWidget, show) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  gtk_widget_set_visible(w, TRUE);
}

/**
 * Gtk4\GtkWidget::hide(): void
 */
ZEND_METHOD(Gtk4_GtkWidget, hide) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  gtk_widget_set_visible(w, FALSE);
}

/**
 * Gtk4\GtkWidget::set_visible(bool $visible): void
 */
ZEND_METHOD(Gtk4_GtkWidget, set_visible) {
  bool visible;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_BOOL(visible)
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  gtk_widget_set_visible(w, visible);
}

/**
 * Gtk4\GtkWidget::get_visible(): bool
 */
ZEND_METHOD(Gtk4_GtkWidget, get_visible) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  RETURN_BOOL(gtk_widget_get_visible(w));
}

/**
 * Gtk4\GtkWidget::is_visible(): bool
 *
 * Whether the widget and all its ancestors are visible.
 */
ZEND_METHOD(Gtk4_GtkWidget, is_visible) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  RETURN_BOOL(gtk_widget_is_visible(w));
}

/**
 * Gtk4\GtkWidget::set_sensitive(bool $sensitive): void
 */
ZEND_METHOD(Gtk4_GtkWidget, set_sensitive) {
  bool sensitive;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_BOOL(sensitive)
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  gtk_widget_set_sensitive(w, sensitive);
}

/**
 * Gtk4\GtkWidget::get_sensitive(): bool
 */
ZEND_METHOD(Gtk4_GtkWidget, get_sensitive) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  RETURN_BOOL(gtk_widget_get_sensitive(w));
}

/**
 * Gtk4\GtkWidget::set_size_request(int $width, int $height): void
 *
 * Minimum size in pixels; -1 = natural size.
 */
ZEND_METHOD(Gtk4_GtkWidget, set_size_request) {
  zend_long width, height;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_LONG(width)
  Z_PARAM_LONG(height)
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  gtk_widget_set_size_request(w, static_cast<int>(width), static_cast<int>(height));
}

/**
 * Gtk4\GtkWidget::get_parent(): ?GtkWidget
 */
ZEND_METHOD(Gtk4_GtkWidget, get_parent) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  GtkWidget *p = gtk_widget_get_parent(w);
  wrap(p != nullptr ? G_OBJECT(p) : nullptr, return_value);
}

/**
 * Gtk4\GtkWidget::get_root(): ?GtkWidget
 *
 * The toplevel GtkWindow (or other root) this widget is in, if any.
 */
ZEND_METHOD(Gtk4_GtkWidget, get_root) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  GtkRoot *root = gtk_widget_get_root(w);
  wrap(root != nullptr ? G_OBJECT(root) : nullptr, return_value);
}

/**
 * Gtk4\GtkWidget::grab_focus(): bool
 */
ZEND_METHOD(Gtk4_GtkWidget, grab_focus) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  RETURN_BOOL(gtk_widget_grab_focus(w));
}

/**
 * Gtk4\GtkWidget::activate(): bool
 *
 * Activate the widget (a button emits `clicked`); false if it is not activatable.
 */
ZEND_METHOD(Gtk4_GtkWidget, activate) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  RETURN_BOOL(gtk_widget_activate(w));
}

/**
 * Gtk4\GtkWidget::set_tooltip_text(?string $text): void
 */
ZEND_METHOD(Gtk4_GtkWidget, set_tooltip_text) {
  zend_string *text = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR_OR_NULL(text)
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  gtk_widget_set_tooltip_text(w, text != nullptr ? ZSTR_VAL(text) : nullptr);
}

/**
 * Gtk4\GtkWidget::get_tooltip_text(): ?string
 */
ZEND_METHOD(Gtk4_GtkWidget, get_tooltip_text) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  PHPGTK_RETURN_STRING_OR_NULL(gtk_widget_get_tooltip_text(w));
}

/**
 * Gtk4\GtkWidget::set_name(?string $name): void
 */
ZEND_METHOD(Gtk4_GtkWidget, set_name) {
  zend_string *name = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR_OR_NULL(name)
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  gtk_widget_set_name(w, name != nullptr ? ZSTR_VAL(name) : nullptr);
}

/**
 * Gtk4\GtkWidget::get_name(): ?string
 */
ZEND_METHOD(Gtk4_GtkWidget, get_name) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  PHPGTK_RETURN_STRING_OR_NULL(gtk_widget_get_name(w));
}

/**
 * Gtk4\GtkWidget::add_css_class(string $css_class): void
 */
ZEND_METHOD(Gtk4_GtkWidget, add_css_class) {
  zend_string *cls;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(cls)
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  gtk_widget_add_css_class(w, ZSTR_VAL(cls));
}

/**
 * Gtk4\GtkWidget::remove_css_class(string $css_class): void
 */
ZEND_METHOD(Gtk4_GtkWidget, remove_css_class) {
  zend_string *cls;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(cls)
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  gtk_widget_remove_css_class(w, ZSTR_VAL(cls));
}

/**
 * Gtk4\GtkWidget::has_css_class(string $css_class): bool
 */
ZEND_METHOD(Gtk4_GtkWidget, has_css_class) {
  zend_string *cls;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(cls)
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  RETURN_BOOL(gtk_widget_has_css_class(w, ZSTR_VAL(cls)));
}

/**
 * Gtk4\GtkWidget::set_hexpand(bool $expand): void
 *
 * Whether the widget takes the horizontal space its parent has spare.
 */
ZEND_METHOD(Gtk4_GtkWidget, set_hexpand) {
  bool expand = false;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_BOOL(expand)
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  gtk_widget_set_hexpand(w, static_cast<gboolean>(expand));
}

/**
 * Gtk4\GtkWidget::get_hexpand(): bool
 */
ZEND_METHOD(Gtk4_GtkWidget, get_hexpand) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  RETURN_BOOL(gtk_widget_get_hexpand(w));
}

/**
 * Gtk4\GtkWidget::set_vexpand(bool $expand): void
 */
ZEND_METHOD(Gtk4_GtkWidget, set_vexpand) {
  bool expand = false;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_BOOL(expand)
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  gtk_widget_set_vexpand(w, static_cast<gboolean>(expand));
}

/**
 * Gtk4\GtkWidget::get_vexpand(): bool
 */
ZEND_METHOD(Gtk4_GtkWidget, get_vexpand) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  RETURN_BOOL(gtk_widget_get_vexpand(w));
}

/**
 * Gtk4\GtkWidget::queue_draw(): void
 *
 * Queue a redraw of the widget.
 */
ZEND_METHOD(Gtk4_GtkWidget, queue_draw) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  gtk_widget_queue_draw(w);
}

/**
 * Gtk4\GtkWidget::get_css_classes(): array
 */
ZEND_METHOD(Gtk4_GtkWidget, get_css_classes) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  strv_to_php(gtk_widget_get_css_classes(w), Transfer::Full, return_value);
}

/**
 * Gtk4\GtkWidget::set_css_classes(array $classes): void
 */
ZEND_METHOD(Gtk4_GtkWidget, set_css_classes) {
  zval *classes;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_ARRAY(classes)
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
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
ZEND_METHOD(Gtk4_GtkWidget, activate_action) {
  zend_string *name;
  zval *param = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 2)
  Z_PARAM_STR(name)
  Z_PARAM_OPTIONAL
  Z_PARAM_ZVAL(param)
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
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
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  gint value = 0;
  if (!enum_from_php(align, GTK_TYPE_ALIGN, &value)) RETURN_THROWS();
  setter(w, static_cast<GtkAlign>(value));
}

/**
 * Gtk4\GtkWidget::set_halign(GtkAlign $align): void
 */
ZEND_METHOD(Gtk4_GtkWidget, set_halign) {
  set_align(INTERNAL_FUNCTION_PARAM_PASSTHRU, gtk_widget_set_halign);
}

/**
 * Gtk4\GtkWidget::get_halign(): GtkAlign
 */
ZEND_METHOD(Gtk4_GtkWidget, get_halign) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  enum_to_php(GTK_TYPE_ALIGN, gtk_widget_get_halign(w), return_value);
}

/**
 * Gtk4\GtkWidget::set_valign(GtkAlign $align): void
 */
ZEND_METHOD(Gtk4_GtkWidget, set_valign) {
  set_align(INTERNAL_FUNCTION_PARAM_PASSTHRU, gtk_widget_set_valign);
}

/**
 * Gtk4\GtkWidget::get_valign(): GtkAlign
 */
ZEND_METHOD(Gtk4_GtkWidget, get_valign) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  enum_to_php(GTK_TYPE_ALIGN, gtk_widget_get_valign(w), return_value);
}

/**
 * Gtk4\GtkWidget::get_size_request(): array
 *
 * The size request as [width, height] (out parameters become a list).
 */
ZEND_METHOD(Gtk4_GtkWidget, get_size_request) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
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
ZEND_METHOD(Gtk4_GtkWidget, list_mnemonic_labels) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWidget *w = PHPGTK_SELF(GtkWidget, GTK_TYPE_WIDGET);
  glist_to_php(gtk_widget_list_mnemonic_labels(w), GTK_TYPE_WIDGET, Transfer::Container,
               return_value);
}
