--TEST--
ExceptionMode::Rethrow stops GMainLoop::run() and propagates the Throwable to PHP
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
    throw new LogicException('escapes the loop');
});
try {
    $loop->run();
    echo "NOT REACHED\n";
} catch (LogicException $e) {
    echo "caught ", $e->getMessage(), "\n";
    var_dump($loop->is_running());
}
?>
--EXPECT--
caught escapes the loop
bool(false)
