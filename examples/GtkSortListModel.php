<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GListModel;
use Gtk4\GObject;
use Gtk4\GtkButton;
use Gtk4\GtkCustomFilter;
use Gtk4\GtkCustomSorter;
use Gtk4\GtkFilterListModel;
use Gtk4\GtkSorter;
use Gtk4\GtkSortListModel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSortListModel - another model's items in sorted order.
 *
 * Both this and GtkFilterListModel are themselves GListModels, so they stack: the
 * chain here is store -> filter -> sort, and clicking rebuilds the middle of it
 * while the sorted view keeps working.
 *
 *   bin/php-gtk4 examples/demo.php GtkSortListModel
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSortListModel',
    'another model\'s items in sorted order',
    function (GtkWindow $win): GtkWidget {
        $store = Demo::store();
        $filter = new GtkCustomFilter(static fn(GObject $item): bool => Demo::born($item) >= 1900);
        $filtered = new GtkFilterListModel($store, $filter);

        $sorter = new GtkCustomSorter(static fn(GObject $a, GObject $b): int => Demo::born($b) <=> Demo::born($a));
        $sorted = new GtkSortListModel($filtered, $sorter);

        $note = 'store -> filter(>= 1900) -> sort(newest first)';
        $canvas = Demo::bars($sorted, function () use (&$note, $sorted, $store): string {
            $source = $sorted->get_model();
            return sprintf(
                "%s\nstore %d -> source %d -> sorted %d · sorter %s",
                $note,
                $store->get_n_items(),
                $source instanceof GListModel ? $source->get_n_items() : 0,
                $sorted->get_n_items(),
                $sorted->get_sorter() instanceof GtkSorter ? 'set' : 'null',
            );
        });

        /** @var list<array{string, callable(): void}> $steps */
        $steps = [
            ['set_model($store) - skip the filter entirely', static fn() => $sorted->set_model($store)],
            ['set_model($filtered) - the full chain again', static fn() => $sorted->set_model($filtered)],
            ['set_sorter(null) - source order, unsorted', static fn() => $sorted->set_sorter(null)],
            ['set_sorter($sorter) - newest first again', static fn() => $sorted->set_sorter($sorter)],
        ];

        $button = new GtkButton();
        $button->set_child($canvas);

        $step = 0;
        $button->connect('clicked', function () use ($steps, &$step, &$note, $canvas): void {
            [$description, $apply] = $steps[$step++ % count($steps)];
            $apply();
            $note = $description;
            $canvas->queue_draw();
        });

        return $button;
    },
    480,
    320,
);
