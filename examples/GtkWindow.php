<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkWindow - a toplevel: title, default size, and close-request.
 *
 * close-request returns bool and true vetoes the close, so the first attempt to
 * close the *demo* window is refused and the label says so. Click to toggle the
 * veto, then try again.
 *
 * The window this page plays with is its own second toplevel, never the one you
 * are reading this in - a page that vetoed the application's own close-request
 * would make the application unclosable.
 *
 *   bin/php-gtk4 examples/demo.php GtkWindow
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkWindow',
    'a toplevel: title, default size, and close-request',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $label = Demo::label();
        $button = new GtkButton();
        $button->set_child($label);

        // Shared mutable state. An object rather than by-ref captures, because the
        // renderer below is written before the handlers that change it.
        $state = new class {
            public bool $veto = true;
            public int $refused = 0;
        };

        // A window of this page's own: adopting $app keeps it alive, and vetoing
        // *its* close-request cannot trap the application.
        $demo = new GtkWindow($app);
        $demo->set_title('GtkWindow demo — try to close me');
        $demo->set_default_size(360, 220);
        $demo->set_child(Demo::label(
            "This is the window the page on the left is describing.\n\n"
            . '<i>Close it and watch close-request refuse.</i>',
        ));

        $refresh = function () use ($demo, $label, $state): void {
            [$width, $height] = $demo->get_default_size();
            $child = $demo->get_child();
            $label->set_markup(sprintf(
                "<b>%s</b>\ndefault size <tt>%d x %d</tt> · resizable <tt>%s</tt>\nchild: <tt>%s</tt>\n\n"
                . "close-request veto: <b><span foreground=\"%s\">%s</span></b>\n"
                . "refused so far: <b>%d</b>\n\n<i>click to toggle, then try to close the demo window</i>",
                htmlspecialchars((string) $demo->get_title()),
                $width,
                $height,
                $demo->resizable ? 'true' : 'false',
                $child instanceof GtkWidget ? $child::class : 'none',
                $state->veto ? Demo::WARN : Demo::GOOD,
                $state->veto ? 'on - closing is refused' : 'off - closing works',
                $state->refused,
            ));
        };

        // Returning true from close-request cancels the close.
        $demo->connect('close-request', function (GtkWindow $window) use ($state, $refresh): bool {
            if ($state->veto) {
                $state->refused++;
                $refresh();
                return true;
            }
            return false;
        });

        $button->connect('clicked', function () use ($state, $refresh): void {
            $state->veto = !$state->veto;
            $refresh();
        });

        $demo->present();
        $refresh();
        return $button;
    },
);
