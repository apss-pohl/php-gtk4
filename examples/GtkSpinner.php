<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GObject;
use Gtk4\GParamSpec;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkSpinner;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkSpinner - the "busy" animation.
 *
 * Five methods: start(), stop(), get/set_spinning() and the constructor. It
 * animates only while spinning and is invisible otherwise - which is the point,
 * you park one in a toolbar and switch it on around slow work. The page has a
 * large one toggled by a button and a timer, with `notify::spinning` reporting
 * each change in the label.
 *
 *   bin/php-gtk4 examples/demo.php GtkSpinner
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkSpinner',
    'start() and stop() - the busy indicator, invisible when idle',
    function (GtkWindow $win): GtkWidget {
        $spinner = new GtkSpinner();
        $spinner->set_size_request(64, 64);
        $spinner->set_halign(GtkAlign::Center);
        $spinner->set_valign(GtkAlign::Center);
        $spinner->set_vexpand(true);

        $status = Demo::label();
        $button = GtkButton::new_with_label('stop');
        $button->set_halign(GtkAlign::Center);

        $changes = 0;
        // `spinning` is a plain GObject property, so notify:: tracks it.
        $spinner->connect('notify::spinning', function (
            GObject $obj,
            GParamSpec $spec,
        ) use (
            $status,
            $button,
            &$changes
        ): void {
            $on = $obj instanceof GtkSpinner && $obj->get_spinning();
            $changes++;
            $button->set_label($on ? 'stop' : 'start');
            $status->set_markup(sprintf(
                "<tt>spinning  %s\nnotify::%s seen %d times</tt>",
                $on ? 'true ' : 'false',
                $spec->get_name(),
                $changes,
            ));
            Demo::status($on ? 'spinning' : 'idle');
        });

        // Click toggles it; start()/stop() are set_spinning(true/false).
        $button->connect('clicked', function () use ($spinner): void {
            $spinner->get_spinning() ? $spinner->stop() : $spinner->start();
        });

        // ... and a timer does the same every few seconds, like work finishing.
        GLib::timeout_add(3000, function () use ($spinner): bool {
            $spinner->set_spinning(!$spinner->get_spinning());
            return true;
        });

        $spinner->start();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($spinner);
        $page->append($button);
        $page->append($status);
        return $page;
    },
    400,
    300,
);
