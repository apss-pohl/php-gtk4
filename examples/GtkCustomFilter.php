<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkButton;
use Gtk4\GtkCustomFilter;
use Gtk4\GtkFilterListModel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkCustomFilter - a GtkFilter whose predicate is a PHP callable.
 *
 * `function (GObject $item): bool`, called by GTK's C code for every item.
 * Clicking swaps the predicate with set_filter_func(), which notifies the model
 * by itself - unlike changing state behind an unchanged closure (see GtkFilter).
 *
 *   bin/php-gtk4 examples/GtkCustomFilter.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GtkCustomFilter', function (GtkWindow $win): GtkWidget {
    $store = Demo::store();
    $filter = new GtkCustomFilter();          // null predicate = everything matches
    $filtered = new GtkFilterListModel($store, $filter);

    $note = 'new GtkCustomFilter() - no predicate, everything passes';
    $canvas = Demo::bars($filtered, function () use (&$note): string {
        return $note;
    });

    /** @var list<array{string, ?callable(mixed): bool}> $predicates */
    $predicates = [
        ['born in the 20th century', static fn($item): bool => Demo::born($item) >= 1900],
        ['name contains "a" twice', static fn($item): bool => substr_count(strtolower(Demo::name($item)), 'a') >= 2],
        ['born before 1915', static fn($item): bool => Demo::born($item) < 1915],
        ['null - everything matches again', null],
    ];

    $button = new GtkButton();
    $button->set_child($canvas);

    $step = 0;
    $button->connect('clicked', function () use ($filter, $predicates, &$step, &$note, $canvas): void {
        [$description, $predicate] = $predicates[$step++ % count($predicates)];
        $filter->set_filter_func($predicate);   // replaces the callback and notifies
        $note = $description;
        $canvas->queue_draw();
    });

    return $button;
}, 480, 320);
