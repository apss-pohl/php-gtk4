<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GtkBox;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkGestureClick;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkGestureClick - presses and releases, counted into single/double/triple clicks.
 *
 * GTK 4 has no button-press-event: pointer input reaches a widget through event
 * controllers, and a GtkGestureClick is the one for clicks. It is attached with
 * add_controller() and reports `pressed` (with the click count), `released`,
 * `stopped` (the click sequence timed out) and `unpaired-release` (a release
 * whose press went elsewhere). The canvas draws a ring per press, one ring per
 * n_press, with the button number from get_current_button(); set_button(0)
 * makes it listen to every mouse button.
 *
 *   bin/php-gtk4 examples/demo.php GtkGestureClick
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkGestureClick',
    'presses and releases, counted into single/double/triple clicks',
    function (GtkWindow $win): GtkWidget {
        /** @var list<array{x: float, y: float, n: int, button: int, down: bool}> $marks */
        $marks = [];
        $last = 'click anywhere below - any mouse button, single, double or triple';

        $canvas = Demo::canvas(560, 300, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (
            &$marks,
            &$last
        ): void {
            Demo::sheet($cr);
            foreach ($marks as $mark) {
                $cr->set_source_rgba(0.21, 0.52, 0.89, $mark['down'] ? 0.9 : 0.35);
                $cr->set_line_width(3);
                for ($i = 1; $i <= $mark['n']; $i++) {
                    $cr->arc($mark['x'], $mark['y'], 8 + $i * 8, 0, 2 * M_PI);
                    $cr->stroke();
                }
                Demo::text(
                    $cr,
                    $mark['x'] + 12,
                    $mark['y'] - 12,
                    sprintf('n_press %d · button %d', $mark['n'], $mark['button']),
                    Demo::MUTED,
                    11,
                );
            }
            Demo::text($cr, 12, $height - 12, $last, Demo::INK, 12);
        });

        $click = new GtkGestureClick();
        $click->set_button(0);          // 0 = react to every button, not only the primary one
        $canvas->add_controller($click);

        $click->connect('pressed', function (
            GtkGestureClick $g,
            int $n_press,
            float $x,
            float $y,
        ) use (
            $canvas,
            &$marks,
            &$last
        ): void {
            if (count($marks) > 30) {
                array_shift($marks);
            }
            $marks[] = ['x' => $x, 'y' => $y, 'n' => $n_press, 'button' => $g->get_current_button(), 'down' => true];
            $last = sprintf('pressed  n_press=%d  at %.0f,%.0f  button %d', $n_press, $x, $y, $g->get_current_button());
            Demo::status($last);
            $canvas->queue_draw();
        });
        $click->connect('released', function (
            GtkGestureClick $g,
            int $n_press,
            float $x,
            float $y,
        ) use (
            $canvas,
            &$marks,
            &$last
        ): void {
            $marks[array_key_last($marks)]['down'] = false;
            $last = sprintf('released n_press=%d  at %.0f,%.0f', $n_press, $x, $y);
            $canvas->queue_draw();
        });
        // stopped: the multi-click timeout passed, the next press starts at n_press 1 again.
        $click->connect('stopped', function () use (&$last, $canvas): void {
            $last .= '  · stopped';
            $canvas->queue_draw();
        });
        $click->connect('unpaired-release', function (
            GtkGestureClick $g,
            float $x,
            float $y,
            int $button,
        ) use (
            &$last,
            $canvas
        ): void {
            $last = sprintf('unpaired-release at %.0f,%.0f  button %d', $x, $y, $button);
            $canvas->queue_draw();
        });

        $page = new GtkBox(GtkOrientation::Vertical, 6);
        $page->append(Demo::label(
            '<b>GtkGestureClick</b> on a GtkDrawingArea - pressed · released · stopped · unpaired-release',
        ));
        $page->append($canvas);
        return $page;
    },
    600,
    380,
);
