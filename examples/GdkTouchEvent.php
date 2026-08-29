<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkEvent;
use Gtk4\GdkTouchEvent;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkTouchEvent - a finger on a touchscreen.
 *
 * TouchBegin / TouchUpdate / TouchEnd / TouchCancel events come from a touch
 * screen, so this page cannot fake one: it explains what would show and
 * listens all the same. Beyond the base class (get_position() is where the
 * finger is, get_event_type() which phase) the class adds
 * get_emulating_pointer() - true for the first touch sequence, which GDK also
 * reports as pointer motion and button events so untouched applications keep
 * working. On a touch device, drag a finger over the area.
 *
 *   bin/php-gtk4 examples/demo.php GdkTouchEvent
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkTouchEvent',
    'a finger on a touchscreen - begin, update, end, and pointer emulation',
    function (GtkWindow $win): GtkWidget {
        $area = Demo::label(
            "<b>touch here</b>\n<small>needs a touchscreen - a mouse produces GdkButtonEvent instead</small>",
        );
        $area->add_css_class('card');
        $area->set_vexpand(true);
        $area->set_hexpand(true);

        $readout = Demo::label(
            "<tt>get_event_type         TouchBegin | TouchUpdate | TouchEnd | TouchCancel\n"
            . "get_position           [x, y] of the finger\nget_emulating_pointer  true for the first finger\n"
            . "get_modifier_state     buttons and keys held meanwhile</tt>\n<i>no touch event yet</i>",
        );
        $readout->set_halign(GtkAlign::Start);

        $count = 0;
        $legacy = new GtkEventControllerLegacy();
        $legacy->connect('event', function (
            GtkEventControllerLegacy $self,
            GdkEvent $event,
        ) use (
            $readout,
            &$count,
        ): bool {
            if (!$event instanceof GdkTouchEvent) {
                return false;
            }
            $count++;
            $position = $event->get_position();
            $readout->set_markup(sprintf(
                "<tt>events                 %d\nget_event_type         %s\nget_position           %s\n"
                . "get_emulating_pointer  %s\nget_modifier_state     %d\nget_time               %d ms</tt>",
                $count,
                $event->get_event_type()->name,
                $position === null ? 'null' : sprintf('%.0f, %.0f', $position[0], $position[1]),
                $event->get_emulating_pointer() ? 'true' : 'false',
                $event->get_modifier_state(),
                $event->get_time(),
            ));
            return false;
        });
        $area->add_controller($legacy);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($area);
        $page->append($readout);
        return $page;
    },
    620,
    400,
);
