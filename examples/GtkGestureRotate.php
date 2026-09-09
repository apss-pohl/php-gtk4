<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GtkBox;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkGestureRotate;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkGestureRotate - two fingers turning around each other.
 *
 * Like GtkGestureZoom this is a two-point gesture, driven by a touchscreen or
 * a touchpad that reports rotation. `angle-changed` carries the current angle
 * and the change since the fingers touched down, in radians; get_angle_delta()
 * reads the latter. The canvas rotates a bar by the delta and keeps the last
 * angle when the fingers lift.
 *
 *   bin/php-gtk4 examples/demo.php GtkGestureRotate
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkGestureRotate',
    'two fingers turning around each other',
    function (GtkWindow $win): GtkWidget {
        $angle = 0.0;
        $readout = Demo::label('<tt>rotate two fingers on a touchscreen or touchpad</tt>');

        $canvas = Demo::canvas(560, 280, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (&$angle): void {
            Demo::sheet($cr);
            $cr->save();
            $cr->translate($width / 2, $height / 2);
            $cr->rotate($angle);
            $cr->set_source_rgba(0.21, 0.52, 0.89, 0.7);
            $cr->rectangle(-90, -14, 180, 28);
            $cr->fill();
            $cr->set_source_rgba(0.88, 0.11, 0.14, 0.9);
            $cr->arc(90, 0, 8, 0, 2 * M_PI);   // marks the end that started on the right
            $cr->fill();
            $cr->restore();
            Demo::text($cr, 20, 36, sprintf('angle %.1f°', rad2deg($angle)), Demo::INK, 20);
        });

        $rotate = new GtkGestureRotate();
        $canvas->add_controller($rotate);

        $rotate->connect('angle-changed', function (
            GtkGestureRotate $g,
            float $current,
            float $delta,
        ) use (
            $canvas,
            $readout,
            &$angle
        ): void {
            $angle = $delta;
            $readout->set_markup(sprintf(
                '<tt>angle-changed  angle %.2f rad  delta %.2f rad   get_angle_delta() %.2f</tt>',
                $current,
                $delta,
                $g->get_angle_delta(),
            ));
            Demo::status(sprintf('rotated %.0f°', rad2deg($delta)));
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
