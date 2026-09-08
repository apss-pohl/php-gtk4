<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkButton;
use Gtk4\GtkTextChildAnchor;
use Gtk4\GtkTextView;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\GtkWrapMode;

/*
 * Gtk4\GtkTextChildAnchor - a widget in the middle of running text.
 *
 * The anchor marks a place in the buffer; the view is what puts a widget there. Two objects have
 * to agree, which is why the anchor exists rather than the widget going straight into the
 * buffer: the buffer owns the position, the view owns the widget. The button below sits inside
 * the sentence and counts its own clicks - the text reflows around it.
 *
 *   bin/php-gtk4 examples/demo.php GtkTextChildAnchor
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkTextChildAnchor',
    'a widget in the middle of running text',
    function (GtkWindow $win): GtkWidget {
        $view = new GtkTextView();
        $view->set_wrap_mode(GtkWrapMode::Word);
        $view->set_editable(false);
        $view->set_left_margin(12);
        $view->set_right_margin(12);
        $view->set_top_margin(12);
        $view->set_vexpand(true);

        $buffer = $view->get_buffer();
        $before = 'A text view lays out characters, and where an anchor sits it lays out a widget '
            . 'instead: ';
        $after = ' - and the text goes on afterwards, reflowing around it as the window is '
            . 'resized.';
        $buffer->set_text($before . $after, -1);

        // The anchor takes one position in the text; the widget is added to the *view* at it.
        $anchor = $buffer->create_child_anchor($buffer->get_iter_at_offset(mb_strlen($before)));

        $clicks = 0;
        $button = new GtkButton();
        $button->set_child(Demo::label('click me'));
        $button->connect('clicked', function (GtkButton $b) use (&$clicks, $buffer, $anchor): void {
            $clicks++;
            $b->set_child(Demo::label("clicked $clicks"));
            Demo::status(sprintf(
                'the anchor is at offset %d of %d characters',
                $buffer->get_iter_at_child_anchor($anchor)->get_offset(),
                $buffer->get_char_count(),
            ));
        });
        $view->add_child_at_anchor($button, $anchor);

        Demo::status('the button is a real widget inside the buffer\'s text');
        return $view;
    },
    460,
    260,
);
