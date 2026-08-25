<?php

declare(strict_types=1);

/*
 * Regression script for request-shutdown teardown (tests/ShutdownTest.php).
 * Leaves PHP callables attached to C objects that outlive the request - the
 * GdkDisplay (a GTK singleton) and an armed timeout source - and exits
 * normally. Without RSHUTDOWN teardown the closures would be finalized after
 * Zend is gone and touch a dead allocator. Must exit 0 and print "shutdown ok".
 */

use Gtk4\GLib;
use Gtk4\Gtk;
use Gtk4\GtkWindow;

if (!Gtk::init()) {
    fwrite(STDERR, "no display\n");
    exit(1);
}

$win = new GtkWindow();
$display = $win->get_property('display');   // GdkDisplay: lives until process exit
if (!$display instanceof Gtk4\GObject) {
    fwrite(STDERR, "no display object\n");
    exit(1);
}
$display->connect('notify::rgba', function (): void {
    echo "never\n";
});
$display->connect('closed', function (): void {
    echo "never\n";
});
GLib::timeout_add(60_000, function (): bool {
    echo "never\n";
    return false;
});
GLib::idle_add(function (): bool {
    echo "never\n";
    return false;
});
// A window whose handle we drop but that GTK still references (presented toplevel).
$win->connect('notify::title', fn() => null);
$win->present();
unset($win);

echo "shutdown ok\n";
