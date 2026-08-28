<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkPickFlags;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPickFlags - what pick() may return: whether insensitive or non-targetable widgets count as hits.
 *
 * pick(x, y, flags) is hit-testing without a pointer: which descendant is at these
 * coordinates? A timer sweeps a point across a row of buttons (the middle one
 * insensitive) with DEFAULT and then with INSENSITIVE.
 *
 *   bin/php-gtk4 examples/demo.php GtkPickFlags
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPickFlags',
    'hit-testing with and without insensitive widgets',
    function (GtkWindow $win): GtkWidget {
        $row = new GtkBox(GtkOrientation::Horizontal, 8);
        foreach (['left', 'middle', 'right'] as $name) {
            $button = GtkButton::new_with_label($name);
            $button->set_name($name);
            $button->set_size_request(100, 50);
            $row->append($button);
        }
        $row->get_children()[1]->set_sensitive(false);
        $column = new GtkBox(GtkOrientation::Vertical, 12);
        $column->append($row);
        $report = Demo::label();
        $column->append($report);

        $tick = 0;
        GLib::timeout_add(500, function () use ($row, $report, &$tick): bool {
            $x = 20.0 + 108.0 * ($tick % 3);
            $flags = intdiv($tick, 3) % 2 === 0 ? GtkPickFlags::DEFAULT : GtkPickFlags::INSENSITIVE;
            $hit = $row->pick($x, 25.0, $flags);
            $report->set_markup(sprintf(
                'pick(%.0f, 25, GtkPickFlags::%s) = <b>%s</b>',
                $x,
                $flags === GtkPickFlags::DEFAULT ? 'DEFAULT' : 'INSENSITIVE',
                $hit instanceof GtkButton ? $hit->get_name() : ($hit === null ? 'null' : $hit::class),
            ));
            $tick++;
            return true;
        });
        return $column;
    },
    480,
    340,
);
