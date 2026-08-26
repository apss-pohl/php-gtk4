// Gtk4\GtkBox: the layout container. GTK 4 dropped GtkContainer, so this is what
// holds more than one child - windows and buttons hold exactly one.
#include "php_gtk4.h"
#include "core/enums.h"
#include "core/object.h"
#include "children.h"

using namespace phpgtk;

namespace {

// Shared by append/prepend/remove/insert: this box plus a checked child widget.
// Returns false with an exception pending if the child is not usable.
bool box_and_child(zend_execute_data *execute_data, const char *fn, zval *child, GtkBox **box,
                   GtkWidget **widget) {
  *box = reinterpret_cast<GtkBox *>(self_object(execute_data, GTK_TYPE_BOX, fn));
  if (EG(exception)) return false;
  GObject *c = unwrap(child, GTK_TYPE_WIDGET);
  if (c == nullptr) return false;
  *widget = GTK_WIDGET(c);
  return true;
}

}  // namespace

/**
 * Gtk4\GtkBox::__construct(GtkOrientation $orientation = GtkOrientation::Horizontal, int $spacing
 * = 0)
 */
ZEND_METHOD(Gtk4_GtkBox, __construct) {
  zval *orientation = nullptr;
  zend_long spacing = 0;
  ZEND_PARSE_PARAMETERS_START(0, 2)
  Z_PARAM_OPTIONAL
  Z_PARAM_OBJECT_OF_CLASS(orientation, enum_class_for_type(GTK_TYPE_ORIENTATION))
  Z_PARAM_LONG(spacing)
  ZEND_PARSE_PARAMETERS_END();
  if (spacing < 0) {
    zend_argument_value_error(2, "must be greater than or equal to 0");
    RETURN_THROWS();
  }
  gint dir = GTK_ORIENTATION_HORIZONTAL;
  if (orientation != nullptr && !enum_from_php(orientation, GTK_TYPE_ORIENTATION, &dir)) {
    RETURN_THROWS();
  }
  attach_new(object_from_zval(ZEND_THIS),
             G_OBJECT(gtk_box_new(static_cast<GtkOrientation>(dir), static_cast<int>(spacing))));
}

/**
 * Gtk4\GtkBox::append(GtkWidget $child): void
 *
 * Add $child at the end.
 */
ZEND_METHOD(Gtk4_GtkBox, append) {
  zval *child = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(child, class_for_gtype_name("GtkWidget"))
  ZEND_PARSE_PARAMETERS_END();
  GtkBox *box = nullptr;
  GtkWidget *widget = nullptr;
  if (!box_and_child(execute_data, __func__, child, &box, &widget)) RETURN_THROWS();
  if (!require_unparented(widget, 1)) RETURN_THROWS();
  gtk_box_append(box, widget);
}

/**
 * Gtk4\GtkBox::prepend(GtkWidget $child): void
 *
 * Add $child at the start.
 */
ZEND_METHOD(Gtk4_GtkBox, prepend) {
  zval *child = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(child, class_for_gtype_name("GtkWidget"))
  ZEND_PARSE_PARAMETERS_END();
  GtkBox *box = nullptr;
  GtkWidget *widget = nullptr;
  if (!box_and_child(execute_data, __func__, child, &box, &widget)) RETURN_THROWS();
  if (!require_unparented(widget, 1)) RETURN_THROWS();
  gtk_box_prepend(box, widget);
}

/**
 * Gtk4\GtkBox::insert_child_after(GtkWidget $child, ?GtkWidget $sibling): void
 *
 * Insert $child directly after $sibling, or at the start when $sibling is null.
 */
ZEND_METHOD(Gtk4_GtkBox, insert_child_after) {
  zval *child = nullptr;
  zval *sibling = nullptr;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_OBJECT_OF_CLASS(child, class_for_gtype_name("GtkWidget"))
  Z_PARAM_OBJECT_OF_CLASS_OR_NULL(sibling, class_for_gtype_name("GtkWidget"))
  ZEND_PARSE_PARAMETERS_END();
  GtkBox *box = nullptr;
  GtkWidget *widget = nullptr;
  if (!box_and_child(execute_data, __func__, child, &box, &widget)) RETURN_THROWS();
  if (!require_unparented(widget, 1)) RETURN_THROWS();
  GtkWidget *after = nullptr;
  if (sibling != nullptr) {
    GObject *s = unwrap(sibling, GTK_TYPE_WIDGET);
    if (s == nullptr) RETURN_THROWS();
    after = GTK_WIDGET(s);
    if (!require_child_of(GTK_WIDGET(box), after, 2)) RETURN_THROWS();
  }
  gtk_box_insert_child_after(box, widget, after);
}

