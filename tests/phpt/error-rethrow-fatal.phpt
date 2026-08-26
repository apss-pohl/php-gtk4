--TEST--
ExceptionMode::Rethrow: an uncaught Throwable ends the process as a PHP fatal
--EXTENSIONS--
gtk4
--SKIPIF--
<?php require __DIR__ . '/skipif-display.inc'; ?>
--FILE--
<?php
use Gtk4\ExceptionMode;
use Gtk4\GLib;
use Gtk4\GMainLoop;
use Gtk4\Gtk;

Gtk::init();
Gtk::set_exception_mode(ExceptionMode::Rethrow);
$loop = new GMainLoop();
GLib::idle_add(function (): bool {
    throw new RuntimeException('nobody catches this');
});
$loop->run();
echo "NOT REACHED\n";
?>
--EXPECTF--
Fatal error: Uncaught RuntimeException: nobody catches this in %s:%d
Stack trace:
%a
