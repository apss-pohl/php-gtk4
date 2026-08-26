// Gtk4\GtkWindow - the single hand-written widget of milestone 1.
#include "php_gtk4.h"
#include "core/object.h"

using namespace phpgtk;

/**
 * Gtk4\GtkWindow::__construct(?GtkApplication $application = null)
 */
ZEND_METHOD(Gtk4_GtkWindow, __construct) {
  zval *application = nullptr;
  ZEND_PARSE_PARAMETERS_START(0, 1)
  Z_PARAM_OPTIONAL
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(application, class_for_gtype_name("GtkApplication"))
  ZEND_PARSE_PARAMETERS_END();
  GtkWidget *w = gtk_window_new();
  attach_new(object_from_zval(ZEND_THIS), G_OBJECT(w));
  if (application != nullptr) {
    GObject *app = unwrap(application, GTK_TYPE_APPLICATION);
    if (app == nullptr) RETURN_THROWS();
    gtk_window_set_application(GTK_WINDOW(w), GTK_APPLICATION(app));
  }
}

/**
 * Gtk4\GtkWindow::set_application(?GtkApplication $application): void
 */
ZEND_METHOD(Gtk4_GtkWindow, set_application) {
  zval *application = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(application, class_for_gtype_name("GtkApplication"))
  ZEND_PARSE_PARAMETERS_END();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  GObject *app = nullptr;
  if (application != nullptr) {
    app = unwrap(application, GTK_TYPE_APPLICATION);
    if (app == nullptr) RETURN_THROWS();
  }
  gtk_window_set_application(w, app != nullptr ? GTK_APPLICATION(app) : nullptr);
}

/**
 * Gtk4\GtkWindow::get_application(): ?GtkApplication
 */
ZEND_METHOD(Gtk4_GtkWindow, get_application) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  GtkApplication *app = gtk_window_get_application(w);
  wrap(app != nullptr ? G_OBJECT(app) : nullptr, return_value);
}

/**
 * Gtk4\GtkWindow::set_title(?string $title): void
 */
ZEND_METHOD(Gtk4_GtkWindow, set_title) {
  zend_string *title = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_STR_OR_NULL(title)
  ZEND_PARSE_PARAMETERS_END();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  gtk_window_set_title(w, title != nullptr ? ZSTR_VAL(title) : nullptr);
}

/**
 * Gtk4\GtkWindow::get_title(): ?string
 */
ZEND_METHOD(Gtk4_GtkWindow, get_title) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  const char *t = gtk_window_get_title(w);
  if (t == nullptr) RETURN_NULL();
  RETURN_STRING(t);
}

/**
 * Gtk4\GtkWindow::set_default_size(int $width, int $height): void
 *
 * Default size in pixels; -1 to unset one dimension.
 */
ZEND_METHOD(Gtk4_GtkWindow, set_default_size) {
  zend_long width, height;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_LONG(width)
  Z_PARAM_LONG(height)
  ZEND_PARSE_PARAMETERS_END();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  gtk_window_set_default_size(w, static_cast<int>(width), static_cast<int>(height));
}

/**
 * Gtk4\GtkWindow::set_child(?GtkWidget $child): void
 *
 * Set (or with null, remove) the single child widget.
 */
ZEND_METHOD(Gtk4_GtkWindow, set_child) {
  zval *child = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(child, class_for_gtype_name("GtkWidget"))
  ZEND_PARSE_PARAMETERS_END();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  GObject *c = nullptr;
  if (child != nullptr) {
    c = unwrap(child, GTK_TYPE_WIDGET);
    if (c == nullptr) RETURN_THROWS();
  }
  gtk_window_set_child(w, c != nullptr ? GTK_WIDGET(c) : nullptr);
}

/**
 * Gtk4\GtkWindow::present(): void
 *
 * Show the window and bring it to the front.
 */
ZEND_METHOD(Gtk4_GtkWindow, present) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  gtk_window_present(w);
}

/**
 * Gtk4\GtkWindow::close(): void
 *
 * Request the window to close (emits close-request; a handler returning true cancels).
 */
ZEND_METHOD(Gtk4_GtkWindow, close) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  gtk_window_close(w);
}

/**
 * Gtk4\GtkWindow::destroy(): void
 *
 * Drop GTK's reference to the toplevel and unrealize it. The PHP handle stays valid; `destroy` is
 * emitted when the last handle is released.
 */
ZEND_METHOD(Gtk4_GtkWindow, destroy) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  gtk_window_destroy(w);
}

/**
 * Gtk4\GtkWindow::get_child(): ?GtkWidget
 */
ZEND_METHOD(Gtk4_GtkWindow, get_child) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkWindow *w = PHPGTK_SELF(GtkWindow, GTK_TYPE_WINDOW);
  GtkWidget *child = gtk_window_get_child(w);
  wrap(child != nullptr ? G_OBJECT(child) : nullptr, return_value);
}
