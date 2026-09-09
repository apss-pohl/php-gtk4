<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkBox;
use Gtk4\GtkGridView;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkOrientation;
use Gtk4\GtkPolicyType;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkGridView - the same list, laid out in a grid of equal cells.
 *
 * GtkListView and GtkGridView are the same machinery (a GtkSelectionModel plus
 * a factory) with a different layout: the grid fills as many columns as fit
 * between min-columns and max-columns and wraps. The timer walks max-columns
 * from 2 to 6 so the reflow is visible.
 *
 *   bin/php-gtk4 examples/demo.php GtkGridView
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkGridView',
    'the same list, laid out in a grid of equal cells',
    function (GtkWindow $win): GtkWidget {
        $emoji = ['🍎', '🍌', '🍇', '🍒', '🍑', '🍍', '🥝', '🍉', '🍓', '🥥', '🍊', '🍋',
            '🫐', '🥭', '🍐', '🍏', '🍈', '🥑'];
        $model = new GtkSingleSelection(new GtkStringList($emoji));

        $factory = new GtkSignalListItemFactory();
        $factory->connect('setup', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $cell = new GtkLabel();
            $cell->set_size_request(56, 56);
            $item->set_child($cell);
        });
        $factory->connect('bind', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $cell = $item->get_child();
            $object = $item->get_item();
            if ($cell instanceof GtkLabel && $object instanceof GtkStringObject) {
                $cell->set_markup('<span size="xx-large">' . $object->get_string() . '</span>');
            }
        });

        $grid = new GtkGridView($model, $factory);
        $grid->set_min_columns(2);
        $grid->set_max_columns(2);
        $grid->set_single_click_activate(true);
        $grid->connect('activate', function (GtkGridView $g, int $position) use ($model): void {
            $item = $model->get_item($position);
            Demo::status(sprintf(
                'cell %d: %s',
                $position,
                $item instanceof GtkStringObject ? $item->get_string() : '?',
            ));
        });

        $scroller = new GtkScrolledWindow();
        $scroller->set_policy(GtkPolicyType::Never, GtkPolicyType::Automatic);
        $scroller->set_child($grid);
        $scroller->set_vexpand(true);

        $caption = Demo::label();
        $columns = 2;
        $show = function () use ($grid, $caption, &$columns): void {
            $grid->set_max_columns($columns);
            $caption->set_markup(sprintf(
                "<b>max-columns %d</b>  <small>(min %d)</small>\n"
                . '<small>the model never changes - only how many cells fit in a row</small>',
                $grid->get_max_columns(),
                $grid->get_min_columns(),
            ));
        };
        $show();
        GLib::timeout_add(1400, function () use (&$columns, $show): bool {
            $columns = $columns >= 6 ? 2 : $columns + 1;   // never below min-columns
            $show();
            return true;
        });

        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($caption);
        $page->append($scroller);
        return $page;
    },
    460,
    420,
);
