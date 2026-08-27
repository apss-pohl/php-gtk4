<?php

declare(strict_types=1);

/*
 * Stress/shutdown script for the sanitizer and coverage runs (not a PHPUnit
 * test): churns through every runtime path - handles, identity, signals,
 * user data, disconnect, exceptions in handlers, destroy vs. release order -
 * many times, then exits normally so that module shutdown runs with live and
 * dead objects around. ASan/LSan report use-after-free, overflows and leaks.
 */

use Gtk4\ExceptionMode;
use Gtk4\GLib;
use Gtk4\GMainLoop;
use Gtk4\GObject;
use Gtk4\Gtk;
use Gtk4\GtkApplication;
use Gtk4\GtkWindow;

if (!Gtk::init()) {
    fwrite(STDERR, "no display\n");
    exit(1);
}

$rounds = (int) ($argv[1] ?? 200);
$reported = 0;
Gtk::set_exception_handler(function () use (&$reported): void {
    $reported++;
});

$keep = [];
for ($i = 0; $i < $rounds; $i++) {
    $w = new GtkWindow();
    $w->set_title("w$i");
    $w->set_default_size(100 + $i, 100);

    $ids = [];
    for ($j = 0; $j < 5; $j++) {
        $ids[] = $w->connect('notify::title', function (GObject $o, Gtk4\GParamSpec $p) use (&$hits, $j): void {
            $hits = ($hits ?? 0) + $j;
        });
    }
    $ids[] = $w->connect_after('notify::title', fn() => null);
    $w->connect('notify::title', function (): void {
        throw new RuntimeException('expected');
    });
    $w->set_title("x$i");

    // identity + object property round trip
    $other = new GtkWindow();
    $w->set_property('transient-for', $other);
    if ($w->get_property('transient-for') !== $other) {
        fwrite(STDERR, "identity broken\n");
        exit(1);
    }
    $w->set_property('transient-for', null);

    foreach ($ids as $k => $id) {
        if ($k % 2 === 0) {
            $w->handler_disconnect($id);
        }
    }
    $w->set_title("y$i");

    // mix of lifetimes: some destroyed, some only released, some kept alive
    switch ($i % 4) {
        case 0:
            $w->present();
            $w->destroy();
            break;
        case 1:
            $w->destroy();
            unset($w);
            break;
        case 2:
            unset($w);
            break;
        case 3:
            $keep[] = $w;   // outlives the loop; freed at shutdown
            break;
    }
    unset($other);
}

// main loops: bare loop with idle/timeout sources, then an application run
$loop = new GMainLoop();
$ticks = 0;
GLib::timeout_add(1, function () use (&$ticks, $loop): bool {
    if (++$ticks >= 5) {
        $loop->quit();
        return false;
    }
    return true;
});
$idleRan = false;
GLib::idle_add(function () use (&$idleRan): bool {
    $idleRan = true;
    throw new RuntimeException('expected idle');
});
$loop->run();
// With many windows alive the frame clocks outrank the idle and the timeouts may quit the loop
// first; make sure it has fired (in Log mode) before the mode changes below.
while (!$idleRan) {
    GLib::main_context_iteration(true);
}
// C-driven nested loop (test builds): a parked Throwable + a chained one, rethrown by run().
if (str_contains((string) ini_get('gtk4.features'), 'testing=yes')) {
    Gtk::set_exception_mode(ExceptionMode::Rethrow);
    $nested = new GMainLoop();
    GLib::idle_add(function (): bool {
        GLib::timeout_add(0, fn() => throw new RuntimeException('nested 1'));
        GLib::timeout_add(0, fn() => throw new RuntimeException('nested 2'));
        usleep(2000);
        Gtk::testing_iterate_nested(2);
        return false;
    });
    try {
        $nested->run();
        fwrite(STDERR, "nested rethrow missing\n");
        exit(1);
    } catch (RuntimeException $e) {
        if ($e->getMessage() !== 'nested 1' || $e->getPrevious()?->getMessage() !== 'nested 2') {
            fwrite(STDERR, "nested chain wrong\n");
            exit(1);
        }
    }
    Gtk::set_exception_mode(ExceptionMode::Log);
}
$app = new GtkApplication(null, 1 << 5);
$app->connect('activate', function (GtkApplication $a): void {
    $w = new GtkWindow($a);
    $w->present();
    $w->close();
});
$app->run();

Gtk::set_exception_handler(null);
// two set_title() emissions per round + the idle source (+ the two nested ones in test builds)
$expected = 2 * $rounds + 1 + (str_contains((string) ini_get('gtk4.features'), 'testing=yes') ? 2 : 0);
if ($reported !== $expected) {
    fwrite(STDERR, "expected $expected reported exceptions, got $reported\n");
    exit(1);
}

echo "stress ok: $rounds rounds, ", count($keep), " windows alive at shutdown\n";
