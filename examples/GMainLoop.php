<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GMainLoop;
use Gtk4\Gtk;
use Gtk4\GtkWindow;

/*
 * Gtk4\GMainLoop - a bare main loop, for when there is no GtkApplication.
 *
 * There is no Gtk::main(); this is the low-level alternative to
 * GtkApplication::run() for scripts that just need to pump events. The window
 * below has no application - closing it calls quit() and run() returns.
 *
 *   bin/php-gtk4 examples/GMainLoop.php
 */

require __DIR__ . '/bootstrap.php';

Demo::init();

$loop = new GMainLoop();

$label = Demo::label();
$win = new GtkWindow();                 // no application: nothing else keeps this alive
$win->set_title('php-gtk4 · GMainLoop');
$win->set_default_size(460, 260);
$win->set_child($label);

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
$win->connect('close-request', function () use ($loop): bool {
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
$win->present();

$loop->run();                            // blocks until quit()

fwrite(STDOUT, sprintf("loop finished, is_running() = %s\n", $loop->is_running() ? 'true' : 'false'));
fwrite(STDOUT, sprintf("exception mode was %s\n", Gtk::get_exception_mode()->name));
