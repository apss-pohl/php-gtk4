<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GtkBox;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkGestureZoom;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkGestureZoom - two-finger pinch, as a scale factor.
 *
 * A two-point gesture: it needs a touchscreen or a touchpad that reports pinch
 * events (libinput does), a mouse cannot drive it. `scale-changed` carries the
 * distance between the two points relative to when they touched down, so 1.0
 * means unchanged; get_scale_delta() reads the same value. The canvas scales a
 * square by it and keeps the last factor when the fingers lift.
 *
 *   bin/php-gtk4 examples/demo.php GtkGestureZoom
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkGestureZoom',
    'two-finger pinch, as a scale factor',
    function (GtkWindow $win): GtkWidget {
        $scale = 1.0;
        $readout = Demo::label('<tt>pinch on a touchscreen or touchpad</tt>');

        $canvas = Demo::canvas(560, 280, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (&$scale): void {
            Demo::sheet($cr);
            $cr->save();
            $cr->translate($width / 2, $height / 2);
            $cr->scale($scale, $scale);
            $cr->set_source_rgba(0.21, 0.52, 0.89, 0.6);
            $cr->rectangle(-50, -50, 100, 100);
            $cr->fill();
            $cr->restore();
            Demo::text($cr, 20, 36, sprintf('scale %.2f', $scale), Demo::INK, 20);
        });

        $zoom = new GtkGestureZoom();
        $canvas->add_controller($zoom);

        $zoom->connect('scale-changed', function (
            GtkGestureZoom $g,
            float $factor,
        ) use (
            $canvas,
            $readout,
            &$scale
        ): void {
            $scale = max(0.1, min(4.0, $factor));
            $readout->set_markup(sprintf(
                '<tt>scale-changed  %.3f   get_scale_delta() %.3f</tt>',
                $factor,
                $g->get_scale_delta(),
            ));
            Demo::status(sprintf('scale %.2f', $factor));
            $canvas->queue_draw();
        });

        $page = new GtkBox(GtkOrientation::Vertical, 6);
        $page->append($readout);
        $page->append($canvas);
        return $page;
    },
    600,
    380,
);
