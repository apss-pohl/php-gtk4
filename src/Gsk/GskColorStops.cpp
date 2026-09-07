// The GskColorStop / GskShadow arrays of the gradient and shadow nodes as PHP lists
// (GskColorStops.h): converters shared by the gen/overrides of their constructors.
#include "GskColorStops.h"

#include "core/boxed.h"

using namespace phpgtk;

namespace {

// Item number `index` of the list is not what the converter wanted: the argument error.
void refuse(uint32_t arg, zend_long index, const char *what) {
  zend_argument_value_error(arg, "item " ZEND_LONG_FMT " %s", index, what);
}

// The GdkRGBA behind a list item, or nullptr with the argument error pending.
const GdkRGBA *rgba_of(zval *item, uint32_t arg, zend_long index) {
  if (Z_TYPE_P(item) != IS_OBJECT || boxed_class_for_type(GDK_TYPE_RGBA) == nullptr ||
      !instanceof_function(Z_OBJCE_P(item), boxed_class_for_type(GDK_TYPE_RGBA)->ce)) {
    refuse(arg, index, "must carry a GdkRGBA colour");
    return nullptr;
  }
  return static_cast<const GdkRGBA *>(unwrap_boxed(item, GDK_TYPE_RGBA));
}

// A float (or int) list item, or false with the argument error pending.
bool number_of(zval *item, uint32_t arg, zend_long index, const char *what, double *out) {
  if (Z_TYPE_P(item) != IS_DOUBLE && Z_TYPE_P(item) != IS_LONG) {
    refuse(arg, index, what);
    return false;
  }
  *out = zval_get_double(item);
  return true;
}

}  // namespace

