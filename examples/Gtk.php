<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\Gtk;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\Gtk - initialisation and the callback exception policy.
 *
 * A Throwable must never unwind through GTK's C frames, so one thrown inside a
 * handler is handed to the callable installed here instead. Click the button and
 * watch the app report the exception and keep running.
 *
 *   bin/php-gtk4 examples/demo.php Gtk
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'Gtk',
    'initialisation and the callback exception policy',
    function (GtkWindow $win): GtkWidget {
        $status = Demo::label(
            "<span size=\"x-large\"><b>Gtk::init()</b> succeeded</span>\n\n"
            . 'click to throw a RuntimeException from the clicked handler',
        );
        $button = new GtkButton();
        $button->set_child($status);

        $caught = 0;
        // Replaces the handler bootstrap.php installed. $origin is the signal name
        // (or the installing method, for non-signal callbacks).
        Gtk::set_exception_handler(function (\Throwable $e, string $origin) use ($status, &$caught): void {
            $caught++;
            $status->set_markup(sprintf(
                "caught <b>#%d</b>: <span foreground=\"%s\">%s</span>\n"
                . "from <tt>%s</tt>\n<i>%s</i>\n\n"
                . "mode <b>%s</b> - GTK carried on\n<small>click again</small>",
                $caught,
                Demo::WARN,
                $e::class,
                htmlspecialchars($origin),
                htmlspecialchars($e->getMessage()),
                Gtk::get_exception_mode()->name,
            ));
        });

        $button->connect('clicked', function () use (&$caught): void {
            throw new \RuntimeException('thrown from a signal handler #' . ($caught + 1));
        });

        return $button;
    },
);
