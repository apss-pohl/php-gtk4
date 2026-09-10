<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GdkRGBA;
use Gtk4\GtkBox;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkOverlay;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PangoFontDescription;

/*
 * Gtk4\PangoFontMetrics - what a font measures before any text is set in it: ascent and descent
 * around the baseline, the height of a line, where an underline and a strikethrough go and how
 * thick they are, and an approximate character width. All in Pango units, 1024 to the pixel.
 *
 * The lines below are drawn over a real label from the metrics of the description it is set in:
 * baseline in ink, ascent and descent in grey, underline in blue, strikethrough in red - so they
 * sit where Pango would put them, not where a guess would.
 *
 *   bin/php-gtk4 examples/demo.php PangoFontMetrics
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'PangoFontMetrics',
    'ascent, descent, baseline and the two rules, drawn over the label they measure',
    function (GtkWindow $win): GtkWidget {
        $desc = PangoFontDescription::from_string('Sans 24');
        $label = new GtkLabel('');
        $label->set_markup(sprintf('<span font_desc="%s">Hamburgefonstiv</span>', $desc->to_string()));

        $rules = Demo::canvas(360, 90, function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (
            $label,
            $desc,
        ): void {
            $layout = $label->get_layout();
            [$offsetX, $offsetY] = $label->get_layout_offsets();
            [, $logical] = $layout->get_pixel_extents();
            $baseline = $offsetY + $layout->get_baseline() / 1024;
            $metrics = $layout->get_context()->get_metrics($desc, null);

            $rule = static function (float $y, float $thickness, string $css) use ($cr, $offsetX, $logical): void {
                $cr->set_source_color(new GdkRGBA($css));
                $cr->set_line_width(max(1.0, $thickness));
                $cr->move_to($offsetX + $logical->x, round($y) + 0.5);
                $cr->line_to($offsetX + $logical->x + $logical->width, round($y) + 0.5);
                $cr->stroke();
            };
            $rule($baseline, 1.0, Demo::INK);
            $rule($baseline - $metrics->get_ascent() / 1024, 1.0, Demo::MUTED);
            $rule($baseline + $metrics->get_descent() / 1024, 1.0, Demo::MUTED);
            // Pango measures both positions upwards from the baseline.
            $rule(
                $baseline - $metrics->get_underline_position() / 1024,
                $metrics->get_underline_thickness() / 1024,
                Demo::ACCENT,
            );
            $rule(
                $baseline - $metrics->get_strikethrough_position() / 1024,
                $metrics->get_strikethrough_thickness() / 1024,
                Demo::WARN,
            );
        });
        $rules->can_target = false;

        $overlay = new GtkOverlay();
        $overlay->set_child($label);
        $overlay->add_overlay($rules);

        $metrics = $label->get_layout()->get_context()->get_metrics($desc, null);
        $facts = Demo::label(sprintf(
            "<tt>get_ascent()                  %5d units\nget_descent()                 %5d\n"
            . "get_height()                  %5d\nget_underline_position()      %5d\n"
            . "get_strikethrough_position()  %5d\nget_approximate_char_width()  %5d</tt>",
            $metrics->get_ascent(),
            $metrics->get_descent(),
            $metrics->get_height(),
            $metrics->get_underline_position(),
            $metrics->get_strikethrough_position(),
            $metrics->get_approximate_char_width(),
        ));

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($overlay);
        $page->append($facts);
        return $page;
    },
);
