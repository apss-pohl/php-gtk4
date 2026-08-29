<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkEvent;
use Gtk4\GdkEventType;
use Gtk4\GdkPadEvent;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkPadEvent - a button, ring or strip on a drawing-tablet pad.
 *
 * Tablet pads (the buttons and touch rings beside the drawing surface) report
 * PadButtonPress / PadButtonRelease with get_button(), PadRing / PadStrip with
 * get_axis_value() = [index, value], and PadGroupMode when the pad's mode
 * switches; get_group_mode() = [group, mode] applies to all of them. Without
 * a tablet the page cannot show live values, so it lists what each event type
 * carries and keeps listening on the window - pad events are not tied to the
 * pointer position, they reach the focused surface.
 *
 *   bin/php-gtk4 examples/demo.php GdkPadEvent
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkPadEvent',
    'a drawing-tablet pad button, ring or strip - button, axis value, group and mode',
    function (GtkWindow $win): GtkWidget {
        $area = Demo::label("<b>press a pad button</b>\n<small>needs a drawing tablet with a pad</small>");
        $area->add_css_class('card');
        $area->set_vexpand(true);
        $area->set_hexpand(true);

        $readout = Demo::label(
            "<tt>PadButtonPress/Release  get_button()      which pad button\n"
            . "PadRing / PadStrip      get_axis_value()  [index, value 0..1]\n"
            . "PadGroupMode            get_group_mode()  [group, mode]</tt>\n<i>no pad event yet</i>",
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
            if (!$event instanceof GdkPadEvent) {
                return false;
            }
            $count++;
            $type = $event->get_event_type();
            [$group, $mode] = $event->get_group_mode();
            $detail = match ($type) {
                GdkEventType::PadRing, GdkEventType::PadStrip => sprintf(
                    'get_axis_value   [%d, %.3f]',
                    ...$event->get_axis_value(),
                ),
                GdkEventType::PadGroupMode => 'get_group_mode   (mode switch)',
                default => sprintf('get_button       %d', $event->get_button()),
            };
            $readout->set_markup(sprintf(
                "<tt>events           %d\nget_event_type   %s\n%s\nget_group_mode   [%d, %d]\n"
                . 'get_time         %d ms</tt>',
                $count,
                $type->name,
                $detail,
                $group,
                $mode,
                $event->get_time(),
            ));
            return false;
        });
        $win->add_controller($legacy);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($area);
        $page->append($readout);
        return $page;
    },
    620,
    380,
);
