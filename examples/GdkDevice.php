<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkDevice;
use Gtk4\GtkEventControllerMotion;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkDevice - which pointer or keyboard an event came from.
 *
 * Every event carries the device that produced it, which is how an application tells a stylus
 * from a mouse, or one user's pointer from another's on a shared display. Move the mouse over
 * this page: the motion controller's current event names its device, and the keyboard's lock
 * states are read from the seat's keyboard as they change.
 *
 *   bin/php-gtk4 examples/demo.php GdkDevice
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkDevice',
    'which pointer or keyboard an event came from',
    function (GtkWindow $win): GtkWidget {
        $label = Demo::label('<b>move the pointer over this page</b>');
        $seat = $win->get_display()->get_default_seat();

        $motion = new GtkEventControllerMotion();
        $motion->connect('motion', static function (
            GtkEventControllerMotion $controller,
            float $x,
            float $y,
        ) use (
            $label,
            $seat
        ): void {
            $device = $controller->get_current_event_device();
            $keyboard = $seat?->get_keyboard();
            $label->set_markup(sprintf(
                "<span size=\"x-large\"><b>%s</b></span>\n"
                . "<small>source %s · at %.0f, %.0f</small>\n\n"
                . '<small>keyboard: caps %s · num %s</small>',
                htmlspecialchars($device?->get_name() ?? 'no device'),
                $device instanceof GdkDevice ? $device->get_source()->name : '-',
                $x,
                $y,
                $keyboard?->get_caps_lock_state() ? 'on' : 'off',
                $keyboard?->get_num_lock_state() ? 'on' : 'off',
            ));
            Demo::status(sprintf(
                'the device belongs to the seat of %s',
                $device?->get_display()->get_name() ?? 'no display',
            ));
        });
        $label->add_controller($motion);
        return $label;
    },
    460,
    240,
);
