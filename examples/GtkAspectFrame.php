<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAspectFrame;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkAspectFrame - a child kept at a shape, whatever the space.
 *
 * The frame takes all the room it is given and hands its child a rectangle of the ratio asked
 * for, aligned by xalign/yalign inside what is left over. obey_child(true) uses the child's own
 * preferred ratio instead of the number. Resize the window and the drawing keeps its shape;
 * click to walk through the ratios and alignments.
 *
 *   bin/php-gtk4 examples/demo.php GtkAspectFrame
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkAspectFrame',
    'a child kept at a shape, whatever the space',
    function (GtkWindow $win): GtkWidget {
        // The child: a filled rectangle, so its shape is visible at a glance.
        $canvas = Demo::canvas(160, 90, static function ($area, $cr, int $w, int $h): void {
            $cr->set_source_rgb(0.208, 0.518, 0.894);
            $cr->rectangle(0, 0, $w, $h);
            $cr->fill();
            $cr->set_source_rgb(1.0, 1.0, 1.0);
            $cr->rectangle(4, 4, $w - 8, $h - 8);
            $cr->set_line_width(2);
            $cr->stroke();
        });
        $canvas->set_vexpand(true);
        $canvas->set_hexpand(true);

        $frame = new GtkAspectFrame(0.5, 0.5, 16 / 9, false);
        $frame->set_child($canvas);
        $frame->set_vexpand(true);
        $frame->set_hexpand(true);

        /** @var list<array{string, float, float, float, bool}> $steps */
        $steps = [
            ['16:9, centred', 16 / 9, 0.5, 0.5, false],
            ['1:1, centred', 1.0, 0.5, 0.5, false],
            ['1:1, top left', 1.0, 0.0, 0.0, false],
            ['3:1, bottom right', 3.0, 1.0, 1.0, false],
            ["the child's own ratio (obey_child)", 1.0, 0.5, 0.5, true],
        ];
        $at = 0;
        $apply = function () use (&$at, $steps, $frame): void {
            [$name, $ratio, $x, $y, $obey] = $steps[$at % count($steps)];
            $frame->set_ratio($ratio);
            $frame->set_xalign($x);
            $frame->set_yalign($y);
            $frame->set_obey_child($obey);
            Demo::status($name . ' - click for the next, or resize the window');
        };

        $button = new GtkButton();
        $button->set_child($frame);
        $button->set_vexpand(true);
        $button->connect('clicked', function () use (&$at, $apply): void {
            $at++;
            $apply();
        });
        $apply();
        return $button;
    },
    460,
    320,
);
