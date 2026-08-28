<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkOrientable - the interface behind GtkBox::set_orientation() and friends.
 *
 * GtkOrientable is a real PHP interface implemented once for every orientable
 * widget. The box flips between Horizontal and Vertical every second and the three
 * labels rearrange.
 *
 *   bin/php-gtk4 examples/demo.php GtkOrientable
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkOrientable',
    'the orientation interface every box, paned and scale implement',
    function (GtkWindow $win): GtkWidget {
        $box = new GtkBox(GtkOrientation::Horizontal, 8);
        foreach (['one', 'two', 'three'] as $text) {
            $box->append(Demo::label("<b>$text</b>"));
        }
        $flip = function () use ($box): void {
            $next = $box->get_orientation() === GtkOrientation::Horizontal
                ? GtkOrientation::Vertical
                : GtkOrientation::Horizontal;
            $box->set_orientation($next);
            Demo::status(sprintf(
                '%s implements %s; get_orientation() = %s',
                $box::class,
                implode(', ', array_keys(class_implements($box) ?: [])),
                $box->get_orientation()->name,
            ));
        };
        $flip();
        GLib::timeout_add(1000, function () use ($flip): bool {
            $flip();
            return true;
        });
        return $box;
    },
    480,
    340,
);
