<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkGestureLongPress;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkGestureLongPress - press and hold without moving.
 *
 * `pressed` fires once the pointer has been held still for the long-press
 * time (the gtk-long-press-time setting, ~500 ms, times the delay factor);
 * `cancelled` fires when it is released or moved away before that. The canvas
 * drops a filled dot where a long press succeeded and a hollow one where it
 * was cancelled; the button cycles set_delay_factor() through 0.5, 1 and 2.
 *
 *   bin/php-gtk4 examples/demo.php GtkGestureLongPress
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkGestureLongPress',
    'press and hold without moving',
    function (GtkWindow $win): GtkWidget {
        /** @var list<array{x: float, y: float, ok: bool}> $marks */
        $marks = [];
        $pending = null;    // where the current press started, until pressed/cancelled decides
        $readout = Demo::label('<tt>press and hold on the canvas</tt>');

        $canvas = Demo::canvas(560, 260, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (&$marks): void {
            Demo::sheet($cr);
            Demo::text($cr, 20, 36, 'hold here', Demo::MUTED, 20);
            foreach ($marks as $mark) {
                if ($mark['ok']) {
                    $cr->set_source_rgba(0.18, 0.76, 0.49, 0.9);
                    $cr->arc($mark['x'], $mark['y'], 14, 0, 2 * M_PI);
                    $cr->fill();
                } else {
                    $cr->set_source_rgba(0.88, 0.11, 0.14, 0.9);
                    $cr->set_line_width(2);
                    $cr->arc($mark['x'], $mark['y'], 14, 0, 2 * M_PI);
                    $cr->stroke();
                }
            }
        });

        $press = new GtkGestureLongPress();
        $canvas->add_controller($press);

        $press->connect('pressed', function (
            GtkGestureLongPress $g,
            float $x,
            float $y,
        ) use (
            $canvas,
            $readout,
            &$marks
        ): void {
            $marks[] = ['x' => $x, 'y' => $y, 'ok' => true];
            $readout->set_markup(sprintf(
                '<tt>pressed    at %.0f,%.0f   (delay factor %.1f)</tt>',
                $x,
                $y,
                $g->get_delay_factor(),
            ));
            $canvas->queue_draw();
        });
        // cancelled carries no coordinates: the press was released or dragged before the timeout.
        $press->connect('cancelled', function (GtkGestureLongPress $g) use ($readout): void {
            $readout->set_markup(sprintf(
                '<tt>cancelled  - released or moved too early (delay factor %.1f)</tt>',
                $g->get_delay_factor(),
            ));
        });

        $factors = [0.5, 1.0, 2.0];
        $step = 1;
        $button = GtkButton::new_with_label('delay factor 1.0');
        $button->connect('clicked', function (GtkButton $self) use ($press, $factors, &$step): void {
            $step = ($step + 1) % count($factors);
            $press->set_delay_factor($factors[$step]);
            $self->set_label(sprintf('delay factor %.1f', $press->get_delay_factor()));
            Demo::status(sprintf('set_delay_factor(%.1f)', $factors[$step]));
        });

        $page = new GtkBox(GtkOrientation::Vertical, 6);
        $page->append($readout);
        $page->append($canvas);
        $page->append($button);
        return $page;
    },
    600,
    400,
);
