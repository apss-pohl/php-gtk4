<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkFixed;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkFixed - children at pixel coordinates.
 *
 * No layout at all: put() places a child at (x, y) and move() relocates it, in
 * the fixed's own coordinates. The page puts three labels down and walks one of
 * them around a circle on a timer, reporting get_child_position() as it goes.
 *
 *   bin/php-gtk4 examples/demo.php GtkFixed
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkFixed',
    'children at pixel coordinates - put() and move(), no layout',
    function (GtkWindow $win): GtkWidget {
        $fixed = new GtkFixed();
        $fixed->set_vexpand(true);
        $fixed->set_hexpand(true);

        $a = Demo::label('<b>put(20, 20)</b>');
        $a->add_css_class('card');
        $fixed->put($a, 20, 20);
        $b = Demo::label('<b>put(260, 160)</b>');
        $b->add_css_class('card');
        $fixed->put($b, 260, 160);
        $mover = Demo::label('<b>move()</b>');
        $mover->add_css_class('suggested-action');
        $fixed->put($mover, 180, 90);

        $status = Demo::label();
        $describe = function () use ($fixed, $mover, $status): void {
            // get_child_position() returns [x, y] as floats.
            [$x, $y] = $fixed->get_child_position($mover);
            $status->set_markup(sprintf('<tt>get_child_position(mover)  x %6.1f  y %6.1f</tt>', $x, $y));
            Demo::status(sprintf('(%.0f, %.0f)', $x, $y));
        };

        // Around a circle: coordinates are floats, sub-pixel positions are fine.
        $angle = 0.0;
        GLib::timeout_add(80, function () use ($fixed, $mover, $describe, &$angle): bool {
            $angle += 0.12;
            $fixed->move($mover, 180 + 120 * cos($angle), 90 + 60 * sin($angle));
            $describe();
            return true;
        });

        $describe();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($fixed);
        return $page;
    },
    520,
    360,
);
