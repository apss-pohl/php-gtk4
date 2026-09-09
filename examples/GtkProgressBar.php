<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkProgressBar;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkProgressBar - how far along something is.
 *
 * Two modes: set_fraction() when you know (0.0 to 1.0), pulse() when you do not
 * and a block should bounce back and forth instead. Both can show text - the
 * percentage by default, or whatever set_text() says. The page runs one of
 * each: a determinate bar filling up and starting over, and an activity bar
 * pulsing with the same timer, plus an inverted one filling from the right.
 *
 *   bin/php-gtk4 examples/demo.php GtkProgressBar
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkProgressBar',
    'set_fraction() when you know how far, pulse() when you do not',
    function (GtkWindow $win): GtkWidget {
        // Determinate: fraction plus the default percentage text.
        $known = new GtkProgressBar();
        $known->set_show_text(true);
        $known->set_fraction(0.0);

        // Activity mode: pulse() bounces a block; pulse_step is how far per call.
        $busy = new GtkProgressBar();
        $busy->set_show_text(true);
        $busy->set_text('working…');
        $busy->set_pulse_step(0.08);

        // Inverted grows from the other end; custom text instead of a percentage.
        $reverse = new GtkProgressBar();
        $reverse->set_inverted(true);
        $reverse->set_show_text(true);

        $status = Demo::label();
        $describe = function () use ($known, $busy, $reverse, $status): void {
            $status->set_markup(sprintf(
                "<tt>fraction    %.2f  text %s\npulse_step  %.2f  text \"%s\"\ninverted    %s     fraction %.2f</tt>",
                $known->get_fraction(),
                $known->get_text() ?? '(null = percentage)',
                $busy->get_pulse_step(),
                $busy->get_text() ?? '',
                $reverse->get_inverted() ? 'true' : 'false',
                $reverse->get_fraction(),
            ));
        };

        $tick = 0;
        GLib::timeout_add(120, function () use ($known, $busy, $reverse, $describe, &$tick): bool {
            $tick++;
            $fraction = ($tick % 50) / 50.0;
            $known->set_fraction($fraction);
            $reverse->set_fraction(1.0 - $fraction);
            $reverse->set_text(sprintf('%d left', 50 - $tick % 50));
            $busy->pulse();
            if ($tick % 50 === 0) {
                Demo::status('wrapped after ' . $tick . ' ticks');
            }
            $describe();
            return true;
        });

        $describe();

        $page = new GtkBox(GtkOrientation::Vertical, 18);
        $page->set_hexpand(true);
        $page->append($known);
        $page->append($busy);
        $page->append($reverse);
        $page->append($status);
        return $page;
    },
    480,
    300,
);
