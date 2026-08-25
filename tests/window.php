<?php
// Milestone 1 smoke test: one element, properties, signal, exception boundary.
function check(bool $ok, string $what): void { if (!$ok) { fwrite(STDERR, "FAIL: $what\n"); exit(1); } }

check(extension_loaded('gtk4'), 'extension loaded');
check(Gtk::init(), 'Gtk::init');

$w = new GtkWindow();
new GtkWindow()->set_title('scratch');  // PHP 8.4: new without parentheses
$w->set_title('hello');
check($w->get_title() === 'hello', 'set/get_title');
check($w->get_property('title') === 'hello', 'get_property');
$w->set_property('title', 'again');
check($w->get_title() === 'again', 'set_property');

$seen = null;
$w->connect('notify::title', function (GObject $obj, string $pspec, string $extra) use (&$seen) {
    $seen = $obj === $GLOBALS['w'] ? "$pspec/$extra" : 'identity-broken';
}, 'userdata');
$w->set_title('x');
check($seen === 'title/userdata', "notify::title delivered with pspec + user arg, same object ($seen)");

$reported = null;
Gtk::set_exception_handler(function (string $msg, string $origin) use (&$reported) { $reported = "$origin: $msg"; });
$w->connect('notify::title', fn() => throw new RuntimeException('boom'));
$w->set_title('y');
check($reported === 'notify::title: boom', "exception routed to handler ($reported)");

$w->connect('close-request', function () { Gtk::main_quit(); return false; });
$w->present();
$w->close();
Gtk::main();
echo "ok\n";
