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

// D-Bus callables GLib holds with a destroy notify it defers to an idle: an exported object
// and a signal subscription, never released by the script. Teardown's keyed clears
// unregister/unsubscribe them and release the callables while Zend is up; the idle never
// runs. On the session bus the environment provides (tests/run.sh: a private one); skipped
// where there is none, and on Windows, where the bus is GLib's own minimal daemon
// (g_win32_run_session_bus) and the exit with a live connection died with an access violation
// on the TS runner (2026-09-11) - D-Bus is Linux desktop plumbing, GDBusTest skips there too.
if (PHP_OS_FAMILY === 'Windows') {
    fwrite(STDERR, "Windows: D-Bus part skipped\n");
} else {
    try {
        $conn = Gtk4\GDBusConnection::bus_get_sync(Gtk4\GBusType::Session);
        $info = Gtk4\GDBusNodeInfo::new_for_xml('<node><interface name="org.phpgtk4.Shutdown">'
            . '<method name="Never"/></interface></node>')->interfaces[0];
        $conn->register_object('/org/phpgtk4/Shutdown', $info, function (): void {
            echo "never (dbus method)\n";
        }, fn(): string => 'never', fn(): bool => false);
        $conn->signal_subscribe(null, 'org.phpgtk4.Shutdown', null, null, null, 0, function (): void {
            echo "never (dbus signal)\n";
        });
        unset($conn, $info);
    } catch (Gtk4\GError $e) {
        fwrite(STDERR, "no session bus, D-Bus part skipped: {$e->getMessage()}\n");
    }
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

// A handler a .ui document connected: GTK holds the closure and its handler id, php-gtk4 only
// the closure - teardown has to invalidate it before Zend goes, or the button's dispose at
// process exit would run PHP.
$builder = new Gtk4\GtkBuilder();
$builder->set_handlers(['on_click' => function (): void {
    echo "builder handler ran at shutdown\n";
}]);
$builder->add_from_string('<interface><object class="GtkButton" id="b">'
    . '<signal name="clicked" handler="on_click"/></object></interface>');
$built = $builder->get_object('b');
assert($built instanceof Gtk4\GtkButton);
$box->append($built);
unset($builder, $built, $box, $store);

// A window whose handle we drop but that GTK still references (presented toplevel).
$win->connect('notify::title', fn() => null);
$win->present();
unset($win);

echo "shutdown ok\n";
