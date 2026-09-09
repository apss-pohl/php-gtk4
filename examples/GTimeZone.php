<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GDateTime;
use Gtk4\GTimeZone;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GTimeZone - the zone a GDateTime is read in.
 *
 * Not a constructor but three factories: new_utc(), new_local() (whatever this machine is set
 * to) and new_identifier() for a name out of the tz database. The same instant read in another
 * zone is a different wall clock and the same unix time, which is what the page shows: one
 * moment, several zones, one number underneath.
 *
 *   bin/php-gtk4 examples/demo.php GTimeZone
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GTimeZone',
    'the same instant, read in different zones',
    function (GtkWindow $win): GtkWidget {
        $zones = ['UTC', 'Europe/Berlin', 'America/New_York', 'Asia/Tokyo', 'Australia/Sydney'];
        $instant = GDateTime::new_utc(2026, 6, 21, 12, 0, 0.0)
            ?? throw new \RuntimeException('2026-06-21 12:00 UTC is a real instant');

        $label = Demo::label();
        $at = 0;
        $render = function () use (&$at, $zones, $instant, $label): void {
            $name = $zones[$at % count($zones)];
            // A name needs a tz database GLib can read - Windows has none of its own, so an
            // unknown identifier answers null and UTC stands in.
            $zone = GTimeZone::new_identifier($name) ?? GTimeZone::new_utc();
            $there = $instant->to_timezone($zone) ?? $instant;
            $local = $instant->to_timezone(GTimeZone::new_local());
            $label->set_markup(sprintf(
                "<span size=\"x-large\"><b>%s</b></span>\n<tt>%s</tt>\n\n"
                . '<small>this machine reads it as <tt>%s</tt></small>',
                htmlspecialchars($name),
                htmlspecialchars($there->format('%Y-%m-%d %H:%M %Z') ?? ''),
                htmlspecialchars($local?->format('%Y-%m-%d %H:%M %Z') ?? '?'),
            ));
            Demo::status(sprintf(
                'identifier %s · unix time %d, the same in every zone',
                $zone->get_identifier(),
                $there->to_unix(),
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
    460,
    240,
);
