<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GMainLoop;
use Gtk4\GtkApplication;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GMainLoop - a bare main loop, for when there is no GtkApplication.
 *
 * There is no Gtk::main(); this is the low-level alternative to
 * GtkApplication::run() for scripts that just need to pump events.
 *
 * The showcase is itself driven by GtkApplication::run(), so the page can only
 * describe the loop it is holding. Run this file on its own and it drives a real
 * window with no application at all - closing it calls quit() and run() returns.
 *
 *   bin/php-gtk4 examples/demo.php GMainLoop
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GMainLoop',
    'the low-level alternative to GtkApplication::run()',
    // Shown in the showcase: a loop object that is deliberately not run, because
    // this process already has one going.
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $loop = new GMainLoop();
        $label = Demo::label();

        $seconds = 0;
        $render = function () use ($label, $loop, &$seconds): void {
            $label->set_markup(sprintf(
                "<b>GMainLoop</b>\n\n<tt>is_running()  %s</tt>\n\n"
                . "This window belongs to a <b>GtkApplication</b>, whose run() is the loop\n"
                . "currently turning - the GMainLoop above is idle.\n\n"
                . "GLib sources go on the same main context either way:\n"
                . "<tt>this page has been up for %ds</tt>\n\n"
                . '<small>run examples/GMainLoop.php on its own to see a bare loop drive a window</small>',
                $loop->is_running() ? 'true' : 'false',
                $seconds,
            ));
        };

        GLib::timeout_add(1000, function () use (&$seconds, $render): bool {
            $seconds++;
            $render();
            return true;
        });

        $render();
        return $label;
    },
    520,
    320,
    // Run directly: no application, just a window and a loop.
    function (): never {
        Demo::init();

        $loop = new GMainLoop();
        $label = Demo::label();

        $own = new GtkWindow();          // no application: nothing else keeps this alive
        $own->set_title('php-gtk4 · GMainLoop');
        $own->set_default_size(460, 260);
        $own->set_child($label);

        $reentry = 'not tried yet';
        $seconds = 0;

        $render = function () use ($label, $loop, &$seconds, &$reentry): void {
            $label->set_markup(sprintf(
                "<b>GMainLoop</b> <small>(no GtkApplication)</small>\n\n"
                . "<tt>is_running()  %s\nrunning for   %ds</tt>\n\n"
                . "reentrant run(): <i>%s</i>\n\n<small>close the window to call quit()</small>",
                $loop->is_running() ? 'true' : 'false',
                $seconds,
                htmlspecialchars($reentry),
            ));
        };

        // Closing the last window does not stop a bare loop - quit() has to.
        $own->connect('close-request', function () use ($loop): bool {
            $loop->quit();
            return false;
        });

        GLib::timeout_add(1000, function () use (&$seconds, $render): bool {
            $seconds++;
            $render();
            return true;
        });

        // Running the same loop twice is refused rather than deadlocking.
        GLib::idle_add(function () use ($loop, &$reentry, $render): bool {
            try {
                $loop->run();
                $reentry = 'accepted?!';
            } catch (\LogicException $e) {
                $reentry = $e::class . ': ' . $e->getMessage();
            }
            $render();
            return false;
        });

        $render();
        $own->present();

        $loop->run();                    // blocks until quit()

        fwrite(STDOUT, sprintf("loop finished, is_running() = %s\n", $loop->is_running() ? 'true' : 'false'));
        exit(0);
    },
);
