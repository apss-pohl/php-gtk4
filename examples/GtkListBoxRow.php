<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkListBox;
use Gtk4\GtkListBoxRow;
use Gtk4\GtkSelectionMode;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkListBoxRow - one row of a GtkListBox, and what it knows about itself.
 *
 * A row carries its child, its position in the box (which changes when the box is sorted), a
 * header the box's header function gives it, and two flags that decide what clicking does:
 * activatable rows emit ::row-activated, selectable rows can be selected. The rows below are
 * each configured differently - click them and watch the status line.
 *
 *   bin/php-gtk4 examples/demo.php GtkListBoxRow
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkListBoxRow',
    'one row of a list: its index, its header, and what clicking it does',
    function (GtkWindow $win): GtkWidget {
        $box = new GtkListBox();
        $box->set_selection_mode(GtkSelectionMode::Single);

        /** @var list<array{string, bool, bool}> $rows name, activatable, selectable */
        $rows = [
            ['ordinary - selectable and activatable', true, true],
            ['not selectable - a click still activates it', true, false],
            ['not activatable - a click only selects it', false, true],
            ['neither - a click does nothing at all', false, false],
        ];
        foreach ($rows as [$text, $activatable, $selectable]) {
            $row = new GtkListBoxRow();
            $row->set_activatable($activatable);
            $row->set_selectable($selectable);
            $row->set_child(Demo::label(htmlspecialchars($text)));
            $box->append($row);
        }

        // A header on the first row only, the way a header function would put one there.
        $box->get_row_at_index(0)?->set_header(Demo::label(
            '<b>four rows, four behaviours</b>',
        ));

        $describe = static function (GtkListBoxRow $row, string $what): void {
            Demo::status(sprintf(
                '%s row %d · selected: %s · activatable: %s · selectable: %s · header: %s',
                $what,
                $row->get_index(),
                $row->is_selected() ? 'yes' : 'no',
                $row->get_activatable() ? 'yes' : 'no',
                $row->get_selectable() ? 'yes' : 'no',
                $row->get_header() !== null ? 'yes' : 'no',
            ));
        };
        $box->connect('row-activated', static function (GtkListBox $b, GtkListBoxRow $row) use ($describe): void {
            $describe($row, 'activated');
        });
        $box->connect('row-selected', static function (GtkListBox $b, ?GtkListBoxRow $row) use ($describe): void {
            if ($row !== null) {
                $describe($row, 'selected');
            }
        });

        Demo::status('click a row');
        return $box;
    },
    480,
    280,
);
