--TEST--
RSHUTDOWN disconnects closures still attached to objects that outlive the request
--EXTENSIONS--
gtk4
--SKIPIF--
<?php require __DIR__ . '/skipif-display.inc'; ?>
--FILE--
<?php
use Gtk4\GLib;
use Gtk4\GObject;
use Gtk4\Gtk;
use Gtk4\GtkWindow;

Gtk::init();
$win = new GtkWindow();
$display = $win->get_property('display');   // GdkDisplay: a GTK singleton, lives until process exit
var_dump($display instanceof GObject);

$display->connect('notify::rgba', function (): void {
    echo "handler ran after shutdown\n";
});
GLib::timeout_add(60_000, function (): bool {
    echo "timeout ran after shutdown\n";
    return false;
});
GLib::idle_add(function (): bool {
    echo "idle ran after shutdown\n";
    return false;
});
$win->connect('notify::title', fn() => null);
$win->present();
unset($win);

echo "shutdown ok\n";
?>
--EXPECT--
bool(true)
shutdown ok
