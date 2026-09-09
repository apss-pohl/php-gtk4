<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkCrossingEvent;
use Gtk4\GdkCrossingMode;
use Gtk4\GdkEvent;
use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkCrossingMode - why the pointer entered or left.
 *
 * A GEnum bound as a native PHP enum, returned by GdkCrossingEvent::get_mode().
 * Normal is plain pointer motion; Grab/Ungrab bracket a pointer grab (press a
 * button over the area and release it outside to see them); GtkGrab/GtkUngrab
 * are GTK's own widget grabs, StateChanged a crossing caused by a widget
 * becoming (in)sensitive or unmapped, TouchBegin/TouchEnd the crossings a
 * touch sequence implies and DeviceSwitch a change of the active device.
 * The card cycles through the cases, the readout names the one of each real
 * crossing event.
 *
 *   bin/php-gtk4 examples/demo.php GdkCrossingMode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkCrossingMode',
    'why the pointer entered or left - motion, grabs, state changes, touch, device switch',
    function (GtkWindow $win): GtkWidget {
        $explain = static fn(GdkCrossingMode $mode): string => match ($mode) {
            GdkCrossingMode::Normal => 'the pointer moved',
            GdkCrossingMode::Grab => 'a pointer grab started (button pressed)',
            GdkCrossingMode::Ungrab => 'a pointer grab ended (button released)',
            GdkCrossingMode::GtkGrab => 'a GTK widget grab started',
            GdkCrossingMode::GtkUngrab => 'a GTK widget grab ended',
            GdkCrossingMode::StateChanged => 'the widget under the pointer changed state',
            GdkCrossingMode::TouchBegin => 'implied by a touch beginning',
            GdkCrossingMode::TouchEnd => 'implied by a touch ending',
            GdkCrossingMode::DeviceSwitch => 'the active device changed',
        };

        $cases = GdkCrossingMode::cases();
        $step = 0;
        $card = Demo::label();
        $card->add_css_class('card');
        $card->set_hexpand(true);
        $show = function () use ($card, $cases, $explain, &$step): void {
            $case = $cases[$step % count($cases)];
            $card->set_markup(sprintf(
                "<b>%s</b> <small>(%d)</small>\n<small>%s</small>",
                $case->name,
                $case->value,
                $explain($case),
            ));
        };
        $show();
        GLib::timeout_add(1000, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        $area = new GtkLabel('move in and out - then press here and release outside');
        $area->add_css_class('card');
        $area->set_vexpand(true);
        $area->set_hexpand(true);
        $readout = Demo::label('<i>no crossing event yet</i>');
        $readout->set_halign(GtkAlign::Start);
        $seen = [];
        $legacy = new GtkEventControllerLegacy();
        $legacy->connect('event', function (
            GtkEventControllerLegacy $self,
            GdkEvent $event,
        ) use (
            $readout,
            $explain,
            &$seen,
        ): bool {
            if (!$event instanceof GdkCrossingEvent) {
                return false;
            }
            $mode = $event->get_mode();
            $seen[$mode->name] = ($seen[$mode->name] ?? 0) + 1;
            $readout->set_markup(sprintf(
                "<tt>%s\nget_mode  <b>%s</b> = GdkCrossingMode::from(%d)\n          %s\n\n%s</tt>",
                $event->get_event_type()->name,
                $mode->name,
                $mode->value,
                $explain($mode),
                implode("\n", array_map(
                    static fn(string $name, int $n): string => sprintf('%-14s %d', $name, $n),
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
    480,
);
