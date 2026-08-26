<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GObject;
use Gtk4\GtkButton;
use Gtk4\GtkCustomSorter;
use Gtk4\GtkSorterChange;
use Gtk4\GtkSortListModel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSorterChange - how a sorter's order changed, as a hint.
 *
 * Inverted is the cheap one: same comparator, exactly reversed, so the model can
 * flip the list instead of sorting it again. Each click changes the comparator's
 * behaviour and reports it with the case that describes what happened.
 *
 *   bin/php-gtk4 examples/demo.php GtkSorterChange
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSorterChange',
    'how a sorter\'s order changed, as a hint',
    function (GtkWindow $win): GtkWidget {
        $store = Demo::store();

        // Shared mutable state, read by the comparator written before the handler.
        $state = new class {
            public string $mode = 'born-desc';
        };

        $sorter = new GtkCustomSorter(function (GObject $a, GObject $b) use ($state): int {
            return match ($state->mode) {
                'born-asc' => Demo::born($a) <=> Demo::born($b),
                'name' => strcmp(Demo::name($a), Demo::name($b)),
                default => Demo::born($b) <=> Demo::born($a),
            };
        });
        $sorted = new GtkSortListModel($store, $sorter);

        $note = 'newest first';
        $canvas = Demo::bars($sorted, function () use (&$note, $state): string {
            return sprintf("comparator: %s\n%s", $state->mode, $note);
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
        $button->connect('clicked', function () use ($sorter, $steps, &$step, $state, &$note, $canvas): void {
            [$next, $change, $explanation] = $steps[$step++ % count($steps)];
            $state->mode = $next;
            $sorter->changed($change);
            $note = sprintf('changed(GtkSorterChange::%s) — %s', $change->name, $explanation);
            $canvas->queue_draw();
        });

        Demo::status(implode(', ', array_map(
            static fn(GtkSorterChange $case): string => $case->name,
            GtkSorterChange::cases(),
        )));
        return $button;
    },
    500,
    340,
);
