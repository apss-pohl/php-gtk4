<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GObject;
use Gtk4\GtkLabel;
use Gtk4\GtkListBox;
use Gtk4\GtkListBoxRow;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkSelectionMode;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkListBox - a list of rows, sorted, filtered and headed from PHP.
 *
 * The four callbacks are the whole point of the widget: bind_model() builds one row per item of
 * a GListModel and follows it afterwards, set_sort_func() and set_filter_func() decide the order
 * and what is shown, and set_header_func() is handed each row with the one above it so it can
 * put a heading on the first of a group. Click the list to cycle through the arrangements; the
 * rows never move in the model, only in the box.
 *
 *   bin/php-gtk4 examples/demo.php GtkListBox
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkListBox',
    'a list of rows, sorted, filtered and headed from PHP',
    function (GtkWindow $win): GtkWidget {
        $store = Demo::store();
        $box = new GtkListBox();
        $box->set_selection_mode(GtkSelectionMode::Single);

        // One row per item, and the box follows the model from here on.
        $box->bind_model($store, static function (GObject $item): GtkListBoxRow {
            $row = new GtkListBoxRow();
            $row->set_child(Demo::label(sprintf(
                '<b>%s</b>  <span foreground="%s">born %d</span>',
                htmlspecialchars(Demo::name($item)),
                Demo::MUTED,
                Demo::born($item),
            )));
            return $row;
        });

        // The sort, filter and header functions read a row's label back out of the widget,
        // which is all a row knows about itself.
        $textOf = static function (GtkListBoxRow $row): string {
            $child = $row->get_child();
            return $child instanceof GtkLabel ? $child->get_text() : '';
        };
        $bornOf = static function (GtkListBoxRow $row) use ($textOf): int {
            return preg_match('/born (\d{4})/', $textOf($row), $m) === 1 ? (int) $m[1] : 0;
        };

        // What each click switches to.
        $byName = static fn(GtkListBoxRow $a, GtkListBoxRow $b): int
            => strcmp($textOf($a), $textOf($b));
        /** @var list<array{string, ?callable, ?callable, bool}> $arrangements */
        $arrangements = [
            ['as the model has them', null, null, false],
            ['sorted by name', $byName, null, false],
            ['sorted, with a heading per initial', $byName, null, true],
            ['sorted, and only those born before 1915', $byName,
                static fn(GtkListBoxRow $row): bool => $bornOf($row) < 1915, true],
        ];

        $at = 0;
        $apply = function () use (&$at, $arrangements, $box, $textOf): void {
            [$name, $sort, $filter, $headers] = $arrangements[$at % count($arrangements)];
            $box->set_sort_func($sort);
            $box->set_filter_func($filter);
            // A heading on the first row of every group, which is every row whose initial
            // differs from the one above it.
            $header = static function (GtkListBoxRow $row, ?GtkListBoxRow $before) use ($textOf): void {
                $initial = substr($textOf($row), 0, 1);
                $sameGroup = $before !== null && substr($textOf($before), 0, 1) === $initial;
                $row->set_header($sameGroup ? null : Demo::label('<b>' . htmlspecialchars($initial) . '</b>'));
            };
            $box->set_header_func($headers === true ? $header : null);
            Demo::status($name . ' - click the list for the next arrangement');
        };

        $box->connect('row-activated', function (GtkListBox $b, GtkListBoxRow $row) use (&$at, $apply): void {
            $at++;
            $apply();
        });
        $apply();

        $scroller = new GtkScrolledWindow();
        $scroller->set_child($box);
        $scroller->set_vexpand(true);
        return $scroller;
    },
    480,
    360,
);
