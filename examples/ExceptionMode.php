<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\ExceptionMode;
use Gtk4\GApplicationFlags;
use Gtk4\Gtk;
use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkWindow;

/*
 * Gtk4\ExceptionMode - what happens to a Throwable thrown inside a callback.
 *
 * It cannot unwind through GTK's C frames either way. `Log` reports it and GTK
 * carries on; `Rethrow` also stops the running main loop and hands it back to the
 * PHP that started it. This one does not use the shared Demo::run() harness,
 * because the whole point is the try/catch around run().
 *
 * Click three times: throw and survive, switch to Rethrow, throw and land in the
 * catch below - watch stdout.
 *
 *   bin/php-gtk4 examples/ExceptionMode.php
 */

require __DIR__ . '/bootstrap.php';

Demo::init();

$app = new GtkApplication('org.phpgtk4.examples', GApplicationFlags::NON_UNIQUE);

$app->connect('activate', function (GtkApplication $app): void {
    $status = Demo::label();
    $button = new GtkButton();
    $button->set_child($status);

    $render = function (string $note) use ($status): void {
        $status->set_markup(sprintf(
            "current mode: <b>%s</b>\n\n%s\n\n<small>cases: %s</small>",
            Gtk::get_exception_mode()->name,
            $note,
            implode(', ', array_map(
                static fn(ExceptionMode $m): string => sprintf('%s = %d', $m->name, $m->value),
                ExceptionMode::cases(),
            )),
        ));
    };

    Gtk::set_exception_handler(function (\Throwable $e, string $origin) use ($render): void {
        // Called in *both* modes, before a rethrow.
        $render(sprintf(
            "handler saw <b>%s</b> from <tt>%s</tt>\n<i>%s</i>",
            $e::class,
            htmlspecialchars($origin),
            htmlspecialchars($e->getMessage()),
        ));
    });

    $step = 0;
    $button->connect('clicked', function () use (&$step, $render): void {
        $step++;
        if ($step === 1) {
            throw new \RuntimeException('thrown in ExceptionMode::Log - GTK keeps going');
        }
        if ($step === 2) {
            Gtk::set_exception_mode(ExceptionMode::Rethrow);
            $render('switched to <b>Rethrow</b> — the next click ends run()');
            return;
        }
        throw new \DomainException('thrown in ExceptionMode::Rethrow - run() will rethrow this');
    });

    $render('click to throw from the clicked handler');

    $win = new GtkWindow($app);
    $win->set_title('php-gtk4 · ExceptionMode');
    $win->set_default_size(520, 300);
    $win->set_child($button);
    $win->present();
});

// Rethrow makes the Throwable come back out of run() itself.
try {
    $status = $app->run();
    fwrite(STDOUT, sprintf("run() returned %d - nothing was rethrown\n", $status));
    exit($status);
} catch (\Throwable $e) {
    fwrite(STDOUT, sprintf("run() rethrew %s: %s\n", $e::class, $e->getMessage()));
    exit(1);
}
