<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkEvent;
use Gtk4\GdkTouchpadEvent;
use Gtk4\GdkTouchpadGesturePhase;
use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkTouchpadGesturePhase - where in a touchpad gesture an event sits.
 *
 * A GEnum bound as a native PHP enum, returned by
 * GdkTouchpadEvent::get_gesture_phase(). A swipe or pinch is one Begin, any
 * number of Update frames and one End - or a Cancel when the compositor takes
 * the gesture for itself. The card cycles through the four phases as a
 * progress strip; the readout shows the phase of every real touchpad event
 * over the area (a multi-finger touchpad is needed - a mouse never sends one).
 *
 *   bin/php-gtk4 examples/demo.php GdkTouchpadGesturePhase
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkTouchpadGesturePhase',
    'where in a touchpad gesture an event sits - begin, update, end, cancel',
    function (GtkWindow $win): GtkWidget {
        $cases = GdkTouchpadGesturePhase::cases();
        $strip = static fn(GdkTouchpadGesturePhase $current): string => implode(
            '  ',
            array_map(
                static fn(GdkTouchpadGesturePhase $phase): string => $phase === $current
                    ? sprintf('<b>[%s]</b>', $phase->name)
                    : sprintf('<small>%s</small>', $phase->name),
                $cases,
            ),
        );

        $step = 0;
        $card = Demo::label();
        $card->add_css_class('card');
        $card->set_hexpand(true);
        $show = function () use ($card, $cases, $strip, &$step): void {
            $case = $cases[$step % count($cases)];
            $card->set_markup(sprintf(
                "%s\n<small>%s = GdkTouchpadGesturePhase::from(%d)</small>",
                $strip($case),
                $case->name,
                $case->value,
            ));
        };
        $show();
        GLib::timeout_add(1000, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        $area = new GtkLabel('swipe or pinch here with two or three fingers');
        $area->add_css_class('card');
        $area->set_vexpand(true);
        $area->set_hexpand(true);
        $readout = Demo::label('<i>no touchpad gesture yet</i>');
        $readout->set_halign(GtkAlign::Start);
        $seen = [];
        $legacy = new GtkEventControllerLegacy();
        $legacy->connect('event', function (
            GtkEventControllerLegacy $self,
            GdkEvent $event,
        ) use (
            $readout,
            $strip,
            &$seen,
        ): bool {
            if (!$event instanceof GdkTouchpadEvent) {
                return false;
            }
            $phase = $event->get_gesture_phase();
            $seen[$phase->name] = ($seen[$phase->name] ?? 0) + 1;
            $readout->set_markup(sprintf(
                "%s\n<tt>%s with %d fingers\n\n%s</tt>",
                $strip($phase),
                $event->get_event_type()->name,
                $event->get_n_fingers(),
                implode("\n", array_map(
                    static fn(string $name, int $n): string => sprintf('%-8s %d', $name, $n),
                    array_keys($seen),
                    $seen,
                )),
            ));
            return false;
        });
        $area->add_controller($legacy);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($card);
        $page->append($area);
        $page->append($readout);
        return $page;
    },
    620,
    440,
);
