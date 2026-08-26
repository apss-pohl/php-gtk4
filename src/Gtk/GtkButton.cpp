// Gtk4\GtkButton
#include "php_gtk4.h"
#include "core/object.h"
#include "children.h"

using namespace phpgtk;

/**
 * Gtk4\GtkButton::__construct(?string $label = null)
 */
ZEND_METHOD(Gtk4_GtkButton, __construct) {
  zend_string *label = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_STR_OR_NULL(label)
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *b = label != nullptr ? gtk_button_new_with_label(ZSTR_VAL(label)) : gtk_button_new();
  attach_new(object_from_zval(ZEND_THIS), G_OBJECT(b));
}

/**
 * Gtk4\GtkButton::set_label(?string $label): void
 */
ZEND_METHOD(Gtk4_GtkButton, set_label) {
  zend_string *label = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR_OR_NULL(label)
  ZEND_PARSE_PARAMETERS_END();
  GtkButton *b = PHPGTK_SELF(GtkButton, GTK_TYPE_BUTTON);
  gtk_button_set_label(b, label != nullptr ? ZSTR_VAL(label) : "");
}

/**
 * Gtk4\GtkButton::get_label(): ?string
 */
ZEND_METHOD(Gtk4_GtkButton, get_label) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkButton *b = PHPGTK_SELF(GtkButton, GTK_TYPE_BUTTON);
  PHPGTK_RETURN_STRING_OR_NULL(gtk_button_get_label(b));
}

/**
 * Gtk4\GtkButton::set_child(?GtkWidget $child): void
 */
ZEND_METHOD(Gtk4_GtkButton, set_child) {
  zval *child = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(child, class_for_gtype_name("GtkWidget"))
  ZEND_PARSE_PARAMETERS_END();
  GtkButton *b = PHPGTK_SELF(GtkButton, GTK_TYPE_BUTTON);
  GtkWidget *c = nullptr;
  if (!widget_or_null(child, &c)) RETURN_THROWS();
  gtk_button_set_child(b, c);
}

/**
 * Gtk4\GtkButton::get_child(): ?GtkWidget
 */
ZEND_METHOD(Gtk4_GtkButton, get_child) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkButton *b = PHPGTK_SELF(GtkButton, GTK_TYPE_BUTTON);
  GtkWidget *c = gtk_button_get_child(b);
  wrap(c != nullptr ? G_OBJECT(c) : nullptr, return_value);
}
