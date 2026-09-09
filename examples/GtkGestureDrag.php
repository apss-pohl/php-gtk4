<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GtkBox;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkGestureDrag;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkGestureDrag - press, move, release, reported as a start point and an offset.
 *
 * `drag-begin` gives the start coordinates, every `drag-update` the offset from
 * there (not the absolute position), `drag-end` the final offset. While the
 * drag is active get_start_point() and get_offset() return the same pair as
 * [x, y] lists - and null once it is over, which is why the handlers read them
 * only from inside the signals. The canvas rubber-bands a rectangle from the
 * start point and keeps the last one drawn.
 *
 *   bin/php-gtk4 examples/demo.php GtkGestureDrag
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkGestureDrag',
    'press, move, release - a start point and an offset',
    function (GtkWindow $win): GtkWidget {
        /** @var array{float, float}|null $start from drag-begin */
        $start = null;
        /** @var array{float, float} $offset */
        $offset = [0.0, 0.0];
        /** @var bool $dragging */
        $dragging = false;
        $readout = Demo::label('<tt>press and drag on the canvas</tt>');

        $canvas = Demo::canvas(560, 280, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (
            &$start,
            &$offset,
            &$dragging
        ): void {
            Demo::sheet($cr);
            if ($start === null) {
                Demo::text($cr, 20, 40, 'drag here', Demo::MUTED, 20);
                return;
            }
            [$x, $y] = $start;
            [$dx, $dy] = $offset;
            // The rubber band: filled while dragging, outline only afterwards.
            $cr->rectangle($x, $y, $dx, $dy);
            $cr->set_source_rgba(0.21, 0.52, 0.89, $dragging ? 0.3 : 0.1);
            $cr->fill_preserve();
            $cr->set_source_rgba(0.21, 0.52, 0.89, 1.0);
            $cr->set_line_width(2);
            $cr->stroke();
            // Start point and the offset vector.
            $cr->arc($x, $y, 5, 0, 2 * M_PI);
            $cr->fill();
            $cr->move_to($x, $y);
            $cr->line_to($x + $dx, $y + $dy);
            $cr->set_source_rgba(0.14, 0.12, 0.19, 0.8);
            $cr->stroke();
            Demo::text($cr, $x + $dx + 8, $y + $dy + 4, sprintf('%+.0f, %+.0f', $dx, $dy), Demo::INK, 12);
        });

        $drag = new GtkGestureDrag();
        $canvas->add_controller($drag);

        $describe = static function (GtkGestureDrag $g, string $signal) use ($readout, &$start, &$offset): void {
            // get_start_point()/get_offset() are only meaningful while the gesture is active.
            $point = $g->is_active() ? $g->get_start_point() : null;
            $readout->set_markup(sprintf(
                '<tt>%-11s start %s   offset %+.0f,%+.0f   get_start_point() %s</tt>',
                $signal,
                $start === null ? '-' : sprintf('%.0f,%.0f', $start[0], $start[1]),
                $offset[0],
                $offset[1],
                $point === null ? 'null' : sprintf('[%.0f, %.0f]', $point[0], $point[1]),
            ));
        };

        $drag->connect('drag-begin', function (
            GtkGestureDrag $g,
            float $x,
            float $y,
        ) use (
            $canvas,
            $describe,
            &$start,
            &$offset,
            &$dragging
        ): void {
            $start = [$x, $y];
            $offset = [0.0, 0.0];
            $dragging = true;
            $describe($g, 'drag-begin');
            $canvas->queue_draw();
        });
        $drag->connect('drag-update', function (
            GtkGestureDrag $g,
            float $dx,
            float $dy,
        ) use (
            $canvas,
            $describe,
            &$offset
        ): void {
            $offset = $g->get_offset() ?? [$dx, $dy];   // the same numbers the signal carries
            $describe($g, 'drag-update');
            $canvas->queue_draw();
        });
        $drag->connect('drag-end', function (
            GtkGestureDrag $g,
            float $dx,
            float $dy,
        ) use (
            $canvas,
            $describe,
            &$offset,
            &$dragging
        ): void {
            $offset = [$dx, $dy];
            $dragging = false;
            $describe($g, 'drag-end');
            Demo::status(sprintf('dragged %+.0f, %+.0f', $dx, $dy));
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
