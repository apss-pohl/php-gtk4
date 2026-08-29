<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkGesturePan;
use Gtk4\GtkOrientation;
use Gtk4\GtkPanDirection;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkGesturePan - a drag locked to one axis.
 *
 * A GtkGestureDrag with an orientation: once the pointer has clearly moved
 * along that axis the gesture recognizes and `pan` reports a GtkPanDirection
 * plus the distance moved; a drag along the other axis is denied. The canvas
 * slides a bar by the reported offset, the button flips the orientation between
 * Horizontal and Vertical with set_orientation().
 *
 *   bin/php-gtk4 examples/demo.php GtkGesturePan
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkGesturePan',
    'a drag locked to one axis',
    function (GtkWindow $win): GtkWidget {
        /** @var GtkPanDirection|null $direction */
        $direction = null;
        $offset = 0.0;
        $readout = Demo::label('<tt>drag left/right on the canvas</tt>');

        $pan = new GtkGesturePan(GtkOrientation::Horizontal);

        $canvas = Demo::canvas(560, 260, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (
            $pan,
            &$direction,
            &$offset
        ): void {
            Demo::sheet($cr);
            $horizontal = $pan->get_orientation() === GtkOrientation::Horizontal;
            // The track along the active axis, and the bar shifted by the pan offset.
            $cr->set_source_rgba(0.47, 0.46, 0.48, 0.3);
            if ($horizontal) {
                $cr->rectangle(20, $height / 2 - 4, $width - 40, 8);
            } else {
                $cr->rectangle($width / 2 - 4, 20, 8, $height - 40);
            }
            $cr->fill();
            $shift = match ($direction) {
                GtkPanDirection::Left, GtkPanDirection::Up => -$offset,
                GtkPanDirection::Right, GtkPanDirection::Down => $offset,
                default => 0.0,
            };
            $cr->set_source_rgba(0.21, 0.52, 0.89, 0.9);
            if ($horizontal) {
                $cr->rectangle($width / 2 - 30 + $shift, $height / 2 - 20, 60, 40);
            } else {
                $cr->rectangle($width / 2 - 20, $height / 2 - 30 + $shift, 40, 60);
            }
            $cr->fill();
            Demo::text($cr, 20, 36, $horizontal ? 'pan horizontally' : 'pan vertically', Demo::MUTED, 20);
        });
        $canvas->add_controller($pan);

        $pan->connect('pan', function (
            GtkGesturePan $g,
            GtkPanDirection $dir,
            float $distance,
        ) use (
            $canvas,
            $readout,
            &$direction,
            &$offset
        ): void {
            $direction = $dir;
            $offset = $distance;
            $readout->set_markup(sprintf(
                '<tt>pan  direction %-5s  offset %.0f   orientation %s</tt>',
                $dir->name,
                $distance,
                $g->get_orientation()->name,
            ));
            $canvas->queue_draw();
        });
        // The bar snaps back when the drag ends.
        $pan->connect('drag-end', function () use ($canvas, &$direction, &$offset): void {
            Demo::status(sprintf('last pan %s by %.0f px', $direction === null ? '-' : $direction->name, $offset));
            $direction = null;
            $offset = 0.0;
            $canvas->queue_draw();
        });

        $flip = GtkButton::new_with_label('set_orientation(Vertical)');
        $flip->connect('clicked', function (GtkButton $self) use ($pan, $canvas, $readout): void {
            $next = $pan->get_orientation() === GtkOrientation::Horizontal
                ? GtkOrientation::Vertical
                : GtkOrientation::Horizontal;
            $pan->set_orientation($next);
            $self->set_label(sprintf(
                'set_orientation(%s)',
                $next === GtkOrientation::Horizontal ? 'Vertical' : 'Horizontal',
            ));
            $readout->set_markup($next === GtkOrientation::Horizontal
                ? '<tt>drag left/right on the canvas</tt>'
                : '<tt>drag up/down on the canvas</tt>');
            $canvas->queue_draw();
        });

        $page = new GtkBox(GtkOrientation::Vertical, 6);
        $page->append($readout);
        $page->append($canvas);
        $page->append($flip);
        return $page;
    },
    600,
    400,
);
