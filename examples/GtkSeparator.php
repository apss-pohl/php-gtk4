<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkSeparator;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSeparator - a line between things.
 *
 * The one-property widget: a horizontal separator is a rule between rows, a
 * vertical one a rule between columns. The page puts both kinds inside a box and
 * flips the box and its separators together on a timer, so the line always
 * cuts across the box's direction.
 *
 *   bin/php-gtk4 examples/demo.php GtkSeparator
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSeparator',
    'a line between things - horizontal between rows, vertical between columns',
    function (GtkWindow $win): GtkWidget {
        $box = new GtkBox(GtkOrientation::Horizontal, 12);
        $box->set_vexpand(true);
        $box->set_homogeneous(true);

        // A separator's orientation is the line's direction, so it is the box's opposite.
        $separators = [];
        foreach (['one', 'two', 'three'] as $i => $text) {
            if ($i > 0) {
                $sep = new GtkSeparator(GtkOrientation::Vertical);
                $separators[] = $sep;
                $box->append($sep);
            }
            $cell = Demo::label("<b>$text</b>");
            $cell->add_css_class('card');
            $box->append($cell);
        }

        $status = Demo::label();
        $describe = function () use ($box, $separators, $status): void {
            $status->set_markup(sprintf(
                "<tt>box        %s\nseparators %s</tt>",
                $box->get_orientation()->name,
                $separators[0]->get_orientation()->name,
            ));
        };

        GLib::timeout_add(1500, function () use ($box, $separators, $describe): bool {
            $flipped = match ($box->get_orientation()) {
                GtkOrientation::Horizontal => GtkOrientation::Vertical,
                GtkOrientation::Vertical => GtkOrientation::Horizontal,
            };
            $box->set_orientation($flipped);
            $across = $flipped === GtkOrientation::Horizontal ? GtkOrientation::Vertical : GtkOrientation::Horizontal;
            foreach ($separators as $sep) {
                $sep->set_orientation($across);
            }
            $describe();
            Demo::status('set_orientation(' . $separators[0]->get_orientation()->name . ')');
            return true;
        });

        $describe();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($box);
        return $page;
    },
    480,
    340,
);
