<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GListModel;
use Gtk4\GtkButton;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkStringList - a GListModel that wraps an array of strings.
 *
 * The editing API in order, one per click: append, take, splice, remove and a
 * clearing splice. Every item is a GtkStringObject (get_item()) and every edit
 * emits `items-changed` with the position and the -removed/+added counts; the
 * label is redrawn from the model after each step.
 *
 *   bin/php-gtk4 examples/demo.php GtkStringList
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkStringList',
    'a GListModel that wraps an array of strings',
    function (GtkWindow $win): GtkWidget {
        $list = new GtkStringList(['red', 'green', 'blue']);
        $note = 'new GtkStringList([red, green, blue])';
        $change = 'no items-changed yet';

        $list->connect('items-changed', function (
            GListModel $model,
            int $position,
            int $removed,
            int $added,
        ) use (&$change): void {
            $change = sprintf('items-changed at %d: -%d +%d', $position, $removed, $added);
        });

        $face = Demo::label();
        $render = function () use ($list, $face, &$note, &$change): void {
            $rows = [];
            for ($i = 0; $i < $list->get_n_items(); $i++) {
                $item = $list->get_item($i);
                $rows[] = sprintf(
                    '%d  %-12s %s',
                    $i,
                    htmlspecialchars((string) $list->get_string($i)),
                    $item instanceof GtkStringObject ? '(GtkStringObject)' : '?',
                );
            }
            $face->set_markup(sprintf(
                "<b>%s</b>\n<small>%s</small>\n\n<tt>%s</tt>\n\n"
                . '<small>%d item(s) of %s - click for the next step</small>',
                htmlspecialchars($note),
                htmlspecialchars($change),
                $rows === [] ? '(empty)' : implode("\n", $rows),
                $list->get_n_items(),
                $list->get_item_type(),
            ));
        };

        /** @var list<callable(): string> $steps */
        $steps = [
            function () use ($list): string {
                $list->append('yellow');
                return 'append(yellow)';
            },
            function () use ($list): string {
                // take() is append() for a string the caller gives away - same thing from PHP.
                $list->take('cyan');
                return 'take(cyan)';
            },
            function () use ($list): string {
                $list->splice(1, 2, ['magenta', 'black', 'white']);
                return 'splice(1, 2, [magenta, black, white]) - two out, three in';
            },
            function () use ($list): string {
                $list->remove(0);
                return 'remove(0)';
            },
            function () use ($list): string {
                $list->splice(0, $list->get_n_items(), null);
                return 'splice(0, n_items, null) - cleared';
            },
            function () use ($list): string {
                $list->splice(0, 0, ['red', 'green', 'blue']);
                return 'splice(0, 0, [red, green, blue]) - back to the start';
            },
        ];

        $button = new GtkButton();
        $button->set_child($face);
        $step = 0;
        $button->connect('clicked', function () use ($steps, &$step, &$note, $render): void {
            $note = $steps[$step++ % count($steps)]();
            $render();
        });

        $render();
        return $button;
    },
    520,
    380,
);
