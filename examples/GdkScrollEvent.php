<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkEvent;
use Gtk4\GdkScrollDirection;
use Gtk4\GdkScrollEvent;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkScrollEvent - a mouse wheel click or a touchpad scroll.
 *
 * A wheel reports discrete steps (get_direction() Up/Down/Left/Right), a
 * touchpad reports GdkScrollDirection::Smooth with the amount in get_deltas()
 * and the unit of that amount in get_unit() (wheel clicks or surface pixels).
 * is_stop() marks the final event of a smooth scroll sequence. The legacy
 * controller keeps every GdkScrollEvent it sees and the readout sums the
 * deltas so the accumulated distance is visible. Scroll over the area.
 *
 *   bin/php-gtk4 examples/demo.php GdkScrollEvent
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkScrollEvent',
    'a wheel click or a touchpad scroll - direction, deltas, unit and stop',
    function (GtkWindow $win): GtkWidget {
        $area = new GtkLabel('scroll here - wheel and touchpad look different');
        $area->add_css_class('card');
        $area->set_vexpand(true);
        $area->set_hexpand(true);

        $readout = Demo::label('<i>waiting for a scroll event</i>');
        $readout->set_halign(GtkAlign::Start);

        $total = [0.0, 0.0];
        $count = 0;
        $legacy = new GtkEventControllerLegacy();
        $legacy->connect('event', function (
            GtkEventControllerLegacy $self,
            GdkEvent $event,
        ) use (
            $readout,
            &$total,
            &$count,
        ): bool {
            if (!$event instanceof GdkScrollEvent) {
                return false;
            }
            $count++;
            [$dx, $dy] = $event->get_deltas();
            $direction = $event->get_direction();
            // Discrete directions carry no deltas: count a wheel click as one unit.
            $total[0] += match ($direction) {
                GdkScrollDirection::Left => -1.0,
                GdkScrollDirection::Right => 1.0,
                default => $dx,
            };
            $total[1] += match ($direction) {
                GdkScrollDirection::Up => -1.0,
                GdkScrollDirection::Down => 1.0,
                default => $dy,
            };
            $readout->set_markup(sprintf(
                "<tt>events         %d\nget_direction  %s\nget_deltas     [%.2f, %.2f]\nget_unit       %s\n"
                . "is_stop        %s\nsum of deltas  [%.1f, %.1f]</tt>",
                $count,
                $direction->name,
                $dx,
                $dy,
                $event->get_unit()->name,
                $event->is_stop() ? 'true' : 'false',
                $total[0],
                $total[1],
            ));
            return false;
        });
        $area->add_controller($legacy);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($area);
        $page->append($readout);
        return $page;
    },
    600,
    420,
);
