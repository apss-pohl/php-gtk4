<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GApplication;
use Gtk4\GLib;
use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GApplication - the Gio base of GtkApplication: id, flags, registration, use count.
 *
 * The demo runs inside a GtkApplication, which *is* a GApplication. The page reads
 * the inherited API and lets you hold()/release() the use count that keeps an
 * application alive when it has no windows.
 *
 *   bin/php-gtk4 examples/demo.php GApplication
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GApplication',
    'the Gio application base class',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $holds = 0;
        $label = Demo::label();
        $button = new GtkButton();
        $button->set_child($label);
        $button->set_size_request(360, 120);

        $show = function () use ($app, $label, &$holds): void {
            $label->set_markup(sprintf(
                "%s\napplication id: <b>%s</b>\nflags: %d, registered: %s, inactivity timeout: %d ms\n"
                . 'hold() count from this page: <b>%d</b> (click: hold, click again: release)',
                sprintf('%s extends %s', $app::class, GApplication::class),
                $app->get_application_id() ?? 'none (non-unique)',
                $app->get_flags(),
                $app->get_is_registered() ? 'yes' : 'no',
                $app->get_inactivity_timeout(),
                $holds,
            ));
        };
        $button->connect('clicked', function () use ($app, $show, &$holds): void {
            if ($holds === 0) {
                $app->hold();
                $holds++;
            } else {
                $app->release();
                $holds--;
            }
            $show();
        });
        $show();
        GLib::timeout_add(1000, function () use ($show): bool {
            $show();
            return true;
        });
        return $button;
    },
    480,
    340,
);
