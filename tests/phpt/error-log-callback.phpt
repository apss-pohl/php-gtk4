--TEST--
ExceptionMode::Log without a handler: non-signal callback reports its installing method as origin
--EXTENSIONS--
gtk4
--SKIPIF--
<?php require __DIR__ . '/skipif-display.inc'; ?>
--FILE--
<?php
use Gtk4\GLib;
use Gtk4\GMainLoop;
use Gtk4\Gtk;

Gtk::init();
$loop = new GMainLoop();
GLib::idle_add(function () use ($loop): bool {
    $loop->quit();
    throw new LogicException('from the idle callback');
});
$loop->run();
echo "loop returned normally\n";
?>
--EXPECTF--
Warning: php-gtk4: uncaught LogicException in 'GLib::idle_add' handler: from the idle callback in %s on line %d
loop returned normally
