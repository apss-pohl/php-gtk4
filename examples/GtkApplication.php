<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkApplication - owns the main loop and the windows.
 *
 * Click to open more toplevels: the window list below grows, and the application
 * keeps running until the *last* window is closed.
 *
 *   bin/php-gtk4 examples/demo.php GtkApplication
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkApplication',
    'owns the main loop and the windows',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $label = Demo::label();
        $button = new GtkButton();
        $button->set_child($label);

        $refresh = function () use ($app, $label): void {
            $titles = array_map(
                static fn(GtkWindow $window): string => (string) $window->get_title(),
                $app->get_windows(),
            );
            $label->set_markup(sprintf(
                "application id <b>%s</b>\nactive window: <b>%s</b>\n\n"
                . "<b>%d</b> window(s):\n<tt>%s</tt>\n\n<i>click to open another one</i>",
                htmlspecialchars((string) $app->get_application_id()),
                htmlspecialchars((string) $app->get_active_window()?->get_title()),
                count($titles),
                htmlspecialchars(implode("\n", $titles)),
            ));
        };

        $opened = 0;
        $button->connect('clicked', function () use ($app, $refresh, &$opened): void {
            $opened++;
            $extra = new GtkWindow();
            $extra->set_application($app);          // adopting the app keeps its loop alive
            $extra->set_title('extra #' . $opened);
            $extra->set_default_size(260, 140);
            $extra->set_child(Demo::label("closing me is fine -\nthe app quits with the <b>last</b> window"));
            $extra->present();
            $refresh();
        });

        $refresh();
        return $button;
    },
);
