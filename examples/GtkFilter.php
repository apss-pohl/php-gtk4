<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkButton;
use Gtk4\GtkCustomFilter;
use Gtk4\GtkFilter;
use Gtk4\GtkFilterListModel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkFilter - the abstract base: one method, changed().
 *
 * A filter's decisions can depend on state the filter list model cannot see. The
 * threshold below is raised on every other click *without* telling anyone, so the
 * list does not move; the next click calls changed() and the same predicate is
 * suddenly re-evaluated. That is the whole point of the base class.
 *
 *   bin/php-gtk4 examples/GtkFilter.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GtkFilter', function (GtkWindow $win): GtkWidget {
    $store = Demo::store();
    $threshold = 1800;

    // The predicate closes over $threshold by reference - changing it is invisible
    // to the model until changed() says otherwise.
    $filter = new GtkCustomFilter(function ($item) use (&$threshold): bool {
        return Demo::born($item) >= $threshold;
    });
    $filtered = new GtkFilterListModel($store, $filter);

    $note = 'showing everyone born >= 1800';
    $canvas = Demo::bars($filtered, function () use (&$note, &$threshold): string {
        return sprintf("born >= %d\n%s", $threshold, $note);
    });

    $button = new GtkButton();
    $button->set_child($canvas);

    $step = 0;
    $button->connect('clicked', function () use ($filter, &$threshold, &$step, &$note, $canvas): void {
        if ($step++ % 2 === 0) {
            $threshold += 40;
            $note = sprintf('threshold raised to %d — nothing told the model, so the list is stale', $threshold);
        } else {
            $filter->changed();                 // GtkFilter::changed() re-evaluates
            $note = 'changed() called — the same predicate, re-run';
        }
        $canvas->queue_draw();
    });

    // GtkFilter is abstract: only subclasses like GtkCustomFilter are instantiable.
    $win->set_title(sprintf(
        'php-gtk4 · GtkFilter — %s is a %s',
        $filter::class,
        get_parent_class($filter) === false ? '?' : (string) get_parent_class($filter),
    ));

    return $button;
}, 480, 320);
