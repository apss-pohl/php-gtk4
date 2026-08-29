<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GtkBox;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkGestureSwipe;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkGestureSwipe - a drag judged by its speed at release.
 *
 * The `swipe` signal fires when the pointer is released and carries the
 * velocity in pixels per second on each axis; get_velocity() reads the same
 * pair while the gesture is still active. The canvas draws the last swipe as
 * an arrow from the centre, scaled down so a fast flick still fits, and keeps
 * the fastest one so far in the window title.
 *
 *   bin/php-gtk4 examples/demo.php GtkGestureSwipe
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkGestureSwipe',
    'a drag judged by its speed at release',
    function (GtkWindow $win): GtkWidget {
        /** @var array{float, float}|null $velocity of the last swipe */
        $velocity = null;
        $fastest = 0.0;
        $readout = Demo::label('<tt>flick across the canvas and let go</tt>');

        $canvas = Demo::canvas(560, 280, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (&$velocity): void {
            Demo::sheet($cr);
            $cx = $width / 2;
            $cy = $height / 2;
            $cr->set_source_rgba(0.47, 0.46, 0.48, 0.5);
            $cr->set_line_width(1);
            $cr->arc($cx, $cy, 6, 0, 2 * M_PI);
            $cr->stroke();
            if ($velocity === null) {
                Demo::text($cr, 20, 36, 'swipe here', Demo::MUTED, 20);
                return;
            }
            // 1000 px/s becomes 100 px of arrow.
            [$vx, $vy] = $velocity;
            $ex = $cx + $vx / 10;
            $ey = $cy + $vy / 10;
            $cr->set_source_rgba(0.21, 0.52, 0.89, 1.0);
            $cr->set_line_width(4);
            $cr->move_to($cx, $cy);
            $cr->line_to($ex, $ey);
            $cr->stroke();
            $angle = atan2($vy, $vx);
            $cr->move_to($ex, $ey);
            $cr->line_to($ex - 14 * cos($angle - 0.5), $ey - 14 * sin($angle - 0.5));
            $cr->line_to($ex - 14 * cos($angle + 0.5), $ey - 14 * sin($angle + 0.5));
            $cr->close_path();
            $cr->fill();
            Demo::text($cr, $ex + 8, $ey + 4, sprintf('%.0f px/s', hypot($vx, $vy)), Demo::INK, 12);
        });

        $swipe = new GtkGestureSwipe();
        $canvas->add_controller($swipe);

        $swipe->connect('swipe', function (
            GtkGestureSwipe $g,
            float $vx,
            float $vy,
        ) use (
            $canvas,
            $readout,
            &$velocity,
            &$fastest
        ): void {
            // get_velocity() answers while the sequence is still active - inside this signal it is.
            $velocity = $g->is_active() ? ($g->get_velocity() ?? [$vx, $vy]) : [$vx, $vy];
            $speed = hypot($vx, $vy);
            $fastest = max($fastest, $speed);
            $readout->set_markup(sprintf('<tt>swipe  velocity %+.0f, %+.0f px/s   speed %.0f</tt>', $vx, $vy, $speed));
            Demo::status(sprintf('fastest so far %.0f px/s', $fastest));
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
