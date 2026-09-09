<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GObject;
use Gtk4\GtkButton;
use Gtk4\GtkCustomSorter;
use Gtk4\GtkSorterOrder;
use Gtk4\GtkSortListModel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSorterOrder - what a sorter says about itself: partial, total, or no order at all.
 *
 * get_order() lets a model skip sorting when the sorter imposes none. A custom
 * sorter with a callback answers Partial; without one it answers None and the list
 * falls back to the store order.
 *
 *   bin/php-gtk4 examples/demo.php GtkSorterOrder
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSorterOrder',
    'a sorter\'s own estimate of the order it imposes',
    function (GtkWindow $win): GtkWidget {
        $store = Demo::store();
        $sorter = new GtkCustomSorter(static fn(GObject $a, GObject $b): int => Demo::born($b) <=> Demo::born($a));
        $model = new GtkSortListModel($store, $sorter);
        $order = static fn(GtkSorterOrder $o): string => sprintf('GtkSorterOrder::%s (%d)', $o->name, $o->value);
        $canvas = Demo::bars($model, function () use ($sorter, $order): string {
            return sprintf("get_order() = %s\nclick: drop the callback", $order($sorter->get_order()));
        });
        $button = new GtkButton();
        $button->set_child($canvas);
        $button->connect('clicked', function () use ($sorter, $canvas): void {
            $sorter->set_sort_func(null);
            $canvas->queue_draw();
        });
        Demo::status($order($sorter->get_order()));
        return $button;
    },
    480,
    340,
);
