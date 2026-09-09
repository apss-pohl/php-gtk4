<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GDateTime;
use Gtk4\GTimeZone;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GDateTime - the date and time GLib hands out.
 *
 * A GtkCalendar answers with one, a cookie's expiry and a certificate's validity are ones. It is
 * a boxed *value*: every add_* answers with a new date and leaves the original alone, which is
 * why the walk below can keep the first date to compare against. format() is strftime, and
 * to_timezone() moves the same instant to another zone rather than changing the clock.
 *
 *   bin/php-gtk4 examples/demo.php GDateTime
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GDateTime',
    'a date and time as a value: arithmetic, formatting, time zones',
    function (GtkWindow $win): GtkWidget {
        // GLib answers null for an instant that does not exist (month 13, hour 25); this one does,
        // so the example says what it expects rather than carrying a null through every step.
        $start = GDateTime::new_utc(1815, 12, 10, 12, 30, 0.0)
            ?? throw new \RuntimeException('1815-12-10 12:30 UTC is a real instant');

        // An IANA name needs a tz database GLib can read; where there is none (Windows), the
        // identifier answers null and UTC stands in.
        $zone = static fn(string $name): GTimeZone => GTimeZone::new_identifier($name) ?? GTimeZone::new_utc();

        /** @var list<array{string, callable(GDateTime): ?GDateTime}> $steps */
        $steps = [
            ['as built (UTC)', static fn(GDateTime $d): GDateTime => $d],
            ['add_years(200)', static fn(GDateTime $d): ?GDateTime => $d->add_years(200)],
            ['add_months(6)', static fn(GDateTime $d): ?GDateTime => $d->add_years(200)?->add_months(6)],
            ['to_timezone(Europe/Berlin)', static fn(GDateTime $d): ?GDateTime
                => $d->add_years(200)?->to_timezone($zone('Europe/Berlin'))],
            ['to_timezone(Asia/Tokyo)', static fn(GDateTime $d): ?GDateTime
                => $d->add_years(200)?->to_timezone($zone('Asia/Tokyo'))],
        ];

        $label = Demo::label();
        $at = 0;
        $render = function () use (&$at, $steps, $start, $label): void {
            [$name, $apply] = $steps[$at % count($steps)];
            $date = $apply($start) ?? $start;
            $label->set_markup(sprintf(
                "<span size=\"x-large\"><b>%s</b></span>\n<tt>%s</tt>\n\n<small>%s</small>",
                htmlspecialchars($date->format('%A, %e %B %Y') ?? ''),
                htmlspecialchars($date->format('%H:%M:%S %Z') ?? ''),
                htmlspecialchars($name),
            ));
            Demo::status(sprintf(
                'ISO 8601: %s · unix: %d · %s the original',
                $date->format_iso8601() ?? '-',
                $date->to_unix(),
                match (true) {
                    $date->compare($start) > 0 => 'after',
                    $date->compare($start) < 0 => 'before',
                    default => 'the same instant as',
                },
            ));
        };

        $button = new GtkButton();
        $button->set_child($label);
        $button->connect('clicked', function () use (&$at, $render): void {
            $at++;
            $render();
        });
        $render();
        return $button;
    },
    480,
    260,
);
