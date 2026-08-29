<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkEvent;
use Gtk4\GdkTouchpadEvent;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkTouchpadEvent - a multi-finger touchpad gesture.
 *
 * Swipe, pinch and hold gestures (TouchpadSwipe / TouchpadPinch / TouchpadHold)
 * arrive as a sequence of these, one per frame, each tagged with a
 * GdkTouchpadGesturePhase (Begin, Update, End, Cancel). get_n_fingers() says
 * how many fingers are down, get_deltas() how far the centre moved since the
 * previous frame, and for pinches get_pinch_scale() and
 * get_pinch_angle_delta() the zoom factor and rotation. A mouse cannot
 * produce them, so under a mouse this page just lists the getters; on a
 * laptop with libinput, put two or three fingers on the touchpad over the area.
 *
 *   bin/php-gtk4 examples/demo.php GdkTouchpadEvent
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkTouchpadEvent',
    'a multi-finger touchpad gesture - phase, fingers, deltas, pinch scale and angle',
    function (GtkWindow $win): GtkWidget {
        $area = Demo::label(
            "<b>swipe or pinch here</b>\n<small>needs a touchpad with two or three fingers</small>",
        );
        $area->add_css_class('card');
        $area->set_vexpand(true);
        $area->set_hexpand(true);

        $readout = Demo::label(
            "<tt>get_event_type         TouchpadSwipe | TouchpadPinch | TouchpadHold\n"
            . "get_gesture_phase      Begin | Update | End | Cancel\nget_n_fingers          2 or 3\n"
            . "get_deltas             [dx, dy] since the last frame\nget_pinch_scale        zoom factor\n"
            . "get_pinch_angle_delta  rotation in radians</tt>\n<i>no touchpad gesture yet</i>",
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
            if (!$event instanceof GdkTouchpadEvent) {
                return false;
            }
            $count++;
            [$dx, $dy] = $event->get_deltas();
            $readout->set_markup(sprintf(
                "<tt>events                 %d\nget_event_type         %s\nget_gesture_phase      %s\n"
                . "get_n_fingers          %d\nget_deltas             [%.2f, %.2f]\nget_pinch_scale        %.3f\n"
                . 'get_pinch_angle_delta  %.3f</tt>',
                $count,
                $event->get_event_type()->name,
                $event->get_gesture_phase()->name,
                $event->get_n_fingers(),
                $dx,
                $dy,
                $event->get_pinch_scale(),
                $event->get_pinch_angle_delta(),
            ));
            return false;
        });
        $area->add_controller($legacy);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($area);
        $page->append($readout);
        return $page;
    },
    640,
    420,
);
