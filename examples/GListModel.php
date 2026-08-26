<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GListModel;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PhpValue;

/*
 * Gtk4\GListModel - the read side of a list: item type, count, items-changed.
 *
 * Three methods and one signal, which is all a list widget ever needs. The bars
 * are drawn straight from the model, and the `items-changed` handler reports the
 * position and the +added/-removed counts of every edit. Click to edit it.
 *
 *   bin/php-gtk4 examples/GListModel.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GListModel', function (GtkWindow $win): GtkWidget {
    $store = Demo::store();
    $lastChange = 'no change yet';

    // The interface is what the drawing code is written against.
    $summarise = static function (GListModel $model): string {
        $names = [];
        for ($i = 0; $i < $model->get_n_items(); $i++) {
            $names[] = Demo::name($model->get_item($i));
        }
        return sprintf('item_type=%s · %s', $model->get_item_type(), implode(' | ', $names));
    };

    $canvas = Demo::bars($store, function () use ($store, $summarise, &$lastChange): string {
        return $summarise($store) . "\n" . $lastChange;
    });

    // Position, how many went away, how many arrived - never a whole-list reload.
    $store->connect('items-changed', function (
        GListModel $model,
        int $position,
        int $removed,
        int $added,
    ) use (&$lastChange, $canvas): void {
        $lastChange = sprintf(
            'items-changed at %d: -%d +%d, now %d (get_item(%d) = %s)',
            $position,
            $removed,
            $added,
            $model->get_n_items(),
            $position,
            Demo::name($model->get_item($position)),
        );
        $canvas->queue_draw();
    });

    $button = new GtkButton();
    $button->set_child($canvas);

    $step = 0;
    $button->connect('clicked', function () use ($store, &$step): void {
        match ($step++ % 3) {
            0 => $store->append(new PhpValue(['name' => 'Hedy Lamarr', 'born' => 1914])),
            1 => $store->remove(0),
            default => $store->insert(0, new PhpValue(['name' => 'Ada Lovelace', 'born' => 1815])),
        };
    });

    return $button;
}, 480, 320);
