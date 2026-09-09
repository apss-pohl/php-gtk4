<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkCheckButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkScrolledWindow;
use Gtk4\GtkTextView;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\GtkWrapMode;

/*
 * Gtk4\GtkTextView - a scrolling console over a GtkTextBuffer.
 *
 * The classic log-console shape: a read-only, word-wrapping view in a scrolled
 * window, a right-gravity mark pinned to the end of the buffer, and every new
 * line inserted at that mark and scrolled into view with scroll_to_mark().
 *
 *   bin/php-gtk4 examples/demo.php GtkTextView
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkTextView',
    'a scrolling console over a GtkTextBuffer',
    function (GtkWindow $win): GtkWidget {
        $view = new GtkTextView();
        $view->set_wrap_mode(GtkWrapMode::Word);
        $view->set_editable(false);
        $view->set_cursor_visible(false);
        $view->set_monospace(true);
        $view->set_left_margin(6);
        $view->set_top_margin(6);

        $buffer = $view->get_buffer();
        $buffer->set_text("console ready\n");
        $tail = $buffer->create_mark('tail', $buffer->get_end_iter(), false);

        $scrolled = new GtkScrolledWindow();
        $scrolled->set_vexpand(true);
        $scrolled->set_has_frame(true);
        $scrolled->set_child($view);

        $n = 0;
        $log = function (string $line) use ($buffer, $view, $tail, &$n): void {
            $n++;
            $buffer->insert($buffer->get_end_iter(), sprintf("[%s] #%02d %s\n", date('H:i:s'), $n, $line));
            $view->scroll_to_mark($tail, 0.0, true, 0.0, 1.0);
            Demo::status("$n lines");
        };

        $once = GtkButton::new_with_label('log a line');
        $once->connect('clicked', fn() => $log('clicked'));

        $burst = GtkButton::new_with_label('log a burst');
        $burst->connect('clicked', function () use ($log): void {
            $left = 5;
            GLib::timeout_add(150, function () use ($log, &$left): bool {
                $log('burst');
                return --$left > 0;
            });
        });

        $editable = GtkCheckButton::new_with_label('editable');
        $editable->connect('toggled', function (GtkCheckButton $c) use ($view): void {
            $view->set_editable($c->get_active());
            $view->set_cursor_visible($c->get_active());
        });

        $row = new GtkBox(GtkOrientation::Horizontal, 6);
        $row->append($once);
        $row->append($burst);
        $row->append($editable);

        $page = new GtkBox(GtkOrientation::Vertical, 8);
        $page->append($row);
        $page->append($scrolled);
        return $page;
    },
    520,
    380,
);
