// Child-widget argument handling shared by every container-like class (GtkBox,
// GtkWindow, GtkButton, ...): unwrap an optional ?GtkWidget, and the two checks
// GTK only asserts on (a child must be unparented before insertion, and be ours
// before removal) raised as PHP argument errors instead of Gtk-CRITICALs.
#pragma once
#include "php_gtk4.h"
#include "core/object.h"

namespace phpgtk {

// ?GtkWidget parameter -> widget or nullptr. False with a TypeError pending on a wrong object.
inline bool widget_or_null(zval *arg, GtkWidget **out) {
  *out = nullptr;
  if (arg == nullptr || Z_TYPE_P(arg) == IS_NULL) return true;
  GObject *c = unwrap(arg, GTK_TYPE_WIDGET);
  if (c == nullptr) return false;
  *out = GTK_WIDGET(c);
  return true;
}

// Insertions assert child->parent == NULL inside GTK (they never reparent).
inline bool require_unparented(GtkWidget *child, int arg_num) {
  if (gtk_widget_get_parent(child) == nullptr) return true;
  zend_argument_value_error(arg_num,
                            "must not already have a parent; remove it from its parent first");
  return false;
}

// Removals are silent about a foreign child; say so.
inline bool require_child_of(GtkWidget *parent, GtkWidget *child, int arg_num) {
  if (gtk_widget_get_parent(child) == parent) return true;
  zend_argument_value_error(arg_num, "must be a child of this %s", G_OBJECT_TYPE_NAME(parent));
  return false;
}

}  // namespace phpgtk
