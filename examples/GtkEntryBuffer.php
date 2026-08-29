<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkEntry;
use Gtk4\GtkEntryBuffer;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkEntryBuffer - the text model behind a GtkEntry.
 *
 * One buffer, two entries: type into either and the other follows, because both
 * display the same GtkEntryBuffer. The buffer emits `inserted-text` and
 * `deleted-text` with position and length; the log below records them. The
 * buttons edit the buffer directly - insert_text(), delete_text(),
 * set_max_length() - and the entries update on their own.
 *
 *   bin/php-gtk4 examples/demo.php GtkEntryBuffer
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkEntryBuffer',
    'the text model behind an entry - shared by two entries, editable from the outside',
    function (GtkWindow $win): GtkWidget {
        // -1 = the initial text is NUL-terminated, take it all.
        $buffer = new GtkEntryBuffer('shared buffer', -1);
        $one = GtkEntry::new_with_buffer($buffer);
        $two = new GtkEntry();
        $two->set_buffer($buffer);

        $log = [];
        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $describe = function () use ($buffer, $readout, &$log): void {
            $readout->set_markup(sprintf(
                "<tt>get_text()      \"%s\"\nget_length()    %d chars, get_bytes() %d\nget_max_length() %d\n\n%s</tt>",
                htmlspecialchars($buffer->get_text()),
                $buffer->get_length(),
                $buffer->get_bytes(),
                $buffer->get_max_length(),
                htmlspecialchars(implode("\n", array_slice($log, -5))),
            ));
        };

        // The two signals every edit goes through, whoever made it.
        $buffer->connect(
            'inserted-text',
            function (GtkEntryBuffer $self, int $position, string $chars, int $n_chars) use (&$log, $describe): void {
                $log[] = sprintf('inserted-text  at %-2d "%s" (%d)', $position, $chars, $n_chars);
                $describe();
            },
        );
        $buffer->connect(
            'deleted-text',
            function (GtkEntryBuffer $self, int $position, int $n_chars) use (&$log, $describe): void {
                $log[] = sprintf('deleted-text   at %-2d %d chars', $position, $n_chars);
                $describe();
            },
        );

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $buttons->set_halign(GtkAlign::Center);
        $actions = [
            'insert_text(0, "» ")' => static fn() => $buffer->insert_text(0, '» ', -1),
            'delete_text(0, 2)' => static fn() => $buffer->delete_text(0, 2),
            'set_text("ünïcödé")' => static fn() => $buffer->set_text('ünïcödé', -1),
            'max_length 10 / 0' => static fn() => $buffer->set_max_length($buffer->get_max_length() === 0 ? 10 : 0),
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
        Demo::status('two GtkEntry, one GtkEntryBuffer: ' . ($one->get_buffer() === $two->get_buffer() ? 'same' : '?'));

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($one);
        $page->append($two);
        $page->append($buttons);
        $page->append($readout);
        return $page;
    },
    600,
    400,
);
