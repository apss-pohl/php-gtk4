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
// Notified-scope callables (draw func, filter func) on objects kept alive by C.
$area = new Gtk4\GtkDrawingArea();
$area->set_draw_func(function (): void {
    echo "never\n";
});
// (the area is put in a box below; the window keeps that box alive)
$filter = new Gtk4\GtkCustomFilter(fn(): bool => true);
$model = new Gtk4\GtkFilterListModel(new Gtk4\GListStore(), $filter);
GLib::timeout_add(60_000, function (): bool {
    echo "never\n";
    return false;
});
GLib::idle_add(function (): bool {
    echo "never\n";
    return false;
});
// PHP data GTK still owns at RSHUTDOWN: the presented window keeps the list view, which
// keeps the store, which keeps the PhpValue items - their zvals have to be drained before
// Zend goes away rather than released from a finalizer afterwards.
$box = new Gtk4\GtkBox(Gtk4\GtkOrientation::Vertical, 0);
$win->set_child($box);
$box->append($area);
$store = new Gtk4\GListStore(Gtk4\PhpValue::class);
foreach ([['name' => 'Ada'], 'string', 42] as $value) {
    $store->append(new Gtk4\PhpValue($value));
}
$box->append(new Gtk4\GtkListView(new Gtk4\GtkNoSelection($store)));
unset($box, $store);

// A window whose handle we drop but that GTK still references (presented toplevel).
$win->connect('notify::title', fn() => null);
$win->present();
unset($win);

echo "shutdown ok\n";
