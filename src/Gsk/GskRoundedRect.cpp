// Gtk4\GskRoundedRect (boxed value type): a rectangle with a size per corner, as
// GskRoundedClipNode, GskBorderNode, the shadow nodes and GtkSnapshot::push_rounded_clip() take it.
// GSK gives the struct no GType, so GskRoundedRect.h registers one; the mutating GSK calls
// (normalize, offset, shrink) are bound as copies, the way graphene's are - a value handle never
// rewrites itself.
#include "GskRoundedRectType.h"
#include "classes.h"
#include "core/boxed.h"
#include "core/enums.h"

#include <array>
#include <cstring>

using namespace phpgtk;

namespace {

const char *const fields[] = {nullptr};

// Boxed field reader: no scalar fields (bounds and corners are structs, reached by methods).
bool read(gpointer data, const char *field, zval *rv) {
  (void)data;
  (void)field;
  (void)rv;
  return false;
}

// Boxed field writer: nothing to write.
bool write(gpointer data, const char *field, zval *v) {
  (void)data;
  (void)field;
  (void)v;
  return false;
}

// g_boxed_copy for the synthetic type: the struct, byte for byte.
gpointer rounded_rect_copy(gpointer p) {
  return g_memdup2(p, sizeof(GskRoundedRect));
}

// `==`: same bounds, same four corner sizes.
bool rounded_rect_equal(gconstpointer a, gconstpointer b) {
  const auto *x = static_cast<const GskRoundedRect *>(a);
  const auto *y = static_cast<const GskRoundedRect *>(b);
  if (!graphene_rect_equal(&x->bounds, &y->bounds)) return false;
  return graphene_size_equal(&x->corner[GSK_CORNER_TOP_LEFT], &y->corner[GSK_CORNER_TOP_LEFT]) &&
         graphene_size_equal(&x->corner[GSK_CORNER_TOP_RIGHT], &y->corner[GSK_CORNER_TOP_RIGHT]) &&
         graphene_size_equal(&x->corner[GSK_CORNER_BOTTOM_RIGHT],
                             &y->corner[GSK_CORNER_BOTTOM_RIGHT]) &&
         graphene_size_equal(&x->corner[GSK_CORNER_BOTTOM_LEFT],
                             &y->corner[GSK_CORNER_BOTTOM_LEFT]);
}

// The graphene rectangle behind a GrapheneRect argument (a TypeError is pending on nullptr).
const graphene_rect_t *rect_arg(zval *zv) {
  return static_cast<const graphene_rect_t *>(unwrap_boxed(zv, GRAPHENE_TYPE_RECT));
}

// A copy of `value` as a new handle.
void ret_rounded(const GskRoundedRect &value, zval *rv) {
  wrap_boxed(PHPGTK_TYPE_GSK_ROUNDED_RECT, &value, rv);
}

}  // namespace

namespace phpgtk {

// The boxed GType standing in for GskRoundedRect, registered on first use.
GType gsk_rounded_rect_php_type() {
  static const GType type = g_boxed_type_register_static(
      g_intern_static_string("PhpGtk4GskRoundedRect"), rounded_rect_copy, g_free);
  return type;
}

// MINIT: bind the PHP class to the synthetic boxed type.
void register_GskRoundedRect(zend_class_entry *ce) {
  register_boxed(BoxedClass{.type = PHPGTK_TYPE_GSK_ROUNDED_RECT,
                            .ce = ce,
                            .fields = fields,
                            .read = read,
                            .write = write,
                            .equal = rounded_rect_equal});
}

}  // namespace phpgtk

/**
 * Gtk4\GskRoundedRect::__construct(GrapheneRect $bounds, float $top_left = 0.0, float $top_right =
 * 0.0, float $bottom_right = 0.0, float $bottom_left = 0.0)
 *
 * A rectangle with a (circular) radius per corner; a radius of 0.0 keeps that corner square.
 */
