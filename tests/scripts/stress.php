<?php

declare(strict_types=1);

/*
 * Stress/shutdown script for the sanitizer and coverage runs (not a PHPUnit
 * test): churns through every runtime path - handles, identity, signals,
 * user data, disconnect, exceptions in handlers, destroy vs. release order -
 * many times, then exits normally so that module shutdown runs with live and
 * dead objects around. ASan/LSan report use-after-free, overflows and leaks.
 */

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
GLib::idle_add(fn() => throw new RuntimeException('expected idle'));
$loop->run();
$app = new GtkApplication(null, 1 << 5);
$app->connect('activate', function (GtkApplication $a): void {
    $w = new GtkWindow($a);
    $w->present();
    $w->close();
});
$app->run();

Gtk::set_exception_handler(null);
if ($reported !== 2 * $rounds + 1) {  // two set_title() emissions per round + the idle source
    fwrite(STDERR, 'expected ' . (2 * $rounds + 1) . " reported exceptions, got $reported\n");
    exit(1);
}

echo "stress ok: $rounds rounds, ", count($keep), " windows alive at shutdown\n";