namespace phpgtk {

// [offset, GdkRGBA] pairs -> GskColorStop array, with gsk_*_gradient_node_new()'s preconditions.
bool color_stops_from_php(zval *list, uint32_t arg, std::vector<GskColorStop> &out) {
  zend_long index = 0;
  double previous = 0;
  zval *item;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
  ZEND_HASH_FOREACH_VAL(Z_ARRVAL_P(list), item) {
    if (Z_TYPE_P(item) != IS_ARRAY || zend_hash_num_elements(Z_ARRVAL_P(item)) != 2) {
      refuse(arg, index, "must be a [float $offset, GdkRGBA $color] pair");
      return false;
    }
    zval *offset = zend_hash_index_find(Z_ARRVAL_P(item), 0);
    zval *color = zend_hash_index_find(Z_ARRVAL_P(item), 1);
    if (offset == nullptr || color == nullptr) {
      refuse(arg, index, "must be a [float $offset, GdkRGBA $color] pair (a list, not a map)");
      return false;
    }
    double at = 0;
    if (!number_of(offset, arg, index, "must start with the float offset", &at)) return false;
    if (at < 0.0 || at > 1.0 || (index > 0 && at < previous)) {
      refuse(arg, index, "must have an offset within 0.0 .. 1.0, not below the one before it");
      return false;
    }
    const GdkRGBA *rgba = rgba_of(color, arg, index);
    if (rgba == nullptr) return false;
    out.push_back(GskColorStop{.offset = static_cast<float>(at), .color = *rgba});
    previous = at;
    index++;
  }
  ZEND_HASH_FOREACH_END();
  if (out.size() < 2) {
    zend_argument_value_error(arg, "must hold at least two colour stops, %zu given", out.size());
    return false;
  }
  return true;
}

// GskColorStop array -> [offset, GdkRGBA] pairs.
void color_stops_to_php(const GskColorStop *stops, gsize n, zval *rv) {
  array_init_size(rv, static_cast<uint32_t>(n));
  for (gsize i = 0; i < n; i++) {
    // NOLINTNEXTLINE(cppcoreguidelines-pro-bounds-pointer-arithmetic) GSK's C array of n stops
    const GskColorStop &stop = stops[i];
    zval pair;
    array_init_size(&pair, 2);
    add_next_index_double(&pair, stop.offset);
    zval color;
    wrap_boxed(GDK_TYPE_RGBA, &stop.color, &color);
    add_next_index_zval(&pair, &color);
    add_next_index_zval(rv, &pair);
  }
}

// [GdkRGBA, dx, dy, radius] lists -> GskShadow array, with gsk_shadow_node_new()'s preconditions.
bool shadows_from_php(zval *list, uint32_t arg, std::vector<GskShadow> &out) {
  zend_long index = 0;
  zval *item;
  // NOLINTNEXTLINE(readability-math-missing-parentheses) Zend macro expansion
  ZEND_HASH_FOREACH_VAL(Z_ARRVAL_P(list), item) {
    if (Z_TYPE_P(item) != IS_ARRAY || zend_hash_num_elements(Z_ARRVAL_P(item)) != 4) {
      refuse(arg, index, "must be a [GdkRGBA $color, float $dx, float $dy, float $radius] list");
      return false;
    }
    std::array<zval *, 4> parts{};
    for (size_t k = 0; k < parts.size(); k++) {
      parts.at(k) = zend_hash_index_find(Z_ARRVAL_P(item), static_cast<zend_ulong>(k));
      if (parts.at(k) == nullptr) {
        refuse(arg, index, "must be a [GdkRGBA $color, float $dx, float $dy, float $radius] list");
        return false;
      }
    }
    const GdkRGBA *rgba = rgba_of(parts.at(0), arg, index);
    if (rgba == nullptr) return false;
    double dx = 0;
    double dy = 0;
    double radius = 0;
    if (!number_of(parts.at(1), arg, index, "must carry a float dx", &dx) ||
        !number_of(parts.at(2), arg, index, "must carry a float dy", &dy) ||
        !number_of(parts.at(3), arg, index, "must carry a float radius", &radius)) {
      return false;
    }
    if (radius < 0) {
      refuse(arg, index, "must not have a negative radius");
      return false;
    }
    out.push_back(GskShadow{.color = *rgba,
                            .dx = static_cast<float>(dx),
                            .dy = static_cast<float>(dy),
                            .radius = static_cast<float>(radius)});
    index++;
  }
  ZEND_HASH_FOREACH_END();
  if (out.empty()) {
    zend_argument_value_error(arg, "must hold at least one shadow");
    return false;
  }
  return true;
}

// Two lists of four -> the width and colour arrays of a border.
bool border_from_php(zval *widths, uint32_t widths_arg, zval *colors, uint32_t colors_arg,
                     std::array<float, 4> &width_out, std::array<GdkRGBA, 4> &color_out) {
  if (zend_hash_num_elements(Z_ARRVAL_P(widths)) != 4) {
    zend_argument_value_error(widths_arg,
                              "must hold exactly four widths (top, right, bottom, left)");
    return false;
  }
  if (zend_hash_num_elements(Z_ARRVAL_P(colors)) != 4) {
    zend_argument_value_error(colors_arg,
                              "must hold exactly four GdkRGBA colours (top, right, bottom, left)");
    return false;
  }
  for (zend_long i = 0; i < 4; i++) {
    zval *w = zend_hash_index_find(Z_ARRVAL_P(widths), i);
    double width = 0;
    if (w == nullptr || !number_of(w, widths_arg, i, "must be a float width", &width)) return false;
    if (width < 0) {
      refuse(widths_arg, i, "must not be a negative width");
      return false;
    }
    width_out.at(static_cast<size_t>(i)) = static_cast<float>(width);
    zval *c = zend_hash_index_find(Z_ARRVAL_P(colors), i);
    if (c == nullptr) {
      refuse(colors_arg, i, "must be a GdkRGBA (a list of four, not a map)");
      return false;
    }
    const GdkRGBA *rgba = rgba_of(c, colors_arg, i);
    if (rgba == nullptr) return false;
    color_out.at(static_cast<size_t>(i)) = *rgba;
  }
  return true;
}

// One GskShadow -> [GdkRGBA, dx, dy, radius].
void shadow_to_php(const GskShadow *shadow, zval *rv) {
  array_init_size(rv, 4);
  zval color;
  wrap_boxed(GDK_TYPE_RGBA, &shadow->color, &color);
  add_next_index_zval(rv, &color);
  add_next_index_double(rv, shadow->dx);
  add_next_index_double(rv, shadow->dy);
  add_next_index_double(rv, shadow->radius);
}

}  // namespace phpgtk
