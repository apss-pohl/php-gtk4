<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GObject;
use Gtk4\GtkCustomSorter;
use Gtk4\GtkOrdering;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkOrdering - the three-way result of comparing two items: Smaller, Equal, Larger.
 *
 * GtkSorter::compare() returns it instead of a bare int. Every pair of the demo
 * people is compared by birth year and the enum case is printed next to the pair.
 *
 *   bin/php-gtk4 examples/demo.php GtkOrdering
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkOrdering',
    'the result of comparing two items',
    function (GtkWindow $win): GtkWidget {
        $sorter = new GtkCustomSorter(static fn(GObject $a, GObject $b): int => Demo::born($a) <=> Demo::born($b));
        $store = Demo::store();
        $rows = [];
        for ($i = 0; $i < $store->get_n_items(); $i++) {
            $a = $store->get_item($i);
            $b = $store->get_item(($i + 1) % $store->get_n_items());
            if ($a === null || $b === null) {
                continue;
            }
            $result = $sorter->compare($a, $b);
            $rows[] = sprintf('%s vs %s: <b>GtkOrdering::%s</b>', Demo::name($a), Demo::name($b), $result->name);
        }
        Demo::status(implode(', ', array_map(
            static fn(GtkOrdering $o): string => "$o->name = $o->value",
            GtkOrdering::cases(),
        )));
        return Demo::label(implode("\n", $rows));
    },
    480,
    340,
);
