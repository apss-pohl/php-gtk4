// Gtk4\GtkWindow - the single hand-written widget of milestone 1.
// NOTE: extends GObject directly until the GtkWidget layer exists (TODO.md).
#include "php_gtk4.h"
#include "core/object.h"

using namespace phpgtk;

ZEND_METHOD(Gtk4_GtkWindow, __construct) {
  ZEND_PARSE_PARAMETERS_NONE();
  attach(object_from_zval(ZEND_THIS), G_OBJECT(gtk_window_new()));
}

ZEND_METHOD(Gtk4_GtkWindow, set_title) {
  zend_string *title = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR_OR_NULL(title)
  ZEND_PARSE_PARAMETERS_END();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  gtk_window_set_title(w, title != nullptr ? ZSTR_VAL(title) : nullptr);
}

ZEND_METHOD(Gtk4_GtkWindow, get_title) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  const char *t = gtk_window_get_title(w);
  if (t == nullptr) RETURN_NULL();
  RETURN_STRING(t);
}

ZEND_METHOD(Gtk4_GtkWindow, set_default_size) {
  zend_long width, height;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_LONG(width)
  Z_PARAM_LONG(height)
  ZEND_PARSE_PARAMETERS_END();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  gtk_window_set_default_size(w, static_cast<int>(width), static_cast<int>(height));
}

ZEND_METHOD(Gtk4_GtkWindow, set_child) {
  zval *child = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(child, class_for_gtype_name("GObject"))
  ZEND_PARSE_PARAMETERS_END();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  GObject *c = nullptr;
  if (child != nullptr) {
    c = unwrap(child, GTK_TYPE_WIDGET);
    if (c == nullptr) RETURN_THROWS();
  }
  gtk_window_set_child(w, c != nullptr ? GTK_WIDGET(c) : nullptr);
}

ZEND_METHOD(Gtk4_GtkWindow, present) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  gtk_window_present(w);
}

ZEND_METHOD(Gtk4_GtkWindow, close) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  gtk_window_close(w);
}

ZEND_METHOD(Gtk4_GtkWindow, destroy) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  gtk_window_destroy(w);
}
