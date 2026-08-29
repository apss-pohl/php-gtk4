<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEditable;
use Gtk4\GtkEntry;
use Gtk4\GtkOrientation;
use Gtk4\GtkPasswordEntry;
use Gtk4\GtkSpinButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkEditable - the text-editing interface behind GtkEntry, GtkPasswordEntry
 * and GtkSpinButton.
 *
 * GtkEditable is a real PHP interface: `class_implements()` lists it for every
 * one of the three widgets in this page, and the PHP side of it declares the
 * readers they share - get_text(), get_selection_bounds(), delete_text(). The
 * full editable method set (set_text, select_region, set_position, get_chars,
 * set_alignment, set_editable …) sits on each implementing class. A timer
 * drives every widget through the same steps; the readout shows what each one
 * reports back through the interface.
 *
 *   bin/php-gtk4 examples/demo.php GtkEditable
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkEditable',
    'the text-editing interface every entry, password entry and spin button implement',
    function (GtkWindow $win): GtkWidget {
        $entry = new GtkEntry();
        $password = new GtkPasswordEntry();
        $spin = GtkSpinButton::new_with_range(0, 99999, 1);
        /** @var list<GtkEntry|GtkPasswordEntry|GtkSpinButton> $editables */
        $editables = [$entry, $password, $spin];

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);

        // Every step is written once and runs on all three widgets.
        $steps = [
            'set_text()' => static fn(GtkEntry|GtkPasswordEntry|GtkSpinButton $e) => $e->set_text('12345 editable'),
            'select_region(0, 5)' => static function (GtkEntry|GtkPasswordEntry|GtkSpinButton $e): void {
                $e->grab_focus();
                $e->select_region(0, 5);
            },
            'set_position(3)' => static fn(GtkEntry|GtkPasswordEntry|GtkSpinButton $e) => $e->set_position(3),
            // delete_text() is one of the interface's own methods.
            'delete_text(0, 5)' => static fn(GtkEditable $e) => $e->delete_text(0, 5),
            'set_alignment(1.0)' => static fn(GtkEntry|GtkPasswordEntry|GtkSpinButton $e) => $e->set_alignment(1.0),
            'set_editable(false)' => static fn(GtkEntry|GtkPasswordEntry|GtkSpinButton $e) => $e->set_editable(false),
            'set_editable(true) + reset' => static function (GtkEntry|GtkPasswordEntry|GtkSpinButton $e): void {
                $e->set_editable(true);
                $e->set_alignment(0.0);
                $e->set_text('');
            },
        ];
        $names = array_keys($steps);
        $step = 0;

        $show = function () use ($editables, $readout, $names, &$step): void {
            $rows = [];
            foreach ($editables as $e) {
                $bounds = $e->get_selection_bounds();
                $rows[] = sprintf(
                    '%-17s text "%s"  chars(0,3) "%s"  pos %d  sel %s  editable %s',
                    new \ReflectionClass($e)->getShortName(),
                    htmlspecialchars($e->get_text()),
                    htmlspecialchars($e->get_chars(0, 3)),
                    $e->get_position(),
                    $bounds === null ? '-' : $bounds[0] . '..' . $bounds[1],
                    $e->get_editable() ? 'yes' : 'no',
                );
            }
            $readout->set_markup(sprintf(
                "<b>%s</b>\n<tt>%s</tt>",
                htmlspecialchars($names[$step % count($names)]),
                implode("\n", $rows),
            ));
        };

        GLib::timeout_add(1300, function () use ($editables, $steps, $names, $show, &$step): bool {
            $step++;
            $name = $names[$step % count($names)];
            foreach ($editables as $e) {
                $steps[$name]($e);
            }
            $show();
            return true;
        });

        // The `changed` signal is part of the interface too.
        foreach ($editables as $e) {
            $e->connect('changed', $show);
        }
        $show();
        Demo::status(sprintf(
            '%s implements %s',
            new \ReflectionClass($spin)->getShortName(),
            implode(', ', array_keys(class_implements($spin) ?: [])),
        ));

        $page = new GtkBox(GtkOrientation::Vertical, 8);
        foreach ($editables as $e) {
            $page->append($e);
        }
        $page->append($readout);
        return $page;
    },
    640,
    360,
);
