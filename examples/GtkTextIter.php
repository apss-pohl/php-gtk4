<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkTextIter;
use Gtk4\GtkTextSearchFlags;
use Gtk4\GtkTextView;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\GtkWrapMode;

/*
 * Gtk4\GtkTextIter - a position in a buffer, as a boxed value.
 *
 * Iters come from the buffer (never `new`), are copied by `clone`, and are
 * snapshots: any edit invalidates them (use marks across edits). "next word"
 * walks a word range forward and selects it; "find" selects the next match of
 * a forward_search().
 *
 *   bin/php-gtk4 examples/demo.php GtkTextIter
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkTextIter',
    'a position in a buffer, as a boxed value',
    function (GtkWindow $win): GtkWidget {
        $view = new GtkTextView();
        $view->set_wrap_mode(GtkWrapMode::Word);
        $view->set_editable(false);
        $buffer = $view->get_buffer();
        $buffer->set_text(
            "Iterators walk this text word by word.\n"
            . 'Searching finds every WORD, word or Word regardless of case.',
        );

        $info = Demo::label();
        $show = function (GtkTextIter $iter) use ($info): void {
            $info->set_markup(sprintf(
                "offset <tt>%d</tt> · line <tt>%d</tt>:<tt>%d</tt> · char <tt>%s</tt>\n"
                . '<small>starts_word %s · inside_word %s · ends_line %s · is_end %s</small>',
                $iter->get_offset(),
                $iter->get_line(),
                $iter->get_line_offset(),
                htmlspecialchars($iter->is_end() ? '∎' : mb_chr($iter->get_char())),
                $iter->starts_word() ? 'yes' : 'no',
                $iter->inside_word() ? 'yes' : 'no',
                $iter->ends_line() ? 'yes' : 'no',
                $iter->is_end() ? 'yes' : 'no',
            ));
        };

        $word = GtkButton::new_with_label('next word');
        $word->connect('clicked', function () use ($buffer, $show): void {
            $start = $buffer->get_iter_at_mark($buffer->get_insert());
            if (!$start->forward_word_end() && $start->is_end()) {
                $start = $buffer->get_start_iter();   // wrap around
                $start->forward_word_end();
            }
            $end = clone $start;                       // an independent copy
            $start->backward_word_start();
            $buffer->select_range($end, $start);       // insert mark at the word's end
            $show($start);
        });

        $find = GtkButton::new_with_label("find 'word'");
        $find->connect('clicked', function () use ($buffer, $show): void {
            $from = $buffer->get_iter_at_mark($buffer->get_insert());
            $match = $from->forward_search('word', GtkTextSearchFlags::CASE_INSENSITIVE, null)
                ?? $buffer->get_start_iter()->forward_search('word', GtkTextSearchFlags::CASE_INSENSITIVE, null);
            if ($match === null) {
                Demo::status('no match');
                return;
            }
            [$s, $e] = $match;
            $buffer->select_range($e, $s);
            $show($s);
        });

        $row = new GtkBox(GtkOrientation::Horizontal, 6);
        $row->append($word);
        $row->append($find);

        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($info);
        $page->append($view);
        $page->append($row);
        $show($buffer->get_start_iter());
        return $page;
    },
    560,
    360,
);
