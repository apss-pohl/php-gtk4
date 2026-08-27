--TEST--
An exception handler that throws is reported and swallowed, never escaping into GLib
--EXTENSIONS--
gtk4
--SKIPIF--
<?php require __DIR__ . '/skipif-display.inc'; ?>
--FILE--
<?php
use Gtk4\Gtk;
use Gtk4\GtkWindow;

Gtk::init();
Gtk::set_exception_handler(function (Throwable $e, string $origin): void {
    echo "handler saw ", $e::class, " from '$origin'\n";
    throw new DomainException('the handler itself failed');
});
$win = new GtkWindow();
$win->connect('notify::title', function (): void {
    throw new RuntimeException('original');
});
$win->set_title('x');
echo "GTK kept running\n";
$win->destroy();
?>
--EXPECTF--
handler saw RuntimeException from 'notify::title'
%aCRITICAL %a: php-gtk4: Gtk::set_exception_handler() callback threw DomainException while reporting from 'notify::title'
%aCRITICAL %a: php-gtk4: uncaught RuntimeException in 'notify::title' handler: original
GTK kept running
