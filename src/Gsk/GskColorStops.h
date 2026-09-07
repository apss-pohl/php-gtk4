// The two small C arrays GSK's gradient and shadow nodes take, as PHP lists: a colour stop is
// `[float $offset, GdkRGBA $color]`, a shadow `[GdkRGBA $color, float $dx, float $dy, float
// $radius]`. Shared by the gen/overrides of the five gradient node constructors and GskShadowNode.
#pragma once
#include "php_gtk4.h"

#include <array>
#include <vector>

namespace phpgtk {

// A PHP list of [offset, GdkRGBA] pairs -> the GskColorStop array a gradient constructor takes.
// Checks what gsk_*_gradient_node_new() would assert: at least two stops, offsets ascending
// within 0.0 .. 1.0. False with an argument error naming `arg` pending.
bool color_stops_from_php(zval *list, uint32_t arg, std::vector<GskColorStop> &out);
// The pairs back, for a get_color_stops().
void color_stops_to_php(const GskColorStop *stops, gsize n, zval *rv);

// A PHP list of [GdkRGBA, dx, dy, radius] lists -> the GskShadow array gsk_shadow_node_new() takes
// (at least one, radius not negative). False with an argument error naming `arg` pending.
bool shadows_from_php(zval *list, uint32_t arg, std::vector<GskShadow> &out);
// One shadow back, as the list get_shadow() answers with.
void shadow_to_php(const GskShadow *shadow, zval *rv);

// The four border widths (top, right, bottom, left) and four GdkRGBA colours gsk_border_node_new()
// and gtk_snapshot_append_border() take, from two PHP lists of exactly four. False with an
// argument error naming `widths_arg` / `colors_arg` pending.
bool border_from_php(zval *widths, uint32_t widths_arg, zval *colors, uint32_t colors_arg,
                     std::array<float, 4> &width_out, std::array<GdkRGBA, 4> &color_out);

}  // namespace phpgtk
