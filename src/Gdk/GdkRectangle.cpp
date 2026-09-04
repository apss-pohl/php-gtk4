// Gtk4\GdkRectangle (boxed value type)
#include "php_gtk4.h"
#include "classes.h"
#include "core/boxed.h"
#include "core/marshal.h"

#include <cstring>

using namespace phpgtk;

namespace {

const char *const fields[] = {"x", "y", "width", "height", nullptr};

// Address of the int field named `f`, or nullptr for an unknown name.
int *field_ptr(GdkRectangle *r, const char *f) {
  if (strcmp(f, "x") == 0) return &r->x;
  if (strcmp(f, "y") == 0) return &r->y;
  if (strcmp(f, "width") == 0) return &r->width;
  if (strcmp(f, "height") == 0) return &r->height;
  return nullptr;
}

// Boxed field reader: x/y/width/height as int.
bool read(gpointer data, const char *f, zval *rv) {
  int *p = field_ptr(static_cast<GdkRectangle *>(data), f);
  if (p == nullptr) return false;
  ZVAL_LONG(rv, *p);
  return true;
}

// Boxed field writer: like an `int` parameter - strict_types where the assignment is written,
// weak coercion otherwise, then the C `int` range. True for a field even when the value was
// refused (the TypeError/ValueError is pending), so the write never falls through to a dynamic
// PHP property of the same name.
bool write(gpointer data, const char *f, zval *value) {
  int *p = field_ptr(static_cast<GdkRectangle *>(data), f);
  if (p == nullptr) return false;
  zend_long v = 0;
  if (Z_TYPE_P(value) == IS_LONG) {
    v = Z_LVAL_P(value);
  } else if (caller_is_strict() || !zend_parse_arg_long_weak(value, &v, 0)) {
    zend_type_error("Cannot assign %s to property GdkRectangle::$%s of type int",
                    zend_zval_value_name(value), f);
    return true;
  }
  if (phpgtk::check_range<int>(v, 0)) *p = static_cast<int>(v);
  return true;
}

// Return a copy of `r` as a new Gtk4\GdkRectangle handle.
void ret_rect(zval *rv, const GdkRectangle &r) {
  wrap_boxed(GDK_TYPE_RECTANGLE, &r, rv);
}

}  // namespace

/**
 * Gtk4\GdkRectangle::__construct(int $x = 0, int $y = 0, int $width = 0, int $height = 0)
 *
 * Origin and size in pixels.
 */
ZEND_METHOD(Gtk4_GdkRectangle, __construct) {
  zend_long x = 0;
  zend_long y = 0;
  zend_long width = 0;
  zend_long height = 0;
  ZEND_PARSE_PARAMETERS_START(0, 4)
  Z_PARAM_OPTIONAL
  Z_PARAM_LONG(x)
  Z_PARAM_LONG(y)
  Z_PARAM_LONG(width)
  Z_PARAM_LONG(height)
  ZEND_PARSE_PARAMETERS_END();
  if (!phpgtk::check_range<int>(x, 1) || !phpgtk::check_range<int>(y, 2) ||
      !phpgtk::check_range<int>(width, 3) || !phpgtk::check_range<int>(height, 4)) {
    RETURN_THROWS();
  }
  auto *r = g_new0(GdkRectangle, 1);
  r->x = static_cast<int>(x);
  r->y = static_cast<int>(y);
  r->width = static_cast<int>(width);
  r->height = static_cast<int>(height);
  boxed_adopt(boxed_from_zval(ZEND_THIS), GDK_TYPE_RECTANGLE, r);
}

/**
 * Gtk4\GdkRectangle::intersect(GdkRectangle $other): ?GdkRectangle
 *
 * The overlap with $other, or null if they do not intersect.
 */
ZEND_METHOD(Gtk4_GdkRectangle, intersect) {
  zval *other;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(other, boxed_class_for_type(GDK_TYPE_RECTANGLE)->ce)
  ZEND_PARSE_PARAMETERS_END();
  GdkRectangle *self = PHPGTK_BOXED_SELF(GdkRectangle);
  GdkRectangle out;
  auto *o = static_cast<GdkRectangle *>(unwrap_boxed(other, GDK_TYPE_RECTANGLE));
  if (!gdk_rectangle_intersect(self, o, &out)) RETURN_NULL();
  ret_rect(return_value, out);
}

/**
 * Gtk4\GdkRectangle::union(GdkRectangle $other): GdkRectangle
 *
 * The smallest rectangle containing both.
 */
ZEND_METHOD(Gtk4_GdkRectangle, union) {
  zval *other;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(other, boxed_class_for_type(GDK_TYPE_RECTANGLE)->ce)
  ZEND_PARSE_PARAMETERS_END();
  GdkRectangle *self = PHPGTK_BOXED_SELF(GdkRectangle);
  GdkRectangle out;
  auto *o = static_cast<GdkRectangle *>(unwrap_boxed(other, GDK_TYPE_RECTANGLE));
  gdk_rectangle_union(self, o, &out);
  ret_rect(return_value, out);
}

/**
 * Gtk4\GdkRectangle::contains_point(int $x, int $y): bool
 *
 * Whether the point lies inside the rectangle.
 */
ZEND_METHOD(Gtk4_GdkRectangle, contains_point) {
  zend_long x, y;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_LONG(x)
  Z_PARAM_LONG(y)
  ZEND_PARSE_PARAMETERS_END();
  GdkRectangle *self = PHPGTK_BOXED_SELF(GdkRectangle);
  if (!phpgtk::check_range<int>(x, 1) || !phpgtk::check_range<int>(y, 2)) RETURN_THROWS();
  RETURN_BOOL(gdk_rectangle_contains_point(self, static_cast<int>(x), static_cast<int>(y)));
}

/**
 * Gtk4\GdkRectangle::equal(GdkRectangle $other): bool
 *
 * Position and size equal.
 */
ZEND_METHOD(Gtk4_GdkRectangle, equal) {
  zval *other;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(other, boxed_class_for_type(GDK_TYPE_RECTANGLE)->ce)
  ZEND_PARSE_PARAMETERS_END();
  GdkRectangle *self = PHPGTK_BOXED_SELF(GdkRectangle);
  auto *o = static_cast<GdkRectangle *>(unwrap_boxed(other, GDK_TYPE_RECTANGLE));
  RETURN_BOOL(gdk_rectangle_equal(self, o));
}

namespace phpgtk {
// MINIT: bind the PHP class to GDK_TYPE_RECTANGLE with its field table.
void register_GdkRectangle(zend_class_entry *ce) {
  register_boxed(BoxedClass{
      .type = GDK_TYPE_RECTANGLE, .ce = ce, .fields = fields, .read = read, .write = write});
}
}  // namespace phpgtk
