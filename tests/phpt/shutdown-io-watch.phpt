--TEST--
RSHUTDOWN with an armed I/O watch, and a watch whose stream PHP closed under it
--EXTENSIONS--
gtk4
--SKIPIF--
<?php require __DIR__ . '/skipif-display.inc'; ?>
--FILE--
<?php
// GLib::io_add_watch() parks the callable and the stream in the callback graveyard and
// tracks the source like a timeout, so request shutdown has to remove it before Zend goes
// away - and GLib keeps polling the descriptor even after PHP closed the stream. Both are
// shutdown/crash shapes PHPUnit cannot observe (IoWatchTest covers what PHP can see).
use Gtk4\GIOCondition;
use Gtk4\GLib;
use Gtk4\GMainLoop;
use Gtk4\Gtk;

Gtk::init();

// (1) a watch whose stream is closed while the source is still armed
$closed = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, 0);
GLib::io_add_watch($closed[0], GIOCondition::IN, static fn(): bool => true);
fclose($closed[0]);
fclose($closed[1]);

$loop = new GMainLoop();
GLib::timeout_add(50, function () use ($loop): bool {
    $loop->quit();
    return false;
});
$loop->run();
echo "the loop survived a watch on a closed stream\n";

// (2) a watch still armed when the request ends: teardown removes it
$alive = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, 0);
$id = GLib::io_add_watch($alive[0], GIOCondition::IN, function (): bool {
    echo "this must not run after shutdown\n";
    return true;
});
var_dump($id > 0);
echo "leaving one watch armed\n";
?>
--EXPECT--
the loop survived a watch on a closed stream
bool(true)
leaving one watch armed
