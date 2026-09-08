<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GraphenePoint3D;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GraphenePoint3D - a point in three dimensions.
 *
 * What GskTransform::translate_3d() and GtkSnapshot::translate_3d() move by. Its three fields
 * are public, so they are plain PHP properties as well as init() arguments - and it is a value,
 * so passing one around copies it.
 *
 *   bin/php-gtk4 examples/demo.php GraphenePoint3D
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GraphenePoint3D',
    'a point in three dimensions',
    function (GtkWindow $win): GtkWidget {
        $label = Demo::label();
        $at = 0;
        $render = function () use (&$at, $label): void {
            $point = new GraphenePoint3D();
            $point->init(10.0 * $at, 5.0 * $at, (float) $at);
            $copy = $point;          // the same handle
            $clone = clone $point;   // a copy of the value
            $clone->z = 99.0;

            $label->set_markup(sprintf(
                "<span size=\"x-large\"><b>(%.1f, %.1f, %.1f)</b></span>\n\n"
                . "<small>the fields are properties: <tt>\$point-&gt;z</tt> is %.1f</small>\n"
                . "<small>a clone is a copy: its z is %.1f, this one's is still %.1f</small>",
                $point->x,
                $point->y,
                $point->z,
                $copy->z,
                $clone->z,
                $point->z,
            ));
            Demo::status('click to move the point');
        };

        $button = new GtkButton();
        $button->set_child($label);
        $button->connect('clicked', function () use (&$at, $render): void {
            $at++;
            $render();
        });
        $render();
        return $button;
    },
    460,
    220,
);
