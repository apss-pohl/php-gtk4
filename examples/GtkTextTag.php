<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkTextTag;
use Gtk4\GtkTextView;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\GtkWrapMode;

/*
 * Gtk4\GtkTextTag - formatting applied to ranges of a buffer.
 *
 * Tags are property bags (weight, foreground, scale, …) registered in the
 * buffer's tag table. Select some text and click a button: the tag is applied
 * to the selection with apply_tag(); "plain" removes all of them.
 *
 *   bin/php-gtk4 examples/demo.php GtkTextTag
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkTextTag',
    'formatting applied to ranges of a buffer',
    function (GtkWindow $win): GtkWidget {
        $view = new GtkTextView();
        $view->set_wrap_mode(GtkWrapMode::Word);
        $buffer = $view->get_buffer();
        $buffer->set_text("Select a few words here,\nthen press a button below.");
        $table = $buffer->get_tag_table();

        $bold = new GtkTextTag('bold');
        $bold->weight = 700;
        $red = new GtkTextTag('red');
        $red->foreground = Demo::WARN;
        $big = new GtkTextTag('big');
        $big->scale = 1.6;
        foreach ([$bold, $red, $big] as $tag) {
            $table->add($tag);
        }

        $range = function () use ($buffer): array {
            // The selection if there is one, the whole buffer otherwise.
            return $buffer->get_selection_bounds() ?? $buffer->get_bounds();
        };

        $row = new GtkBox(GtkOrientation::Horizontal, 6);
        foreach ([$bold, $red, $big] as $tag) {
            $button = GtkButton::new_with_label((string) $tag->name);
            $button->connect('clicked', function () use ($buffer, $tag, $range): void {
                [$start, $end] = $range();
                $buffer->apply_tag($tag, $start, $end);
                Demo::status("applied '{$tag->name}' (priority {$tag->get_priority()})");
            });
            $row->append($button);
        }
        $plain = GtkButton::new_with_label('plain');
        $plain->connect('clicked', function () use ($buffer, $range): void {
            [$start, $end] = $range();
            $buffer->remove_all_tags($start, $end);
            Demo::status('all tags removed');
        });
        $row->append($plain);

        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($view);
        $page->append($row);
        return $page;
    },
    520,
    340,
);
