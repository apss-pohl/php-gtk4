<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkButton;
use Gtk4\GtkCustomSorter;
use Gtk4\GtkSortListModel;
use Gtk4\GtkSorterChange;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSorterChange - how a sorter's order changed, as a hint.
 *
 * Inverted is the cheap one: same comparator, exactly reversed, so the model can
 * flip the list instead of sorting it again. Each click changes the comparator's
 * behaviour and reports it with the case that describes what happened.
 *
 *   bin/php-gtk4 examples/GtkSorterChange.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GtkSorterChange', function (GtkWindow $win): GtkWidget {
    $store = Demo::store();
    $mode = 'born-desc';

    $sorter = new GtkCustomSorter(function ($a, $b) use (&$mode): int {
        return match ($mode) {
            'born-asc' => Demo::born($a) <=> Demo::born($b),
            'name' => strcmp(Demo::name($a), Demo::name($b)),
            default => Demo::born($b) <=> Demo::born($a),
        };
    });
    $sorted = new GtkSortListModel($store, $sorter);

    $note = 'newest first';
    $canvas = Demo::bars($sorted, function () use (&$note, &$mode): string {
        return sprintf("comparator: %s\n%s", $mode, $note);
    });

    /** @var list<array{string, GtkSorterChange, string}> $steps */
    $steps = [
        ['born-asc', GtkSorterChange::Inverted, 'the same order, reversed - the model can just flip it'],
        ['name', GtkSorterChange::Different, 'a different order entirely - re-sort from scratch'],
        ['born-desc', GtkSorterChange::Different, 'back to birth year, newest first'],
    ];

    $button = new GtkButton();
    $button->set_child($canvas);

    $step = 0;
    $button->connect('clicked', function () use ($sorter, $steps, &$step, &$mode, &$note, $canvas): void {
        [$next, $change, $explanation] = $steps[$step++ % count($steps)];
        $mode = $next;
        $sorter->changed($change);
        $note = sprintf('changed(GtkSorterChange::%s) — %s', $change->name, $explanation);
        $canvas->queue_draw();
    });

    $win->set_title('php-gtk4 · GtkSorterChange — ' . implode(', ', array_map(
        static fn(GtkSorterChange $c): string => $c->name,
        GtkSorterChange::cases(),
    )));
    return $button;
}, 500, 340);
