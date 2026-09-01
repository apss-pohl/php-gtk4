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
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PhpValue;

/*
 * Gtk4\GtkColumnViewColumn - one column of a GtkColumnView.
 *
 * A column is not a widget: it is a title, a factory for its cells and,
 * optionally, a GtkSorter. Give it one and its header becomes clickable - the
 * view's own sorter then follows that column, and the GtkSortListModel under
 * the view reorders the rows. The button below walks the rest of the API on the
 * "Moons" column: expand, a fixed width, and hiding it altogether.
 *
 *   bin/php-gtk4 examples/demo.php GtkColumnViewColumn
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkColumnViewColumn',
    'one column of a GtkColumnView: title, factory, sorter',
    function (GtkWindow $win): GtkWidget {
        $store = new GListStore(PhpValue::class);
        foreach ([['Mercury', 0], ['Venus', 0], ['Earth', 1], ['Mars', 2], ['Jupiter', 95]] as $row) {
            $store->append(new PhpValue($row));
        }

        /** @return array{string, int} */
        $rowOf = static function (GObject $item): array {
            $value = $item instanceof PhpValue ? $item->get_value() : null;
            if (!is_array($value) || !is_string($value[0] ?? null) || !is_int($value[1] ?? null)) {
                return ['?', 0];
            }
            return [$value[0], $value[1]];
        };

        $cells = function (int $field) use ($rowOf): GtkSignalListItemFactory {
            $factory = new GtkSignalListItemFactory();
            $factory->connect('setup', function (GtkSignalListItemFactory $f, GtkListItem $item): void {
                $item->set_child(new GtkLabel());
            });
            $factory->connect('bind', function (
                GtkSignalListItemFactory $f,
                GtkListItem $item,
            ) use (
                $field,
                $rowOf
            ): void {
                $cell = $item->get_child();
                $object = $item->get_item();
                if ($cell instanceof GtkLabel && $object !== null) {
                    $cell->set_text((string) $rowOf($object)[$field]);
                }
            });
            return $factory;
        };

        $view = new GtkColumnView();
        $planet = new GtkColumnViewColumn('Planet', $cells(0));
        $planet->set_expand(true);
        $planet->set_sorter(new GtkCustomSorter(fn(GObject $a, GObject $b): int
            => strcmp($rowOf($a)[0], $rowOf($b)[0])));
        $view->append_column($planet);

        $moons = new GtkColumnViewColumn('Moons', $cells(1));
        $moons->set_sorter(new GtkCustomSorter(fn(GObject $a, GObject $b): int
            => $rowOf($a)[1] <=> $rowOf($b)[1]));
        $view->append_column($moons);

        $view->set_model(new GtkSingleSelection(new GtkSortListModel($store, $view->get_sorter())));

        $report = Demo::label();
        $describe = function () use ($moons, $report): void {
            $report->set_markup(sprintf(
                "<b>click a header to sort</b>\n<tt>Moons: title=%s expand=%s fixed-width=%d visible=%s</tt>",
                htmlspecialchars((string) $moons->get_title()),
                $moons->get_expand() ? 'true' : 'false',
                $moons->get_fixed_width(),
                $moons->get_visible() ? 'true' : 'false',
            ));
        };

        /** @var list<callable(): void> $steps */
        $steps = [
            function () use ($moons): void {
                $moons->set_title('Moons (n)');
            },
            function () use ($moons): void {
                $moons->set_fixed_width(140);
            },
            function () use ($moons): void {
                $moons->set_visible(false);
            },
            function () use ($moons): void {
                $moons->set_visible(true);
                $moons->set_fixed_width(-1);
                $moons->set_title('Moons');
            },
        ];
        $step = 0;
        $button = GtkButton::new_with_label('next: change the Moons column');
        $button->connect('clicked', function () use ($steps, &$step, $describe): void {
            $steps[$step++ % count($steps)]();
            $describe();
        });

        $describe();
        $view->set_vexpand(true);

        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($report);
        $page->append($view);
        $page->append($button);
        return $page;
    },
    520,
    400,
);
