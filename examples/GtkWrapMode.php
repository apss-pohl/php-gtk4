<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkTextView;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;
use Gtk4\GtkWrapMode;

/*
 * Gtk4\GtkWrapMode - how a GtkTextView breaks long lines.
 *
 * None lets the line run off (and the view scroll), Char breaks anywhere,
 * Word only between words, WordChar between words but inside one when it is
 * longer than the line. The page cycles the mode on a narrow view.
 *
 *   bin/php-gtk4 examples/demo.php GtkWrapMode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkWrapMode',
    'how a GtkTextView breaks long lines',
    function (GtkWindow $win): GtkWidget {
        $view = new GtkTextView();
        $view->set_editable(false);
        $view->set_cursor_visible(false);
        $view->set_size_request(220, -1);
        $view->set_halign(GtkAlign::Center);
        $view->get_buffer()->set_text(
            'A sentence with an extraordinarily-hyphenated-overlong-compound in it, '
            . 'so every wrap mode breaks it differently.',
        );

        $status = Demo::label();
        $cases = GtkWrapMode::cases();
        $step = 0;
        $show = function () use ($view, $status, $cases, &$step): void {
            $mode = $cases[$step % count($cases)];
            $view->set_wrap_mode($mode);
            $status->set_markup(sprintf(
                "wrap_mode <b>%s</b> <small>(%d)</small>\n<small>%s</small>",
                $mode->name,
                $mode->value,
                implode(' · ', array_map(static fn(GtkWrapMode $m): string => $m->name, $cases)),
            ));
        };

        $show();
        GLib::timeout_add(1600, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($status);
        $page->append($view);
        return $page;
    },
    480,
    360,
);