/**
 * Gtk4\GtkBox::remove(GtkWidget $child): void
 *
 * Remove a child.
 */
ZEND_METHOD(Gtk4_GtkBox, remove) {
  zval *child = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(child, class_for_gtype_name("GtkWidget"))
  ZEND_PARSE_PARAMETERS_END();
  GtkBox *box = nullptr;
  GtkWidget *widget = nullptr;
  if (!box_and_child(execute_data, __func__, child, &box, &widget)) RETURN_THROWS();
  if (!require_child_of(GTK_WIDGET(box), widget, 1)) RETURN_THROWS();
  gtk_box_remove(box, widget);
}

/**
 * Gtk4\GtkBox::set_spacing(int $spacing): void
 *
 * Pixels between children.
 */
ZEND_METHOD(Gtk4_GtkBox, set_spacing) {
  zend_long spacing = 0;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_LONG(spacing)
  ZEND_PARSE_PARAMETERS_END();
  if (spacing < 0) {
    zend_argument_value_error(1, "must be greater than or equal to 0");
    RETURN_THROWS();
  }
  GtkBox *box = PHPGTK_SELF(GtkBox, GTK_TYPE_BOX);
  gtk_box_set_spacing(box, static_cast<int>(spacing));
}

/**
 * Gtk4\GtkBox::get_spacing(): int
 */
ZEND_METHOD(Gtk4_GtkBox, get_spacing) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkBox *box = PHPGTK_SELF(GtkBox, GTK_TYPE_BOX);
  RETURN_LONG(gtk_box_get_spacing(box));
}

/**
 * Gtk4\GtkBox::set_homogeneous(bool $homogeneous): void
 *
 * Whether every child gets the same amount of space.
 */
ZEND_METHOD(Gtk4_GtkBox, set_homogeneous) {
  bool homogeneous = false;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_BOOL(homogeneous)
  ZEND_PARSE_PARAMETERS_END();
  GtkBox *box = PHPGTK_SELF(GtkBox, GTK_TYPE_BOX);
  gtk_box_set_homogeneous(box, static_cast<gboolean>(homogeneous));
}

/**
 * Gtk4\GtkBox::get_homogeneous(): bool
 */
ZEND_METHOD(Gtk4_GtkBox, get_homogeneous) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkBox *box = PHPGTK_SELF(GtkBox, GTK_TYPE_BOX);
  RETURN_BOOL(gtk_box_get_homogeneous(box));
}

/**
 * Gtk4\GtkBox::set_orientation(GtkOrientation $orientation): void
 */
ZEND_METHOD(Gtk4_GtkBox, set_orientation) {
  zval *orientation = nullptr;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(orientation, enum_class_for_type(GTK_TYPE_ORIENTATION))
  ZEND_PARSE_PARAMETERS_END();
  GtkBox *box = PHPGTK_SELF(GtkBox, GTK_TYPE_BOX);
  gint value = 0;
  if (!enum_from_php(orientation, GTK_TYPE_ORIENTATION, &value)) RETURN_THROWS();
  gtk_orientable_set_orientation(GTK_ORIENTABLE(box), static_cast<GtkOrientation>(value));
}

/**
 * Gtk4\GtkBox::get_orientation(): GtkOrientation
 */
ZEND_METHOD(Gtk4_GtkBox, get_orientation) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkBox *box = PHPGTK_SELF(GtkBox, GTK_TYPE_BOX);
  enum_to_php(GTK_TYPE_ORIENTATION, gtk_orientable_get_orientation(GTK_ORIENTABLE(box)),
              return_value);
}

/**
 * Gtk4\GtkBox::get_children(): array
 *
 * This box's children, in order.
 */
ZEND_METHOD(Gtk4_GtkBox, get_children) {
  ZEND_PARSE_PARAMETERS_NONE();
  GtkBox *box = PHPGTK_SELF(GtkBox, GTK_TYPE_BOX);
  array_init(return_value);
  for (GtkWidget *c = gtk_widget_get_first_child(GTK_WIDGET(box)); c != nullptr;
       c = gtk_widget_get_next_sibling(c)) {
    zval item;
    wrap(G_OBJECT(c), &item);
    add_next_index_zval(return_value, &item);
  }
}
