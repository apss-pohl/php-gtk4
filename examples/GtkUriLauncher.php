<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GAsyncResult;
use Gtk4\GError;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkEntry;
use Gtk4\GtkOrientation;
use Gtk4\GtkUriLauncher;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkUriLauncher - hand a URI to whatever the desktop opens it with.
 *
 * GTK 4.10's replacement for gtk_show_uri(): asynchronous like the rest of the new API, and the
 * answer arrives in the callback - launch_finish() returns true, or throws the GError saying why
 * nothing opened. The parent window is what a portal dialog would be modal to.
 *
 * The button below really does open the URI in the entry, in the browser this machine uses.
 * Nothing happens until it is clicked.
 *
 *   bin/php-gtk4 examples/demo.php GtkUriLauncher
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkUriLauncher',
    'hand a URI to whatever the desktop opens it with',
    function (GtkWindow $win): GtkWidget {
        $column = new GtkBox(GtkOrientation::Vertical, 12);
        $column->append(Demo::label(
            "<span size=\"x-large\"><b>open a URI</b></span>\n"
            . '<small>the button really does open it, in this machine\'s browser</small>',
        ));

        $entry = new GtkEntry();
        $entry->set_text('https://gtk.org/');
        $column->append($entry);

        $button = new GtkButton();
        $button->set_child(Demo::label('Open'));
        $button->connect('clicked', static function () use ($entry, $win): void {
            $launcher = new GtkUriLauncher($entry->get_text());
            Demo::status('launching ' . $entry->get_text() . ' ...');
            // $win is the parent a portal dialog would be modal to; the answer is asynchronous.
            $launcher->launch($win, null, static function (
                GtkUriLauncher $source,
                GAsyncResult $result,
            ): void {
                try {
                    Demo::status($source->launch_finish($result)
                        ? 'opened ' . ($source->get_uri() ?? '')
                        : 'the launch reported no success');
                } catch (GError $e) {
                    Demo::status('could not open it: ' . $e->getMessage());
                }
            });
        });
        $column->append($button);

        Demo::status('type a URI and click Open');
        return $column;
    },
    460,
    240,
);
