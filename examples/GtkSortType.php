<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GListStore;
use Gtk4\GObject;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkColumnView;
use Gtk4\GtkColumnViewColumn;
use Gtk4\GtkCustomSorter;
use Gtk4\GtkLabel;
use Gtk4\GtkListItem;
use Gtk4\GtkOrientation;
use Gtk4\GtkSignalListItemFactory;
use Gtk4\GtkSingleSelection;
use Gtk4\GtkSortListModel;
use Gtk4\GtkSortType;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PhpValue;

/*
 * Gtk4\GtkSortType - ascending or descending, the direction a column sorts in.
 *
 * Bound as a native PHP enum with two cases. GtkColumnView::sort_by_column()
 * takes it: the column decides *what* is compared (its GtkSorter), this decides
 * which way round. Clicking the header does the same thing and flips the
 * direction on every second click.
 *
 *   bin/php-gtk4 examples/demo.php GtkSortType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSortType',
    'ascending or descending, the direction a column sorts in',
    function (GtkWindow $win): GtkWidget {
        $store = new GListStore(PhpValue::class);
        foreach (['Mercury', 'Venus', 'Earth', 'Mars', 'Jupiter', 'Saturn'] as $planet) {
            $store->append(new PhpValue($planet));
        }
        $nameOf = static function (GObject $item): string {
            $value = $item instanceof PhpValue ? $item->get_value() : null;
            return is_string($value) ? $value : '?';
        };

        $factory = new GtkSignalListItemFactory();
        $factory->connect('setup', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
            $item->set_child(new GtkLabel());
        });
        $factory->connect('bind', function (
            GtkSignalListItemFactory $f,
            GtkListItem $item,
        ) use ($nameOf): void {
            $label = $item->get_child();
            $object = $item->get_item();
            if ($label instanceof GtkLabel && $object !== null) {
                $label->set_text($nameOf($object));
            }
        });

        $view = new GtkColumnView();
        $column = new GtkColumnViewColumn('Planet', $factory);
        $column->set_sorter(new GtkCustomSorter(fn(GObject $a, GObject $b): int
            => strcmp($nameOf($a), $nameOf($b))));
        $view->append_column($column);
        $view->set_model(new GtkSingleSelection(new GtkSortListModel($store, $view->get_sorter())));
        $view->set_vexpand(true);

        $caption = Demo::label();
        $cases = GtkSortType::cases();
        $step = 0;
        $apply = function () use ($view, $column, $caption, $cases, &$step): void {
            $direction = $cases[$step % count($cases)];
            $view->sort_by_column($column, $direction);
            $caption->set_markup(sprintf(
                "<b>GtkSortType::%s (%d)</b>\n<small>sort_by_column(column, %s) - or click the header</small>",
                $direction->name,
                $direction->value,
                $direction->name,
            ));
        };
        $button = GtkButton::new_with_label('flip the direction');
        $button->connect('clicked', function () use (&$step, $apply): void {
            $step++;
            $apply();
        });

        $apply();
        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($caption);
        $page->append($view);
        $page->append($button);
        return $page;
    },
    460,
    380,
);
