<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkEvent;
use Gtk4\GdkFocusEvent;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkFocusEvent - the keyboard focus entered or left a surface.
 *
 * The one getter is get_in(): true when the window gained the focus, false
 * when it lost it. These events belong to the *surface*, not to a widget, so
 * the legacy controller that receives them sits on the window itself (the
 * application's window when demo.php mounts this page - a controller is added,
 * nothing about the window is changed). Click into another window and back,
 * or Alt+Tab away and return, and the readout flips. Widget-level focus
 * changes (Tab between the two areas) are GTK's business, not GDK's: they
 * show up through GtkEventControllerFocus, never as a GdkFocusEvent.
 *
 *   bin/php-gtk4 examples/demo.php GdkFocusEvent
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkFocusEvent',
    'the keyboard focus entered or left the window - get_in()',
    function (GtkWindow $win): GtkWidget {
        $face = Demo::label("<big>focus: <i>unknown</i></big>\n<small>switch to another window and back</small>");
        $face->add_css_class('card');
        $face->set_vexpand(true);
        $face->set_hexpand(true);

        $readout = Demo::label('<i>waiting for a focus change</i>');
        $readout->set_halign(GtkAlign::Start);

        $count = 0;
        $legacy = new GtkEventControllerLegacy();
        $legacy->connect('event', function (
            GtkEventControllerLegacy $self,
            GdkEvent $event,
        ) use (
            $face,
            $readout,
            &$count,
        ): bool {
            if (!$event instanceof GdkFocusEvent) {
                return false;
            }
            $count++;
            $in = $event->get_in();
            $face->set_markup(sprintf(
                "<big>focus: <b>%s</b></big>\n<small>switch to another window and back</small>",
                $in ? 'in' : 'out',
            ));
            $readout->set_markup(sprintf(
                "<tt>events          %d\nevent_type      %s\nget_in          %s\nget_position    %s\n"
                . 'get_time        %d ms</tt>',
                $count,
                $event->get_event_type()->name,
                $in ? 'true' : 'false',
                $event->get_position() === null ? 'null (focus events have none)' : '?',
                $event->get_time(),
            ));
            return false;
        });
        $win->add_controller($legacy);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($face);
        $page->append($readout);
        return $page;
    },
    560,
    360,
);
