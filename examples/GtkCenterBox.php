<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkButton;
use Gtk4\GtkCenterBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkCenterBox - three slots, and the middle one really is in the middle.
 *
 * A GtkBox centres its middle child between its neighbours; a centre box centres it in the box
 * itself, however wide the other two are. That is what a header bar is made of. Click to make
 * the start widget wider and watch the centre stay where it is - then compare with the same
 * three widgets in a GtkBox below.
 *
 *   bin/php-gtk4 examples/demo.php GtkCenterBox
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkCenterBox',
    'three slots, and the middle one really is in the middle',
    function (GtkWindow $win): GtkWidget {
        $centre = new GtkCenterBox();
        $centre->set_orientation(GtkOrientation::Horizontal);

        $start = Demo::label('start');
        $middle = Demo::label('<b>centre</b>');
        $end = Demo::label('end');
        $centre->set_start_widget($start);
        $centre->set_center_widget($middle);
        $centre->set_end_widget($end);

        $widths = [40, 120, 200, 40];
        $at = 0;
        $button = new GtkButton();
        $button->set_child($centre);
        $button->connect('clicked', function () use (&$at, $widths, $start, $centre): void {
            $at++;
            $width = $widths[$at % count($widths)];
            $start->set_size_request($width, -1);
            Demo::status(sprintf(
                'the start widget is %d px wide; the centre widget has not moved (%s)',
                $width,
                $centre->get_center_widget() !== null ? 'still there' : 'gone',
            ));
        });
        $start->set_size_request($widths[0], -1);
        $end->set_size_request(40, -1);
        Demo::status('click to widen the start widget');
        return $button;
    },
    460,
    200,
);
