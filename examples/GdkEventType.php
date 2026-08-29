<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkEvent;
use Gtk4\GdkEventType;
use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkEventType - which kind of event a GdkEvent is.
 *
 * A GEnum bound as a native PHP enum, returned by GdkEvent::get_event_type().
 * The card at the top walks through every case on a timer (with the GdkEvent
 * subclass that carries it), and the readout below lights up the case of each
 * real event a GtkEventControllerLegacy sees on the area - move, click,
 * scroll and type to see MotionNotify, ButtonPress, Scroll, KeyPress and so on.
 * EventLast is only a sentinel; no event ever has it.
 *
 *   bin/php-gtk4 examples/demo.php GdkEventType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkEventType',
    'which kind of event a GdkEvent is - the case of every real event as it arrives',
    function (GtkWindow $win): GtkWidget {
        $carrier = static fn(GdkEventType $type): string => match ($type) {
            GdkEventType::KeyPress, GdkEventType::KeyRelease => 'GdkKeyEvent',
            GdkEventType::ButtonPress, GdkEventType::ButtonRelease => 'GdkButtonEvent',
            GdkEventType::Scroll => 'GdkScrollEvent',
            GdkEventType::EnterNotify, GdkEventType::LeaveNotify => 'GdkCrossingEvent',
            GdkEventType::FocusChange => 'GdkFocusEvent',
            GdkEventType::GrabBroken => 'GdkGrabBrokenEvent',
            GdkEventType::TouchBegin, GdkEventType::TouchUpdate, GdkEventType::TouchEnd,
            GdkEventType::TouchCancel => 'GdkTouchEvent',
            GdkEventType::TouchpadSwipe, GdkEventType::TouchpadPinch, GdkEventType::TouchpadHold => 'GdkTouchpadEvent',
            GdkEventType::PadButtonPress, GdkEventType::PadButtonRelease, GdkEventType::PadRing,
            GdkEventType::PadStrip, GdkEventType::PadGroupMode => 'GdkPadEvent',
            GdkEventType::EventLast => '(sentinel, never an event)',
            default => 'GdkEvent',
        };

        // The cycling card: every case in turn, with the class that carries it.
        $cases = GdkEventType::cases();
        $step = 0;
        $card = Demo::label();
        $card->add_css_class('card');
        $card->set_hexpand(true);
        $show = function () use ($card, $cases, $carrier, &$step): void {
            $case = $cases[$step % count($cases)];
            $card->set_markup(sprintf(
                "<b>%s</b> <small>(%d)</small>\n<small>%s</small>",
                $case->name,
                $case->value,
                $carrier($case),
            ));
        };
        $show();
        GLib::timeout_add(900, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        // The live readout: whatever the area actually receives.
        $area = new GtkLabel('move, click, scroll and type here');
        $area->add_css_class('card');
        $area->set_focusable(true);
        $area->set_vexpand(true);
        $area->set_hexpand(true);
        $seen = [];
        $readout = Demo::label('<i>no event yet</i>');
        $readout->set_halign(GtkAlign::Start);
        $legacy = new GtkEventControllerLegacy();
        $legacy->connect('event', function (
            GtkEventControllerLegacy $self,
            GdkEvent $event,
        ) use (
            $readout,
            $carrier,
            &$seen,
        ): bool {
            $type = $event->get_event_type();
            $seen[$type->name] = ($seen[$type->name] ?? 0) + 1;
            $readout->set_markup(sprintf(
                "<tt>last   <b>%s</b> = GdkEventType::from(%d), a %s\n\n%s</tt>",
                $type->name,
                $type->value,
                $carrier($type),
                implode("\n", array_map(
                    static fn(string $name, int $n): string => sprintf('%-18s %d', $name, $n),
                    array_keys($seen),
                    $seen,
                )),
            ));
            return false;
        });
        $area->add_controller($legacy);
        GLib::idle_add(function () use ($area): bool {
            $area->grab_focus();
            return false;
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($card);
        $page->append($area);
        $page->append($readout);
        return $page;
    },
    600,
    520,
);
