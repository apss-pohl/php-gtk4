--TEST--
ExceptionMode::Log without a handler: signal handler exception -> a PHP warning
--EXTENSIONS--
gtk4
--SKIPIF--
<?php require __DIR__ . '/skipif-display.inc'; ?>
--FILE--
<?php
use Gtk4\Gtk;
use Gtk4\GtkWindow;

Gtk::init();
$win = new GtkWindow();
$win->connect('notify::title', function (): void {
    throw new RuntimeException('from the signal handler');
});
$win->set_title('x');
echo "GTK kept running\n";
$win->destroy();
?>
--EXPECTF--
Warning: php-gtk4: uncaught RuntimeException in 'notify::title' handler: from the signal handler in %s on line %d
GTK kept running
