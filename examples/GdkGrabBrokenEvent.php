<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkEvent;
use Gtk4\GdkGrabBrokenEvent;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkGrabBrokenEvent - a pointer or keyboard grab was taken away.
 *
 * While a button is held the surface has an *implicit* grab on the pointer;
 * popovers and drag-and-drop take explicit ones. When the windowing system
 * hands the device to somebody else in the middle of it (another application
 * pops up, a system dialog appears, a drag starts) the surface gets a
 * GrabBroken event, and get_implicit() says which kind of grab it lost. The
 * event is delivered to the surface, so the controller sits on the window.
 * Hard to provoke deliberately: press and hold a button over the area and let
 * another program raise a window in the meantime, or start a drag from a
 * file manager onto this window.
 *
 *   bin/php-gtk4 examples/demo.php GdkGrabBrokenEvent
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkGrabBrokenEvent',
    'a pointer or keyboard grab was taken away - get_implicit()',
    function (GtkWindow $win): GtkWidget {
        $area = Demo::label(
            "<b>hold a button here while another window steals the pointer</b>\n"
            . '<small>the window keeps listening; this rarely fires on purpose</small>',
        );
        $area->add_css_class('card');
        $area->set_vexpand(true);
        $area->set_hexpand(true);

        $readout = Demo::label(
            "<tt>get_event_type  GrabBroken\nget_implicit    true = a held-button grab, false = an explicit one\n"
            . "get_position    where the pointer was</tt>\n<i>no grab broken yet</i>",
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
            if (!$event instanceof GdkGrabBrokenEvent) {
                return false;
            }
            $count++;
            $position = $event->get_position();
            $readout->set_markup(sprintf(
                "<tt>events          %d\nget_event_type  %s\nget_implicit    %s\nget_position    %s\n"
                . 'get_time        %d ms</tt>',
                $count,
                $event->get_event_type()->name,
                $event->get_implicit() ? 'true' : 'false',
                $position === null ? 'null' : sprintf('%.0f, %.0f', $position[0], $position[1]),
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
