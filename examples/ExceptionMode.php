<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\ExceptionMode;
use Gtk4\GApplicationFlags;
use Gtk4\Gtk;
use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\ExceptionMode - what happens to a Throwable thrown inside a callback.
 *
 * It cannot unwind through GTK's C frames either way. `Log` reports it and GTK
 * carries on; `Rethrow` also stops the running main loop and hands it back to the
 * PHP that started it.
 *
 * Inside the showcase only the `Log` half runs - `Rethrow` would end the
 * application, which is exactly the point. Run this file on its own and the third
 * click lands in the try/catch around run(), printed to stdout.
 *
 *   bin/php-gtk4 examples/demo.php ExceptionMode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'ExceptionMode',
    'Log survives the throw; Rethrow comes back out of run()',
    // Shown in the showcase: Log mode only, so the application stays alive.
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $status = Demo::label();
        $button = new GtkButton();
        $button->set_child($status);

        $render = function (string $note) use ($status): void {
            $status->set_markup(sprintf(
                "current mode: <b>%s</b>\n\n%s\n\n<small>cases: %s</small>",
                Gtk::get_exception_mode()->name,
                $note,
                implode(', ', array_map(
                    static fn(ExceptionMode $mode): string => sprintf('%s = %d', $mode->name, $mode->value),
                    ExceptionMode::cases(),
                )),
            ));
        };

        // Called in *both* modes, before a rethrow.
        Gtk::set_exception_handler(function (\Throwable $e, string $origin) use ($render): void {
            $render(sprintf(
                "handler saw <b>%s</b> from <tt>%s</tt>\n<i>%s</i>\n\n"
                . '<small>GTK carried on - that is ExceptionMode::Log</small>',
                $e::class,
                htmlspecialchars($origin),
                htmlspecialchars($e->getMessage()),
            ));
        });

        $button->connect('clicked', function (): void {
            throw new \RuntimeException('thrown in ExceptionMode::Log - GTK keeps going');
        });

        $render('click to throw from the clicked handler');
        return $button;
    },
    520,
    320,
    // Run directly: the same demo, plus the Rethrow half, which needs a try/catch
    // around run() and therefore its own application.
    function (): never {
        Demo::init();
        $app = new GtkApplication('org.phpgtk4.examples', GApplicationFlags::NON_UNIQUE);

        $app->connect('activate', function (GtkApplication $app): void {
            $status = Demo::label();
            $button = new GtkButton();
            $button->set_child($status);

            $render = function (string $note) use ($status): void {
                $status->set_markup(sprintf(
                    "current mode: <b>%s</b>\n\n%s",
                    Gtk::get_exception_mode()->name,
                    $note,
                ));
            };
            Gtk::set_exception_handler(function (\Throwable $e, string $origin) use ($render): void {
                $render(sprintf('handler saw <b>%s</b> from <tt>%s</tt>', $e::class, htmlspecialchars($origin)));
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

            $render('click three times: throw and survive, switch mode, throw and exit');

            $own = new GtkWindow($app);
            $own->set_title('php-gtk4 · ExceptionMode');
            $own->set_default_size(520, 300);
            $own->set_child($button);
            $own->present();
        });

        try {
            $status = $app->run();
            fwrite(STDOUT, sprintf("run() returned %d - nothing was rethrown\n", $status));
            exit($status);
        } catch (\Throwable $e) {
            fwrite(STDOUT, sprintf("run() rethrew %s: %s\n", $e::class, $e->getMessage()));
            exit(1);
        }
    },
);
