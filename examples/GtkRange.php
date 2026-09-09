<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkRange;
use Gtk4\GtkScale;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkRange - the abstract base of GtkScale: a value dragged along a trough.
 *
 * Bounds, increments, inversion and the fill level (the "buffered so far" bar a
 * media player shows) all live here; GtkScale only adds marks and the printed
 * value. The page drives a GtkScale through the GtkRange API and reads back the
 * slider's pixel span and the trough rectangle, which only exist once the
 * widget has been laid out.
 *
 *   bin/php-gtk4 examples/demo.php GtkRange
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkRange',
    'bounds, increments, inversion and fill level, shown on a GtkScale',
    function (GtkWindow $win): GtkWidget {
        // GtkRange is abstract; GtkScale is the concrete one everybody uses.
        $scale = GtkScale::new_with_range(GtkOrientation::Horizontal, 0.0, 100.0, 1.0);
        $scale->set_hexpand(true);
        $scale->set_draw_value(false);

        // The GtkRange half of the API.
        $scale->set_range(0.0, 200.0);
        $scale->set_increments(5.0, 25.0);
        $scale->set_value(60.0);
        $scale->set_fill_level(120.0);
        $scale->set_show_fill_level(true);
        $scale->set_restrict_to_fill_level(true);   // the slider cannot go past 120
        $scale->set_round_digits(0);

        $status = Demo::label();
        $describe = function () use ($scale, $status): void {
            $adj = $scale->get_adjustment();
            [$start, $end] = $scale->get_slider_range();
            $rect = $scale->get_range_rect();
            $status->set_markup(sprintf(
                "<tt>value        %6.1f   bounds %g..%g  step %g  page %g\n"
                . "fill level   %6.1f   shown %-5s restrict %s\ninverted     %s\n"
                . "slider       px %d..%d\ntrough       %dx%d at %d,%d</tt>",
                $scale->get_value(),
                $adj->get_lower(),
                $adj->get_upper(),
                $adj->get_step_increment(),
                $adj->get_page_increment(),
                $scale->get_fill_level(),
                $scale->get_show_fill_level() ? 'true' : 'false',
                $scale->get_restrict_to_fill_level() ? 'true' : 'false',
                $scale->get_inverted() ? 'true' : 'false',
                $start,
                $end,
                $rect->width,
                $rect->height,
                $rect->x,
                $rect->y,
            ));
        };

        // `value-changed` is GtkRange's signal; the handler gets the range.
        $scale->connect('value-changed', function (GtkRange $self) use ($describe): void {
            Demo::status(sprintf('value-changed -> %g', $self->get_value()));
            $describe();
        });

        // Walk the API so every setter is seen doing something.
        $step = 0;
        GLib::timeout_add(1300, function () use ($scale, $describe, &$step): bool {
            match ($step++ % 6) {
                0 => $scale->set_value($scale->get_value() + 25.0),
                1 => $scale->set_fill_level(180.0),
                2 => $scale->set_value(190.0),                  // clamped to the fill level
                3 => $scale->set_inverted(!$scale->get_inverted()),
                4 => $scale->set_restrict_to_fill_level(false),
                default => (function () use ($scale): void {
                    $scale->set_value(60.0);
                    $scale->set_fill_level(120.0);
                    $scale->set_restrict_to_fill_level(true);
                })(),
            };
            $describe();
            return true;
        });

        $describe();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($scale);
        $page->append($status);
        return $page;
    },
    560,
    300,
);
