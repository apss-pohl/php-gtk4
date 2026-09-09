<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GdkRectangle;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkGesture;
use Gtk4\GtkGestureClick;
use Gtk4\GtkGestureDrag;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkGesture - the abstract base of every gesture: state, bounding box, groups.
 *
 * A gesture is an event controller that tracks touch/pointer sequences and
 * decides whether they mean something. This base class answers is_active()
 * (a sequence is being tracked), is_recognized() (it matched), the bounding
 * box of the tracked points (get_bounding_box() as a GdkRectangle, its centre
 * as [x, y]) and the grouping API: gestures that group() share their sequences
 * instead of competing. It cannot be constructed; the page puts a GtkGestureDrag
 * and a GtkGestureClick on one canvas, reads the base API from inside the drag
 * and lets the button group() and ungroup() them.
 *
 *   bin/php-gtk4 examples/demo.php GtkGesture
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkGesture',
    'the abstract base of every gesture: state, bounding box, groups',
    function (GtkWindow $win): GtkWidget {
        /** @var GdkRectangle|null $box the last get_bounding_box() */
        $box = null;
        /** @var array{float, float}|null $center */
        $center = null;
        $readout = Demo::label('<tt>drag on the canvas</tt>');

        $canvas = Demo::canvas(560, 240, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (
            &$box,
            &$center
        ): void {
            Demo::sheet($cr);
            if ($box === null) {
                Demo::text($cr, 20, 36, 'drag here', Demo::MUTED, 20);
                return;
            }
            // With one pointer the bounding box is a point; it grows with more touch points.
            $cr->set_source_rgba(0.21, 0.52, 0.89, 0.25);
            $cr->rectangle($box->x - 10, $box->y - 10, $box->width + 20, $box->height + 20);
            $cr->fill();
            if ($center !== null) {
                $cr->set_source_rgba(0.88, 0.11, 0.14, 0.9);
                $cr->arc($center[0], $center[1], 4, 0, 2 * M_PI);
                $cr->fill();
            }
        });

        $drag = new GtkGestureDrag();
        $click = new GtkGestureClick();
        $canvas->add_controller($drag);
        $canvas->add_controller($click);

        $describe = static function (GtkGesture $g, string $signal) use ($readout, $click, &$box, &$center): void {
            // The bounding box is only available while a sequence is active.
            $box = $g->is_active() ? $g->get_bounding_box() : null;
            $center = $g->is_active() ? $g->get_bounding_box_center() : null;
            $readout->set_markup(sprintf(
                "<tt>%-11s is_active %-5s  is_recognized %-5s  box %s\ngroup size %d   is_grouped_with(click) %s</tt>",
                $signal,
                $g->is_active() ? 'true' : 'false',
                $g->is_recognized() ? 'true' : 'false',
                $box === null ? 'null' : sprintf('%d,%d %dx%d', $box->x, $box->y, $box->width, $box->height),
                count($g->get_group()),
                $g->is_grouped_with($click) ? 'true' : 'false',
            ));
        };

        $drag->connect('drag-begin', function (GtkGestureDrag $g) use ($describe, $canvas): void {
            $describe($g, 'drag-begin');
            $canvas->queue_draw();
        });
        $drag->connect('drag-update', function (GtkGestureDrag $g) use ($describe, $canvas): void {
            $describe($g, 'drag-update');
            $canvas->queue_draw();
        });
        $drag->connect('drag-end', function (GtkGestureDrag $g) use ($describe, $canvas): void {
            $describe($g, 'drag-end');
            $canvas->queue_draw();
        });
        $click->connect('pressed', function (GtkGestureClick $g, int $n_press) use ($describe): void {
            $describe($g, sprintf('pressed(%d)', $n_press));
        });

        $toggle = GtkButton::new_with_label('group(drag, click)');
        $toggle->connect('clicked', function (GtkButton $self) use ($drag, $click, $describe): void {
            if ($drag->is_grouped_with($click)) {
                $drag->ungroup();
                $self->set_label('group(drag, click)');
            } else {
                $drag->group($click);   // both now see the same sequences
                $self->set_label('ungroup()');
            }
            $describe($drag, 'grouping');
            Demo::status($drag->is_grouped_with($click) ? 'grouped' : 'not grouped');
        });

        $page = new GtkBox(GtkOrientation::Vertical, 6);
        $page->append($readout);
        $page->append($canvas);
        $page->append($toggle);
        return $page;
    },
    600,
    400,
);