ZEND_METHOD(Gtk4_GskRoundedRect, __construct) {
  zval *bounds;
  std::array<double, 4> radius{};
  ZEND_PARSE_PARAMETERS_START(1, 5)
  Z_PARAM_OBJECT_OF_CLASS(bounds, boxed_class_for_type(GRAPHENE_TYPE_RECT)->ce)
  Z_PARAM_OPTIONAL
  Z_PARAM_DOUBLE(radius.at(0))
  Z_PARAM_DOUBLE(radius.at(1))
  Z_PARAM_DOUBLE(radius.at(2))
  Z_PARAM_DOUBLE(radius.at(3))
  ZEND_PARSE_PARAMETERS_END();
  for (uint32_t i = 0; i < radius.size(); i++) {
    if (!phpgtk::check_domain_double(radius.at(i), 0.0, HUGE_VAL, i + 2)) RETURN_THROWS();
  }
  GskRoundedRect value{};
  gsk_rounded_rect_init_from_rect(&value, rect_arg(bounds), 0.0F);
  const auto corner = [](double r) { return static_cast<float>(r); };
  graphene_size_init(&value.corner[GSK_CORNER_TOP_LEFT], corner(radius.at(0)),
                     corner(radius.at(0)));
  graphene_size_init(&value.corner[GSK_CORNER_TOP_RIGHT], corner(radius.at(1)),
                     corner(radius.at(1)));
  graphene_size_init(&value.corner[GSK_CORNER_BOTTOM_RIGHT], corner(radius.at(2)),
                     corner(radius.at(2)));
  graphene_size_init(&value.corner[GSK_CORNER_BOTTOM_LEFT], corner(radius.at(3)),
                     corner(radius.at(3)));
  boxed_adopt(boxed_from_zval(ZEND_THIS), PHPGTK_TYPE_GSK_ROUNDED_RECT, rounded_rect_copy(&value));
}

/**
 * Gtk4\GskRoundedRect::get_bounds(): GrapheneRect
 *
 * The rectangle without its corners.
 */
ZEND_METHOD(Gtk4_GskRoundedRect, get_bounds) {
  ZEND_PARSE_PARAMETERS_NONE();
  GskRoundedRect *self = PHPGTK_BOXED_SELF(GskRoundedRect);
  wrap_boxed(GRAPHENE_TYPE_RECT, &self->bounds, return_value);
}

/**
 * Gtk4\GskRoundedRect::get_corner(GskCorner $corner): GrapheneSize
 *
 * The horizontal and vertical radius of one corner.
 */
ZEND_METHOD(Gtk4_GskRoundedRect, get_corner) {
  zval *corner;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(corner, enum_class_for_type(GSK_TYPE_CORNER))
  ZEND_PARSE_PARAMETERS_END();
  GskRoundedRect *self = PHPGTK_BOXED_SELF(GskRoundedRect);
  gint which = 0;
  if (!enum_from_php(corner, GSK_TYPE_CORNER, &which)) RETURN_THROWS();
  // NOLINTNEXTLINE(cppcoreguidelines-pro-bounds-constant-array-index) a GskCorner case, 0..3
  wrap_boxed(GRAPHENE_TYPE_SIZE, &self->corner[which], return_value);
}

/**
 * Gtk4\GskRoundedRect::is_rectilinear(): bool
 *
 * Whether every corner is square (all radii 0.0).
 */
ZEND_METHOD(Gtk4_GskRoundedRect, is_rectilinear) {
  ZEND_PARSE_PARAMETERS_NONE();
  GskRoundedRect *self = PHPGTK_BOXED_SELF(GskRoundedRect);
  RETURN_BOOL(gsk_rounded_rect_is_rectilinear(self));
}

/**
 * Gtk4\GskRoundedRect::contains_point(GraphenePoint $point): bool
 *
 * Whether the point lies inside the rounded rectangle.
 */
ZEND_METHOD(Gtk4_GskRoundedRect, contains_point) {
  zval *point;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(point, boxed_class_for_type(GRAPHENE_TYPE_POINT)->ce)
  ZEND_PARSE_PARAMETERS_END();
  GskRoundedRect *self = PHPGTK_BOXED_SELF(GskRoundedRect);
  const auto *p = static_cast<const graphene_point_t *>(unwrap_boxed(point, GRAPHENE_TYPE_POINT));
  RETURN_BOOL(gsk_rounded_rect_contains_point(self, p));
}

/**
 * Gtk4\GskRoundedRect::contains_rect(GrapheneRect $rect): bool
 *
 * Whether the whole rectangle lies inside the rounded rectangle.
 */
