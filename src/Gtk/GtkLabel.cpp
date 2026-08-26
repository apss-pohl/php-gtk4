// Gtk4\GtkLabel
#include "php_gtk4.h"
#include "core/object.h"

using namespace phpgtk;

/**
 * Gtk4\GtkLabel::__construct(?string $text = null)
 */
ZEND_METHOD(Gtk4_GtkLabel, __construct) {
  zend_string *text = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_STR_OR_NULL(text)
  ZEND_PARSE_PARAMETERS_END();
  attach_new(object_from_zval(ZEND_THIS),
             G_OBJECT(gtk_label_new(text != nullptr ? ZSTR_VAL(text) : nullptr)));
}

/**
 * Gtk4\GtkLabel::set_text(string $text): void
 */
ZEND_METHOD(Gtk4_GtkLabel, set_text) {
  zend_string *text;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(text)
  ZEND_PARSE_PARAMETERS_END();
  GtkLabel *l = PHPGTK_SELF(GtkLabel, GTK_TYPE_LABEL);
  gtk_label_set_text(l, ZSTR_VAL(text));
}

/**
 * Gtk4\GtkLabel::get_text(): string
 */
ZEND_METHOD(Gtk4_GtkLabel, get_text) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkLabel *l = PHPGTK_SELF(GtkLabel, GTK_TYPE_LABEL);
  RETURN_STRING(gtk_label_get_text(l));
}

/**
 * Gtk4\GtkLabel::set_markup(string $markup): void
 *
 * Set Pango markup, e.g. `<b>bold</b>`.
 */
ZEND_METHOD(Gtk4_GtkLabel, set_markup) {
  zend_string *markup;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR(markup)
  ZEND_PARSE_PARAMETERS_END();
  GtkLabel *l = PHPGTK_SELF(GtkLabel, GTK_TYPE_LABEL);
  gtk_label_set_markup(l, ZSTR_VAL(markup));
}

/**
 * Gtk4\GtkLabel::set_selectable(bool $selectable): void
 */
ZEND_METHOD(Gtk4_GtkLabel, set_selectable) {
  bool selectable;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_BOOL(selectable)
  ZEND_PARSE_PARAMETERS_END();
  GtkLabel *l = PHPGTK_SELF(GtkLabel, GTK_TYPE_LABEL);
  gtk_label_set_selectable(l, selectable);
}

/**
 * Gtk4\GtkLabel::get_selectable(): bool
 */
ZEND_METHOD(Gtk4_GtkLabel, get_selectable) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkLabel *l = PHPGTK_SELF(GtkLabel, GTK_TYPE_LABEL);
  RETURN_BOOL(gtk_label_get_selectable(l));
}

/**
 * Gtk4\GtkLabel::get_selection_bounds(): ?array
 *
 * Selected character range as [start, end], or null when nothing is selected (a boolean-returning
 * C function with out parameters returns the outs or null).
 */
ZEND_METHOD(Gtk4_GtkLabel, get_selection_bounds) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkLabel *l = PHPGTK_SELF(GtkLabel, GTK_TYPE_LABEL);
  int start = 0;
  int end = 0;
  if (!gtk_label_get_selection_bounds(l, &start, &end)) RETURN_NULL();
  array_init_size(return_value, 2);
  add_next_index_long(return_value, start);
  add_next_index_long(return_value, end);
}

/**
 * Gtk4\GtkLabel::select_region(int $start, int $end): void
 */
ZEND_METHOD(Gtk4_GtkLabel, select_region) {
  zend_long start, end;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_LONG(start)
  Z_PARAM_LONG(end)
  ZEND_PARSE_PARAMETERS_END();
  GtkLabel *l = PHPGTK_SELF(GtkLabel, GTK_TYPE_LABEL);
  gtk_label_select_region(l, static_cast<int>(start), static_cast<int>(end));
}
