<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GObject;
use Gtk4\GtkButton;
use Gtk4\GtkCustomFilter;
use Gtk4\GtkFilterChange;
use Gtk4\GtkFilterListModel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkFilterChange - how a filter's decisions changed, as a hint.
 *
 * The hint you pass to GtkFilter::changed() lets the model skip work: MoreStrict
 * means it only has to re-check items that are currently visible, LessStrict only
 * the hidden ones, Different means everything. Each click widens or narrows the
 * predicate and announces it with the matching case.
 *
 *   bin/php-gtk4 examples/demo.php GtkFilterChange
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkFilterChange',
    'how a filter\'s decisions changed, as a hint',
    function (GtkWindow $win): GtkWidget {
        $store = Demo::store();
        $minimum = 1900;
        $filter = new GtkCustomFilter(function (GObject $item) use (&$minimum): bool {
            return Demo::born($item) >= $minimum;
        });
        $filtered = new GtkFilterListModel($store, $filter);

        $note = 'born >= 1900';
        $canvas = Demo::bars($filtered, function () use (&$note, &$minimum): string {
            return sprintf("born >= %d\n%s", $minimum, $note);
        });

        /** @var list<array{int, GtkFilterChange, string}> $steps */
        $steps = [
            [1800, GtkFilterChange::LessStrict, 'the predicate got looser: only hidden items can appear'],
            [1930, GtkFilterChange::MoreStrict, 'the predicate got tighter: only visible items can vanish'],
            [1910, GtkFilterChange::Different, 'no relation to before: everything is re-checked'],
        ];

        $button = new GtkButton();
        $button->set_child($canvas);

        $step = 0;
        $button->connect('clicked', function () use ($filter, $steps, &$step, &$minimum, &$note, $canvas): void {
            [$value, $change, $explanation] = $steps[$step++ % count($steps)];
            $minimum = $value;
            $filter->changed($change);          // the hint, not the work
            $note = sprintf('changed(GtkFilterChange::%s) — %s', $change->name, $explanation);
            $canvas->queue_draw();
        });

        return $button;
    },
    500,
    340,
);
