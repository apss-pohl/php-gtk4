<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GListModel;
use Gtk4\GObject;
use Gtk4\GtkButton;
use Gtk4\GtkCustomFilter;
use Gtk4\GtkFilter;
use Gtk4\GtkFilterListModel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkFilterListModel - a model showing the items of another that pass a filter.
 *
 * It is itself a GListModel, so it can be stacked (see GtkSortListModel). Clicking
 * cycles what it is pointed at: a filter, no filter, a different source model, and
 * no model at all - the source count and the filtered count are drawn together.
 *
 *   bin/php-gtk4 examples/demo.php GtkFilterListModel
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkFilterListModel',
    'a model showing the items of another that pass a filter',
    function (GtkWindow $win): GtkWidget {
        $everyone = Demo::store();
        $nobody = Demo::store();
        $nobody->remove_all();

        $modern = new GtkCustomFilter(static fn(GObject $item): bool => Demo::born($item) >= 1900);
        $filtered = new GtkFilterListModel($everyone, $modern);

        $note = 'model=5 people · filter=born >= 1900';
        $canvas = Demo::bars($filtered, function () use (&$note, $filtered): string {
            $source = $filtered->get_model();
            return sprintf(
                "%s\nsource %d item(s) · filter %s",
                $note,
                $source instanceof GListModel ? $source->get_n_items() : 0,
                $filtered->get_filter() instanceof GtkFilter ? 'set' : 'null',
            );
        });

        /** @var list<array{string, callable(): void}> $steps */
        $steps = [
            ['set_filter(null) - every source item shows', static fn() => $filtered->set_filter(null)],
            ['set_filter($modern) - born >= 1900 again', static fn() => $filtered->set_filter($modern)],
            ['set_model(empty store) - nothing to filter', static fn() => $filtered->set_model($nobody)],
            ['set_model($everyone) - back to five', static fn() => $filtered->set_model($everyone)],
            ['set_model(null) - no source at all', static fn() => $filtered->set_model(null)],
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
