<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkButton;
use Gtk4\GtkCustomSorter;
use Gtk4\GtkSortListModel;
use Gtk4\GtkSorter;
use Gtk4\GtkSorterChange;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSorter - the abstract base: one method, changed().
 *
 * Same story as GtkFilter. The comparator below reads a direction flag by
 * reference; flipping it changes nothing until changed() tells the sort model to
 * re-order, and the hint it is given says how much work that will be.
 *
 *   bin/php-gtk4 examples/GtkSorter.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GtkSorter', function (GtkWindow $win): GtkWidget {
    $store = Demo::store();
    $descending = true;

    $sorter = new GtkCustomSorter(function ($a, $b) use (&$descending): int {
        return $descending
            ? Demo::born($b) <=> Demo::born($a)
            : Demo::born($a) <=> Demo::born($b);
    });
    $sorted = new GtkSortListModel($store, $sorter);

    $note = 'newest first';
    $canvas = Demo::bars($sorted, function () use (&$note, &$descending): string {
        return sprintf("%s\n%s", $descending ? 'descending' : 'ascending', $note);
    });

    $button = new GtkButton();
    $button->set_child($canvas);

    $step = 0;
    $button->connect('clicked', function () use ($sorter, &$descending, &$step, &$note, $canvas): void {
        if ($step++ % 2 === 0) {
            $descending = !$descending;
            $note = 'direction flipped — nobody was told, so the order is stale';
        } else {
            // Inverted is the cheap hint: same comparator, exactly reversed.
            $sorter->changed(GtkSorterChange::Inverted);
            $note = 'changed(GtkSorterChange::Inverted) — re-ordered';
        }
        $canvas->queue_draw();
    });

    $win->set_title(sprintf('php-gtk4 · GtkSorter — abstract base of %s', $sorter::class));
    return $button;
}, 480, 320);
