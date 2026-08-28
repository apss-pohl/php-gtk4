<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GObject;
use Gtk4\GtkButton;
use Gtk4\GtkCustomFilter;
use Gtk4\GtkFilterListModel;
use Gtk4\GtkFilterMatch;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkFilterMatch - what a filter says about itself: it matches some items, all of them, or none.
 *
 * get_strictness() lets a model skip work: All means nothing is filtered, None means
 * everything is. A custom filter with a callback can only answer Some; without a
 * callback it answers All - and the bars show both.
 *
 *   bin/php-gtk4 examples/demo.php GtkFilterMatch
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkFilterMatch',
    'a filter\'s own estimate of what it matches',
    function (GtkWindow $win): GtkWidget {
        $store = Demo::store();
        $filter = new GtkCustomFilter(static fn(GObject $item): bool => Demo::born($item) >= 1910);
        $model = new GtkFilterListModel($store, $filter);
        $strict = static fn(GtkFilterMatch $m): string => sprintf('GtkFilterMatch::%s (%d)', $m->name, $m->value);
        $canvas = Demo::bars($model, function () use ($filter, $strict): string {
            return sprintf("get_strictness() = %s\nclick: drop the callback", $strict($filter->get_strictness()));
        });
        $button = new GtkButton();
        $button->set_child($canvas);
        $button->connect('clicked', function () use ($filter, $canvas): void {
            $filter->set_filter_func(null);                      // no callback: everything matches
            $canvas->queue_draw();
        });
        Demo::status($strict($filter->get_strictness()) . ' - match(item) is the per-item answer');
        return $button;
    },
    480,
    340,
);
