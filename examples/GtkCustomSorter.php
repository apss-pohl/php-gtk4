<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GObject;
use Gtk4\GtkButton;
use Gtk4\GtkCustomSorter;
use Gtk4\GtkSortListModel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkCustomSorter - a GtkSorter whose comparator is a PHP callable.
 *
 * `function (GObject $a, GObject $b): int`, exactly the <=> contract, called from
 * GTK's C sort. Clicking swaps the comparator with set_sort_func(), which notifies
 * the sort model by itself.
 *
 *   bin/php-gtk4 examples/demo.php GtkCustomSorter
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkCustomSorter',
    'a GtkSorter whose comparator is a PHP callable',
    function (GtkWindow $win): GtkWidget {
        $store = Demo::store();
        $sorter = new GtkCustomSorter();          // null comparator = original order
        $sorted = new GtkSortListModel($store, $sorter);

        $note = 'new GtkCustomSorter() - no comparator, insertion order';
        $canvas = Demo::bars($sorted, function () use (&$note): string {
            return $note;
        });

        /** @var list<array{string, ?callable(GObject, GObject): int}> $comparators */
        $comparators = [
            ['born ascending', static fn(GObject $a, GObject $b): int => Demo::born($a) <=> Demo::born($b)],
            ['born descending', static fn(GObject $a, GObject $b): int => Demo::born($b) <=> Demo::born($a)],
            ['name A-Z', static fn(GObject $a, GObject $b): int => strcmp(Demo::name($a), Demo::name($b))],
            ['by name length, then name', static fn(GObject $a, GObject $b): int
                => [strlen(Demo::name($a)), Demo::name($a)] <=> [strlen(Demo::name($b)), Demo::name($b)]],
            ['null - back to insertion order', null],
        ];

        $button = new GtkButton();
        $button->set_child($canvas);

        $step = 0;
        $button->connect('clicked', function () use ($sorter, $comparators, &$step, &$note, $canvas): void {
            [$description, $compare] = $comparators[$step++ % count($comparators)];
            $sorter->set_sort_func($compare);
            $note = $description;
            $canvas->queue_draw();
        });

        return $button;
    },
    480,
    320,
);
