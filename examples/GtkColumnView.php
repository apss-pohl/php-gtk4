<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GListStore;
use Gtk4\GObject;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkColumnView;
use Gtk4\GtkColumnViewColumn;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkOrientation;
use Gtk4\GtkPolicyType;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkSortListModel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PhpValue;

/*
 * Gtk4\GtkColumnView - the same list model, shown as a table.
 *
 * Every column is a GtkColumnViewColumn with a factory of its own, so a row is
 * built once per column and the items stay whatever they are - here PhpValues
 * holding a PHP array. The view has a `sorter` that follows the column headers;
 * feeding it to a GtkSortListModel is what actually sorts, and clicking a header
 * (the columns below carry a sorter) reorders the rows.
 *
 *   bin/php-gtk4 examples/demo.php GtkColumnView
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkColumnView',
    'the same list model, shown as a table',
    function (GtkWindow $win): GtkWidget {
        $rows = [
            ['Mercury', 4879, 0],
            ['Venus', 12104, 0],
            ['Earth', 12742, 1],
            ['Mars', 6779, 2],
            ['Jupiter', 139820, 95],
            ['Saturn', 116460, 146],
        ];
        $store = new GListStore(PhpValue::class);
        foreach ($rows as $row) {
            $store->append(new PhpValue($row));
        }

        $view = new GtkColumnView();
        $view->set_show_column_separators(true);
        $view->set_show_row_separators(true);

        // The item of a row is a PhpValue holding the array above: one field per column.
        $field = static function (?GObject $item, int $index): string {
            $row = $item instanceof PhpValue ? $item->get_value() : null;
            return is_array($row) && isset($row[$index]) && is_scalar($row[$index])
                ? (string) $row[$index]
                : '?';
        };

        // One factory per column: the same GtkListItem shape, a different field in it.
        $column = function (string $title, int $index, float $xalign) use ($view, $field): GtkColumnViewColumn {
            $factory = new GtkSignalListItemFactory();
            $factory->connect('setup', function (
                GtkSignalListItemFactory $f,
                GtkListItem $item,
            ) use ($xalign): void {
                $cell = new GtkLabel();
                $cell->set_xalign($xalign);
                $item->set_child($cell);
            });
            $factory->connect('bind', function (
                GtkSignalListItemFactory $f,
                GtkListItem $item,
            ) use (
                $field,
                $index
            ): void {
                $cell = $item->get_child();
                if ($cell instanceof GtkLabel) {
                    $cell->set_text($field($item->get_item(), $index));
                }
            });
            $col = new GtkColumnViewColumn($title, $factory);
            $view->append_column($col);
            return $col;
        };

        $column('Planet', 0, 0.0)->set_expand(true);
        $column('Diameter (km)', 1, 1.0);
        $column('Moons', 2, 1.0);

        // The view's sorter is the one the headers drive; the sorted model is what the
        // view then shows, so a click on a header reorders these six rows.
        $sorted = new GtkSortListModel($store, $view->get_sorter());
        $view->set_model(new GtkSingleSelection($sorted));

        $scroller = new GtkScrolledWindow();
        $scroller->set_policy(GtkPolicyType::Automatic, GtkPolicyType::Automatic);
        $scroller->set_child($view);
        $scroller->set_vexpand(true);

        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $head = Demo::label(
            '<b>three columns over one model</b>'
            . "\n<small>the columns carry no sorter yet - see GtkColumnViewColumn</small>",
        );
        $head->set_halign(GtkAlign::Center);
        $page->append($head);
        $page->append($scroller);
        return $page;
    },
    520,
    400,
);
