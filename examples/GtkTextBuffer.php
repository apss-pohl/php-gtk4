<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkTextBuffer;
use Gtk4\GtkTextView;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\GtkWrapMode;

/*
 * Gtk4\GtkTextBuffer - the text model: characters, lines, selection, undo.
 *
 * Type into the view: the `changed` signal keeps the counters live. The buttons
 * drive the buffer directly - set_text() replaces everything, insert_at_cursor()
 * types for you, undo()/redo() replay the user actions the buffer recorded.
 *
 *   bin/php-gtk4 examples/demo.php GtkTextBuffer
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkTextBuffer',
    'the text model: characters, lines, selection, undo',
    function (GtkWindow $win): GtkWidget {
        $buffer = new GtkTextBuffer();
        $buffer->set_enable_undo(true);

        $view = new GtkTextView();
        $view->set_buffer($buffer);
        $view->set_wrap_mode(GtkWrapMode::WordChar);
        $view->set_left_margin(6);
        $view->set_top_margin(6);

        $counters = Demo::label();
        $refresh = function () use ($buffer, $counters): void {
            $selection = $buffer->get_selection_bounds();
            $counters->set_markup(sprintf(
                '<tt>%d</tt> chars · <tt>%d</tt> lines · selection <tt>%s</tt>'
                . ' · undo <tt>%s</tt> · redo <tt>%s</tt>',
                $buffer->get_char_count(),
                $buffer->get_line_count(),
                $selection === null
                    ? 'none'
                    : $selection[0]->get_offset() . '–' . $selection[1]->get_offset(),
                $buffer->get_can_undo() ? 'yes' : 'no',
                $buffer->get_can_redo() ? 'yes' : 'no',
            ));
        };
        $buffer->connect('changed', $refresh);
        $buffer->connect('mark-set', $refresh);   // selection moves are mark moves

        $buffer->set_text("Edit me.\nEvery change updates the counters below.");

        $reset = GtkButton::new_with_label('set_text()');
        $reset->connect('clicked', fn() => $buffer->set_text('replaced wholesale'));
        $type = GtkButton::new_with_label('insert_at_cursor()');
        $type->connect('clicked', fn() => $buffer->insert_at_cursor(' …typed… '));
        $undo = GtkButton::new_with_label('undo()');
        $undo->connect('clicked', fn() => $buffer->undo());
        $redo = GtkButton::new_with_label('redo()');
        $redo->connect('clicked', fn() => $buffer->redo());

        $scrolled = new GtkScrolledWindow();
        $scrolled->set_vexpand(true);
        $scrolled->set_has_frame(true);
        $scrolled->set_child($view);

        $row = new GtkBox(GtkOrientation::Horizontal, 6);
        foreach ([$reset, $type, $undo, $redo] as $b) {
            $row->append($b);
        }

        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($counters);
        $page->append($scrolled);
        $page->append($row);
        $refresh();
        return $page;
    },
    560,
    400,
);
