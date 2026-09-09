<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkDragAction;
use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEntry;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkDragAction - what a drop is allowed to do: copy, move, link or ask.
 *
 * A GFlags type bound as a final class of int constants, combined with `|` and
 * tested with `&`. It reaches PHP through the drag-and-drop family - here
 * GtkEntry::get_current_icon_drag_source() - and the page cycles every
 * combination of the bits to show how such a value is read.
 *
 *   bin/php-gtk4 examples/demo.php GdkDragAction
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkDragAction',
    'what a drop is allowed to do - a GFlags bitfield',
    function (GtkWindow $win): GtkWidget {
        $constants = array_filter(new \ReflectionClass(GdkDragAction::class)->getConstants(), 'is_int');
        $decode = static fn(int $flags): string => implode(' | ', array_keys(
            array_filter($constants, static fn(int $bit): bool => ($flags & $bit) !== 0),
        )) ?: 'none';

        $entry = new GtkEntry();
        $entry->set_text('drag targets report a GdkDragAction');
        $entry->set_halign(GtkAlign::Fill);

        $rows = array_map(
            static fn(string $k, int $v): string => sprintf('%-5s = %d', $k, $v),
            array_keys($constants),
            $constants,
        );
        $head = sprintf("<tt>%s</tt>\n\n", implode("\n", $rows));

        // Every combination of the four bits, one per second.
        $face = Demo::label();
        $step = 0;
        $show = function (int $flags) use ($face, $head, $decode, $constants): void {
            $bits = implode(' · ', array_map(
                static fn(string $k, int $v): string => sprintf('%s %s', $k, ($flags & $v) !== 0 ? 'set' : 'clear'),
                array_keys($constants),
                $constants,
            ));
            $face->set_markup($head . sprintf(
                "actions <b>%2d</b> = <b>%s</b>\n<small>%s</small>",
                $flags,
                $decode($flags),
                $bits,
            ));
        };
        $show(0);
        GLib::timeout_add(1000, function () use ($show, &$step): bool {
            $show(++$step % 16);
            return true;
        });

        // The real thing: no icon drag is in progress, so the entry reports -1 (no icon).
        Demo::status(sprintf(
            'GtkEntry::get_current_icon_drag_source() = %d; COPY | MOVE = %d',
            $entry->get_current_icon_drag_source(),
            GdkDragAction::COPY | GdkDragAction::MOVE,
        ));

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $face->set_halign(GtkAlign::Start);
        $page->append($face);
        $page->append($entry);
        return $page;
    },
    480,
    340,
);
