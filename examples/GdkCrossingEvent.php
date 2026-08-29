<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkCrossingEvent;
use Gtk4\GdkEvent;
use Gtk4\GdkEventType;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkCrossingEvent - the pointer entered or left a widget.
 *
 * Two boxes side by side, each with a GtkEventControllerLegacy: moving the
 * pointer from one into the other produces a LeaveNotify on the first and an
 * EnterNotify on the second. get_mode() says why the crossing happened
 * (GdkCrossingMode::Normal for plain pointer motion, Grab/Ungrab around a
 * button press), get_detail() how the two widgets relate (GdkNotifyType) and
 * get_focus() whether the crossed widget is the focus widget. The box that
 * has the pointer is highlighted so the events are visible without reading.
 *
 *   bin/php-gtk4 examples/demo.php GdkCrossingEvent
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkCrossingEvent',
    'the pointer entered or left a widget - mode, detail and focus',
    function (GtkWindow $win): GtkWidget {
        $readout = Demo::label('<i>move the pointer through the two boxes</i>');
        $readout->set_halign(GtkAlign::Start);

        $row = new GtkBox(GtkOrientation::Horizontal, 12);
        $row->set_homogeneous(true);
        $row->set_vexpand(true);
        foreach (['left box', 'right box'] as $name) {
            $box = new GtkLabel($name);
            $box->add_css_class('card');
            $box->set_hexpand(true);

            $legacy = new GtkEventControllerLegacy();
            $legacy->connect('event', function (
                GtkEventControllerLegacy $self,
                GdkEvent $event,
            ) use (
                $box,
                $name,
                $readout,
            ): bool {
                if (!$event instanceof GdkCrossingEvent) {
                    return false;
                }
                $entered = $event->get_event_type() === GdkEventType::EnterNotify;
                if ($entered) {
                    $box->add_css_class('accent');
                } else {
                    $box->remove_css_class('accent');
                }
                $position = $event->get_position();
                $readout->set_markup(sprintf(
                    "<tt>widget        %s\nevent_type    %s\nget_mode      %s\nget_detail    %s\n"
                    . "get_focus     %s\nget_position  %s</tt>",
                    $name,
                    $event->get_event_type()->name,
                    $event->get_mode()->name,
                    $event->get_detail()->name,
                    $event->get_focus() ? 'true' : 'false',
                    $position === null ? 'null' : sprintf('%.0f, %.0f', $position[0], $position[1]),
                ));
                return false;
            });
            $box->add_controller($legacy);
            $row->append($box);
        }

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($row);
        $page->append($readout);
        return $page;
    },
    600,
    400,
);
