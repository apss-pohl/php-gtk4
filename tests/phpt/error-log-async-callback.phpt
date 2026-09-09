--TEST--
ExceptionMode::Log: a throw inside an async callback names the method that started it
--EXTENSIONS--
gtk4
--SKIPIF--
<?php require __DIR__ . '/skipif-display.inc'; ?>
--FILE--
<?php
// The generated trampolines for GIR scope="async" callbacks (every *_async / choose() /
// GTask completion) go through the same exception boundary as a signal handler, and the
// origin they report is the method that installed the callback. Only stderr sees it, which
// is why this is here and not in DialogTest.
use Gtk4\GAsyncResult;
use Gtk4\GMainLoop;
use Gtk4\GObject;
use Gtk4\GTask;
use Gtk4\Gtk;

Gtk::init();
$loop = new GMainLoop();
$task = new GTask(null, null, function (?GObject $source, GAsyncResult $result) use ($loop): void {
    $loop->quit();
    throw new RuntimeException('from the async callback');
});
$task->return_boolean(true);
$loop->run();
echo "the loop returned and GTK is still running\n";
?>
--EXPECTF--
Warning: php-gtk4: uncaught RuntimeException in 'GTask::__construct' handler: from the async callback in %s on line %d
the loop returned and GTK is still running
