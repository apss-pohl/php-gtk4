<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkDevice;
use Gtk4\GdkSeat;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkSeat - the set of input devices one user sits at.
 *
 * A display has at least one: a pointer, a keyboard, and whatever else is plugged in. PHP never
 * builds one - GdkDisplay::get_default_seat() is where it comes from, and the seat is how you
 * reach the devices behind the events a window receives.
 *
 *   bin/php-gtk4 examples/demo.php GdkSeat
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkSeat',
    'the set of input devices one user sits at',
    function (GtkWindow $win): GtkWidget {
        $seat = $win->get_display()->get_default_seat();
        if ($seat === null) {
            return Demo::label('<b>this display has no seat</b>');
        }

        $describe = static function (?GdkDevice $device, string $what): string {
            if ($device === null) {
                return sprintf('<b>%s</b>: none', $what);
            }
            return sprintf(
                "<b>%s</b>: %s\n<small>source %s · %s cursor · %d touch points</small>",
                $what,
                htmlspecialchars($device->get_name()),
                $device->get_source()->name,
                $device->get_has_cursor() ? 'has a' : 'no',
                $device->get_num_touches(),
            );
        };

        $label = Demo::label(implode("\n\n", [
            $describe($seat->get_pointer(), 'pointer'),
            $describe($seat->get_keyboard(), 'keyboard'),
        ]));
        Demo::status(sprintf(
            'the seat belongs to a %s; capabilities are a flags value (GdkSeatCapabilities)',
            $seat->get_display()::class,
        ));
        return $label;
    },
    460,
    240,
);
