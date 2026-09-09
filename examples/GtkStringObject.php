<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkButton;
use Gtk4\GtkStringList;
use Gtk4\GtkStringObject;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkStringObject - one string as a GObject, the item type of GtkStringList.
 *
 * A GListModel holds GObjects, so a list of strings has to box each one: that is
 * all this class is - a read-only `string` property and get_string(). The page
 * builds a few by hand, shows the same string reached both ways, and then walks
 * a GtkStringList's get_item() to show that this is what comes back out of it.
 *
 *   bin/php-gtk4 examples/demo.php GtkStringObject
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkStringObject',
    'one string as a GObject - the item type of GtkStringList',
    function (GtkWindow $win): GtkWidget {
        $words = ['alpha', 'beta', 'gamma', 'delta'];
        $list = new GtkStringList($words);

        $face = Demo::label();
        $step = 0;
        $show = function () use ($words, $list, $face, &$step): void {
            $i = $step % count($words);
            $own = new GtkStringObject($words[$i]);    // built by hand ...
            $item = $list->get_item($i);                // ... and what the list hands out
            $fromList = $item instanceof GtkStringObject ? $item->get_string() : '(not a GtkStringObject)';
            $face->set_markup(sprintf(
                "<tt>new GtkStringObject('%s')\n\n  get_string()  %s\n  ->string      %s\n  class         %s\n\n"
                . "list->get_item(%d)\n  instanceof    %s\n  get_string()  %s</tt>\n\n"
                . '<small>%s - click or wait to move on</small>',
                $own->get_string(),
                $own->get_string(),
                htmlspecialchars((string) $own->string),
                $own::class,
                $i,
                $item instanceof GtkStringObject ? 'GtkStringObject' : 'no',
                htmlspecialchars($fromList),
                implode(' · ', array_map(
                    static fn(int $k): string => $k === $i ? "<b>{$words[$k]}</b>" : $words[$k],
                    array_keys($words),
                )),
            ));
            Demo::status(sprintf('item %d of %d is a %s', $i, $list->get_n_items(), $list->get_item_type()));
        };

        $button = new GtkButton();
        $button->set_child($face);
        $button->connect('clicked', function () use ($show, &$step): void {
            $step++;
            $show();
        });

        GLib::timeout_add(1500, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        $show();
        return $button;
    },
    520,
    360,
);
