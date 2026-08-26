<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkWindow - a toplevel: title, default size, and close-request.
 *
 * close-request returns bool and true vetoes the close, so the first attempt to
 * close this window is refused and the label says so. Click to toggle the veto,
 * then try again.
 *
 *   bin/php-gtk4 examples/GtkWindow.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GtkWindow', function (GtkWindow $win): GtkWidget {
    $label = Demo::label();
    $button = new GtkButton();
    $button->set_child($label);

    $veto = true;
    $refused = 0;
    $refresh = function () use ($win, $label, &$veto, &$refused): void {
        [$width, $height] = $win->get_default_size();
        $child = $win->get_child();
        $label->set_markup(sprintf(
            "<b>%s</b>\ndefault size <tt>%d x %d</tt> · resizable <tt>%s</tt>\nchild: <tt>%s</tt>\n\n"
            . "close-request veto: <b><span foreground=\"%s\">%s</span></b>\n"
            . "refused so far: <b>%d</b>\n\n<i>click to toggle, then try to close the window</i>",
            htmlspecialchars((string) $win->get_title()),
            $width,
            $height,
            $win->resizable ? 'true' : 'false',
            $child instanceof GtkWidget ? $child::class : 'none',
            $veto ? Demo::WARN : Demo::GOOD,
            $veto ? 'on - closing is refused' : 'off - closing works',
            $refused,
        ));
    };

    // Returning true from close-request cancels the close.
    $win->connect('close-request', function (GtkWindow $window) use (&$veto, &$refused, $refresh): bool {
        if ($veto) {
            $refused++;
            $refresh();
            return true;
        }
        return false;
    });

    $button->connect('clicked', function () use (&$veto, $refresh): void {
        $veto = !$veto;
        $refresh();
    });

    $win->set_default_size(520, 320);
    $refresh();
    return $button;
});
