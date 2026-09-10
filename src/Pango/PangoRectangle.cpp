// Gtk4\PangoRectangle (boxed value type on a synthetic GType - see PangoRectangleType.h)
#include "php_gtk4.h"
#include "classes.h"
#include "core/boxed.h"
#include "core/marshal.h"
#include "Pango/PangoRectangleType.h"

#include <cstring>

using namespace phpgtk;

namespace {

const char *const fields[] = {"x", "y", "width", "height", nullptr};

// Address of the int field named `f`, or nullptr for an unknown name.
int *field_ptr(PangoRectangle *r, const char *f) {
  if (strcmp(f, "x") == 0) return &r->x;
  if (strcmp(f, "y") == 0) return &r->y;
  if (strcmp(f, "width") == 0) return &r->width;
  if (strcmp(f, "height") == 0) return &r->height;
  return nullptr;
}

// Boxed field reader: x/y/width/height as int.
bool read(gpointer data, const char *f, zval *rv) {
  int *p = field_ptr(static_cast<PangoRectangle *>(data), f);
  if (p == nullptr) return false;
  ZVAL_LONG(rv, *p);
  return true;
}

// Boxed field writer, like an `int` parameter: strict_types where the assignment is written,
// weak coercion otherwise, then the C `int` range. True even when the value was refused, so the
// write never falls through to a dynamic PHP property of the same name.
bool write(gpointer data, const char *f, zval *value) {
  int *p = field_ptr(static_cast<PangoRectangle *>(data), f);
  if (p == nullptr) return false;
  zend_long v = 0;
  if (!boxed_field_long(value, "Gtk4\\PangoRectangle", f, &v)) return true;
  if (phpgtk::check_range<int>(v, 0)) *p = static_cast<int>(v);
  return true;
}

// g_boxed_copy for the synthetic type: the struct, byte for byte.
gpointer pango_rectangle_copy(gpointer p) {
  return g_memdup2(p, sizeof(PangoRectangle));
}

// `==`: all four fields.
bool pango_rectangle_equal(gconstpointer a, gconstpointer b) {
  const auto *x = static_cast<const PangoRectangle *>(a);
  const auto *y = static_cast<const PangoRectangle *>(b);
  return x->x == y->x && x->y == y->y && x->width == y->width && x->height == y->height;
}

}  // namespace

/**
 * Gtk4\PangoRectangle::__construct(int $x = 0, int $y = 0, int $width = 0, int $height = 0)
 *
 * Origin and size in Pango units, unless the call that filled it says pixels.
 */
ZEND_METHOD(Gtk4_PangoRectangle, __construct) {
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
  auto *r = g_new0(PangoRectangle, 1);
  r->x = static_cast<int>(x);
  r->y = static_cast<int>(y);
  r->width = static_cast<int>(width);
  r->height = static_cast<int>(height);
  boxed_adopt(boxed_from_zval(ZEND_THIS), PHPGTK_TYPE_PANGO_RECTANGLE, r);
}

/**
 * Gtk4\PangoRectangle::to_pixels(): PangoRectangle
 *
 * The same rectangle in pixels, rounded outwards to cover what it covered in Pango units.
 */
ZEND_METHOD(Gtk4_PangoRectangle, to_pixels) {
  ZEND_PARSE_PARAMETERS_NONE();
  auto *self = static_cast<PangoRectangle *>(unwrap_boxed(ZEND_THIS, PHPGTK_TYPE_PANGO_RECTANGLE));
  PangoRectangle pixels = *self;
  // The "inclusive" rectangle of pango_extents_to_pixels(): the smallest pixel rectangle that
  // still contains every unit the original covered.
  pango_extents_to_pixels(&pixels, nullptr);
  wrap_boxed(PHPGTK_TYPE_PANGO_RECTANGLE, &pixels, return_value);
}

namespace phpgtk {

// The boxed GType standing in for PangoRectangle, registered on first use.
GType pango_rectangle_php_type() {
  static const GType type = g_boxed_type_register_static(
      g_intern_static_string("PhpGtk4PangoRectangle"), pango_rectangle_copy, g_free);
  return type;
}

// MINIT: bind the PHP class to the synthetic boxed type.
void register_PangoRectangle(zend_class_entry *ce) {
  register_boxed(BoxedClass{.type = PHPGTK_TYPE_PANGO_RECTANGLE,
                            .ce = ce,
                            .fields = fields,
                            .read = read,
                            .write = write,
                            .equal = pango_rectangle_equal});
}

}  // namespace phpgtk
