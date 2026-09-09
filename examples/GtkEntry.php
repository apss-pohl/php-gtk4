<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkEntry;
use Gtk4\GtkEntryIconPosition;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkEntry - the single-line text entry.
 *
 * Type into it: the readout underneath follows every `changed` signal with what
 * GtkEditable (the interface GtkEntry implements) and GtkEntry itself can tell
 * you - text, length, cursor, selection. Enter fires `activate`, the clear icon
 * on the right fires `icon-press`, and a timer drives the progress bar that lives
 * inside the entry's own frame. The buttons cover what typing cannot: selecting
 * a region, a maximum length, overwrite mode and turning the visible text off.
 *
 *   bin/php-gtk4 examples/demo.php GtkEntry
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkEntry',
    'the single-line text entry - text, icons, progress and the GtkEditable API',
    function (GtkWindow $win): GtkWidget {
        $entry = new GtkEntry();
        $entry->set_placeholder_text('type here, press Enter');
        $entry->set_text('hello GtkEntry');
        $entry->set_icon_from_icon_name(GtkEntryIconPosition::Primary, 'edit-find-symbolic');
        $entry->set_icon_from_icon_name(GtkEntryIconPosition::Secondary, 'edit-clear-symbolic');
        $entry->set_icon_tooltip_text(GtkEntryIconPosition::Secondary, 'clear the text');
        $entry->set_hexpand(true);

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $describe = function () use ($entry, $readout): void {
            $bounds = $entry->get_selection_bounds();
            $readout->set_markup(sprintf(
                "<tt>get_text()         %s\ntext_length        %d / max_length %d\nget_position()     %d\n"
                . "selection          %s\noverwrite_mode     %s\nvisibility         %s\n"
                . 'progress_fraction  %.2f</tt>',
                htmlspecialchars($entry->get_text()),
                $entry->get_text_length(),
                $entry->get_max_length(),
                $entry->get_position(),
                $bounds === null ? 'none' : sprintf('%d..%d "%s"', $bounds[0], $bounds[1], htmlspecialchars(
                    $entry->get_chars($bounds[0], $bounds[1]),
                )),
                $entry->get_overwrite_mode() ? 'true' : 'false',
                $entry->get_visibility() ? 'true' : 'false',
                $entry->get_progress_fraction(),
            ));
        };

        // `changed` comes from GtkEditable, `notify::text` is the property behind it.
        $entry->connect('changed', $describe);
        $entry->connect('notify::selection-bound', $describe);
        $entry->connect('notify::cursor-position', $describe);
        $entry->connect('activate', function (GtkEntry $self): void {
            Demo::status('activate: "' . $self->get_text() . '"');
        });
        // The icon signal tells you which of the two icons was clicked.
        $entry->connect('icon-press', function (GtkEntry $self, GtkEntryIconPosition $pos): void {
            if ($pos === GtkEntryIconPosition::Secondary) {
                $self->set_text('');
            }
            Demo::status('icon-press: ' . $pos->name . ' (' . ($self->get_icon_name($pos) ?? '?') . ')');
        });

        // A progress bar drawn inside the entry, advanced by a timer.
        GLib::timeout_add(200, function () use ($entry, $describe): bool {
            $next = $entry->get_progress_fraction() + 0.05;
            $entry->set_progress_fraction($next > 1.0 ? 0.0 : $next);
            $describe();
            return true;
        });

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $buttons->set_halign(GtkAlign::Center);
        $actions = [
            'select 0..5' => static function () use ($entry): void {
                $entry->grab_focus_without_selecting();
                $entry->select_region(0, 5);
            },
            'cursor to end' => static fn() => $entry->set_position(-1),
            'max_length 12' => static fn() => $entry->set_max_length($entry->get_max_length() === 0 ? 12 : 0),
            'overwrite' => static fn() => $entry->set_overwrite_mode(!$entry->get_overwrite_mode()),
            'visibility' => static fn() => $entry->set_visibility(!$entry->get_visibility()),
        ];
        foreach ($actions as $label => $action) {
            $button = GtkButton::new_with_label($label);
            $button->connect('clicked', function () use ($action, $describe): void {
                $action();
                $describe();
            });
            $buttons->append($button);
        }

        $describe();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($entry);
        $page->append($buttons);
        $page->append($readout);
        return $page;
    },
    600,
    380,
);
