<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkTextView;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkTextMark - a position that survives edits.
 *
 * Two marks sit at the same spot; text is inserted exactly between them. The
 * left-gravity mark stays before the insertion, the right-gravity mark is
 * pushed behind it - which is why an end-of-log mark uses right gravity.
 *
 *   bin/php-gtk4 examples/demo.php GtkTextMark
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkTextMark',
    'a position that survives edits',
    function (GtkWindow $win): GtkWidget {
        $view = new GtkTextView();
        $view->set_editable(false);
        $buffer = $view->get_buffer();
        $buffer->set_text('before|after');

        $middle = $buffer->get_iter_at_offset(7);
        $left = $buffer->create_mark('left', $middle, true);
        $right = $buffer->create_mark('right', $middle, false);

        $info = Demo::label();
        $refresh = function () use ($buffer, $left, $right, $info): void {
            $info->set_markup(sprintf(
                "left-gravity mark at <tt>%d</tt> · right-gravity mark at <tt>%d</tt>\n"
                . '<small>the insertion happens between them - left stays, right moves</small>',
                $buffer->get_iter_at_mark($left)->get_offset(),
                $buffer->get_iter_at_mark($right)->get_offset(),
            ));
        };

        $insert = GtkButton::new_with_label('insert at the marks');
        $insert->connect('clicked', function () use ($buffer, $right, $refresh): void {
            $buffer->insert($buffer->get_iter_at_mark($right), '·new·');
            $refresh();
        });

        $reset = GtkButton::new_with_label('reset');
        $reset->connect('clicked', function () use ($buffer, $left, $right, $refresh): void {
            $buffer->set_text('before|after');
            $middle = $buffer->get_iter_at_offset(7);
            $buffer->move_mark($left, $middle);
            $buffer->move_mark($right, $middle);
            $refresh();
        });

        $row = new GtkBox(GtkOrientation::Horizontal, 6);
        $row->append($insert);
        $row->append($reset);

        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($info);
        $page->append($view);
        $page->append($row);
        $refresh();
        return $page;
    },
    520,
    320,
);