ZEND_METHOD(Gtk4_GskRoundedRect, contains_rect) {
  zval *rect;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(rect, boxed_class_for_type(GRAPHENE_TYPE_RECT)->ce)
  ZEND_PARSE_PARAMETERS_END();
  GskRoundedRect *self = PHPGTK_BOXED_SELF(GskRoundedRect);
  RETURN_BOOL(gsk_rounded_rect_contains_rect(self, rect_arg(rect)));
}

/**
 * Gtk4\GskRoundedRect::intersects_rect(GrapheneRect $rect): bool
 *
 * Whether the rectangle overlaps the rounded rectangle anywhere.
 */
ZEND_METHOD(Gtk4_GskRoundedRect, intersects_rect) {
  zval *rect;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(rect, boxed_class_for_type(GRAPHENE_TYPE_RECT)->ce)
  ZEND_PARSE_PARAMETERS_END();
  GskRoundedRect *self = PHPGTK_BOXED_SELF(GskRoundedRect);
  RETURN_BOOL(gsk_rounded_rect_intersects_rect(self, rect_arg(rect)));
}

/**
 * Gtk4\GskRoundedRect::normalize(): GskRoundedRect
 *
 * A copy with a non-negative size and corners no larger than the sides allow.
 */
ZEND_METHOD(Gtk4_GskRoundedRect, normalize) {
  ZEND_PARSE_PARAMETERS_NONE();
  GskRoundedRect *self = PHPGTK_BOXED_SELF(GskRoundedRect);
  GskRoundedRect copy = *self;
  gsk_rounded_rect_normalize(&copy);
  ret_rounded(copy, return_value);
}

/**
 * Gtk4\GskRoundedRect::offset(float $dx, float $dy): GskRoundedRect
 *
 * A copy moved by ($dx, $dy).
 */
ZEND_METHOD(Gtk4_GskRoundedRect, offset) {
  double dx = 0;
  double dy = 0;
  ZEND_PARSE_PARAMETERS_START(2, 2)
  Z_PARAM_DOUBLE(dx)
  Z_PARAM_DOUBLE(dy)
  ZEND_PARSE_PARAMETERS_END();
  GskRoundedRect *self = PHPGTK_BOXED_SELF(GskRoundedRect);
  GskRoundedRect copy = *self;
  gsk_rounded_rect_offset(&copy, static_cast<float>(dx), static_cast<float>(dy));
  ret_rounded(copy, return_value);
}

/**
 * Gtk4\GskRoundedRect::shrink(float $top, float $right, float $bottom, float $left):
 * GskRoundedRect
 *
 * A copy inset by the given amounts on each side, the corners shrinking with it (negative values
 * grow it).
 */
ZEND_METHOD(Gtk4_GskRoundedRect, shrink) {
  double top = 0;
  double right = 0;
  double bottom = 0;
  double left = 0;
  ZEND_PARSE_PARAMETERS_START(4, 4)
  Z_PARAM_DOUBLE(top)
  Z_PARAM_DOUBLE(right)
  Z_PARAM_DOUBLE(bottom)
  Z_PARAM_DOUBLE(left)
  ZEND_PARSE_PARAMETERS_END();
  GskRoundedRect *self = PHPGTK_BOXED_SELF(GskRoundedRect);
  GskRoundedRect copy = *self;
  gsk_rounded_rect_shrink(&copy, static_cast<float>(top), static_cast<float>(right),
                          static_cast<float>(bottom), static_cast<float>(left));
  ret_rounded(copy, return_value);
}

/**
 * Gtk4\GskRoundedRect::equal(GskRoundedRect $other): bool
 *
 * Same bounds and the same four corners (what `==` compares too).
 */
ZEND_METHOD(Gtk4_GskRoundedRect, equal) {
  zval *other;
  ZEND_PARSE_PARAMETERS_START(1, 1)
  Z_PARAM_OBJECT_OF_CLASS(other, boxed_class_for_type(PHPGTK_TYPE_GSK_ROUNDED_RECT)->ce)
  ZEND_PARSE_PARAMETERS_END();
  GskRoundedRect *self = PHPGTK_BOXED_SELF(GskRoundedRect);
  RETURN_BOOL(rounded_rect_equal(self, unwrap_boxed(other, PHPGTK_TYPE_GSK_ROUNDED_RECT)));
}
