<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkRGBA;
use Gtk4\GLib;
use Gtk4\GrapheneRect;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkSnapshot;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSnapshot - a widget written in PHP that paints itself.
 *
 * Every GTK 4 widget draws by filling a GtkSnapshot, and this is the one GtkWidget slot a PHP
 * subclass could not reach until the type was bound. The widget below is not a GtkDrawingArea
 * with a draw callback: it is a GtkWidget subclass whose own vfunc_measure() asks for a size and
 * whose vfunc_snapshot() paints - a flat colour node for the background, then append_cairo() for
 * the parts that are easier to describe with the cairo API, then a colour node again on top to
 * show that the two compose in order.
 *
 *   bin/php-gtk4 examples/demo.php GtkSnapshot
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSnapshot',
    'a widget written in PHP that paints itself',
    function (GtkWindow $win): GtkWidget {
        $dial = new class extends GtkWidget {
            public float $angle = 0.0;

            /** @return array{int, int, int, int} */
            public function vfunc_measure(GtkOrientation $orientation, int $for_size): array
            {
                return [240, 240, -1, -1];
            }

            public function vfunc_snapshot(GtkSnapshot $snapshot): void
            {
                $w = (float) $this->get_width();
                $h = (float) $this->get_height();
                $whole = GrapheneRect::alloc()->init(0.0, 0.0, $w, $h);

                // A render node: no cairo involved, GSK draws it.
                $snapshot->append_color(new GdkRGBA(Demo::PAPER), $whole);

                // ... and the same snapshot filled with cairo, which is where an existing
                // CairoContext drawing routine plugs straight in.
                $cr = $snapshot->append_cairo($whole);
                $cx = $w / 2;
                $cy = $h / 2;
                $radius = min($w, $h) / 2 - 16;

                $cr->set_source_color(new GdkRGBA(Demo::MUTED));
                $cr->set_line_width(2);
                $cr->arc($cx, $cy, $radius, 0, 2 * M_PI);
                $cr->stroke();

                $cr->set_source_color(new GdkRGBA(Demo::ACCENT));
                $cr->set_line_width(6);
                $cr->move_to($cx, $cy);
                $cr->line_to($cx + cos($this->angle) * $radius * 0.8, $cy + sin($this->angle) * $radius * 0.8);
                $cr->stroke();

                // On top of the cairo node, so ordering is visible: a bar along the bottom.
                $snapshot->append_color(
                    new GdkRGBA(Demo::ACCENT),
                    GrapheneRect::alloc()->init(0.0, $h - 6.0, $w * fmod($this->angle / (2 * M_PI), 1.0), 6.0),
                );
            }
        };

        $dial->set_hexpand(true);
        $dial->set_vexpand(true);

        // One frame every 40 ms: queue_draw() is what asks for vfunc_snapshot() again.
        GLib::timeout_add(40, function () use ($dial): bool {
            $dial->angle += M_PI / 30;
            $dial->queue_draw();

            return true;
        });

        $page = new GtkBox(GtkOrientation::Vertical, 0);
        $page->append($dial);
        Demo::status('a GtkWidget subclass: vfunc_measure() asks for the size, vfunc_snapshot() paints');

        return $page;
    },
);
