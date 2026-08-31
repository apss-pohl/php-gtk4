<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkTextTag;
use Gtk4\GtkTextTagTable;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkTextTagTable - the registry of tags a buffer can use.
 *
 * One table can serve many buffers. add() refuses a tag that is already in a
 * table, lookup() finds tags by name, and foreach() visits every tag with a
 * PHP callable - the listing below is rebuilt with it after every change.
 *
 *   bin/php-gtk4 examples/demo.php GtkTextTagTable
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkTextTagTable',
    'the registry of tags a buffer can use',
    function (GtkWindow $win): GtkWidget {
        $table = new GtkTextTagTable();
        $n = 0;

        $listing = Demo::label();
        $refresh = function () use ($table, $listing): void {
            $rows = [];
            $table->foreach(function (GtkTextTag $tag) use (&$rows): void {
                $rows[] = sprintf('%s <small>(priority %d)</small>', $tag->name, $tag->get_priority());
            });
            sort($rows);
            $listing->set_markup(
                "<b>{$table->get_size()} tags</b>\n" . ($rows === [] ? '<i>empty</i>' : implode("\n", $rows)),
            );
        };

        $add = GtkButton::new_with_label('add a tag');
        $add->connect('clicked', function () use ($table, $refresh, &$n): void {
            $table->add(new GtkTextTag('tag-' . ++$n));
            $refresh();
        });

        $remove = GtkButton::new_with_label('remove the last');
        $remove->connect('clicked', function () use ($table, $refresh, &$n): void {
            $tag = $table->lookup('tag-' . $n);
            if ($tag !== null) {
                $table->remove($tag);
                $n--;
            }
            $refresh();
        });

        $row = new GtkBox(GtkOrientation::Horizontal, 6);
        $row->append($add);
        $row->append($remove);
        $row->set_halign(GtkAlign::Center);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($row);
        $page->append($listing);
        $refresh();
        return $page;
    },
);
