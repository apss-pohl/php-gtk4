<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GListStore;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\PhpValue;

/*
 * Gtk4\GListStore - a GListModel backed by an array.
 *
 * The whole editing API in order: append, insert, find, remove, remove_all - one
 * per click, with the bars redrawn from the store after each. The store is typed
 * (PhpValue::class here) and appending anything else is a TypeError.
 *
 *   bin/php-gtk4 examples/demo.php GListStore
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GListStore',
    'a GListModel backed by an array',
    function (GtkWindow $win): GtkWidget {
        $store = new GListStore(PhpValue::class);
        $note = 'empty store of ' . $store->get_item_type();
        $marker = new PhpValue(['name' => 'Hedy Lamarr', 'born' => 1914]);

        $canvas = Demo::bars($store, function () use (&$note): string {
            return $note;
        });

        /** @var list<array{string, callable(): string}> $steps */
        $steps = [
            ['append', function () use ($store): string {
                foreach (Demo::people() as $row) {
                    $store->append(new PhpValue($row));
                }
                return sprintf('append() x %d', count(Demo::people()));
            }],
            ['insert', function () use ($store, $marker): string {
                $store->insert(2, $marker);
                return 'insert(2, PhpValue) - the rest shifts down';
            }],
            ['find', function () use ($store, $marker): string {
                return sprintf('find($marker) = %s', var_export($store->find($marker), true));
            }],
            ['remove', function () use ($store, $marker): string {
                $at = $store->find($marker);
                if ($at !== null) {
                    $store->remove($at);
                }
                return sprintf(
                    'remove(%s) - find() now returns %s',
                    var_export($at, true),
                    var_export($store->find($marker), true),
                );
            }],
            ['type', function () use ($store): string {
                try {
                    // The store is typed: only PhpValue instances go in.
                    $store->append(new GListStore());
                    return 'append(GListStore) was accepted?!';
                } catch (\TypeError $e) {
                    return 'append(GListStore) -> TypeError: ' . $e->getMessage();
                }
            }],
            ['remove_all', function () use ($store): string {
                $store->remove_all();
                return sprintf('remove_all() - n_items = %d', $store->n_items);
            }],
        ];

        $button = new GtkButton();
        $button->set_child($canvas);

        $step = 0;
        $button->connect('clicked', function () use ($steps, &$step, &$note, $canvas): void {
            [, $apply] = $steps[$step++ % count($steps)];
            $note = $apply();
            $canvas->queue_draw();
        });

        return $button;
    },
    480,
    320,
);
