<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkEvent;
use Gtk4\GdkScrollDirection;
use Gtk4\GdkScrollEvent;
use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkScrollDirection - which way a scroll event goes.
 *
 * A GEnum bound as a native PHP enum, returned by GdkScrollEvent::get_direction().
 * Up/Down/Left/Right are the discrete wheel clicks; Smooth is what a touchpad
 * (and a high-resolution wheel) sends, with the amount in get_deltas() instead.
 * The card cycles through the five cases with an arrow for each, and the
 * readout names the case of every real scroll event over the area.
 *
 *   bin/php-gtk4 examples/demo.php GdkScrollDirection
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkScrollDirection',
    'which way a scroll event goes - wheel clicks versus smooth deltas',
    function (GtkWindow $win): GtkWidget {
        $arrow = static fn(GdkScrollDirection $direction): string => match ($direction) {
            GdkScrollDirection::Up => '↑',
            GdkScrollDirection::Down => '↓',
            GdkScrollDirection::Left => '←',
            GdkScrollDirection::Right => '→',
            GdkScrollDirection::Smooth => '~',
        };

        $cases = GdkScrollDirection::cases();
        $step = 0;
        $card = Demo::label();
        $card->add_css_class('card');
        $card->set_hexpand(true);
        $show = function () use ($card, $cases, $arrow, &$step): void {
            $case = $cases[$step % count($cases)];
            $card->set_markup(sprintf(
                "<big><big>%s</big></big>\n<b>%s</b> <small>(%d)</small>\n<small>%s</small>",
                $arrow($case),
                $case->name,
                $case->value,
                implode(' · ', array_map(static fn(GdkScrollDirection $d): string => $d->name, $cases)),
            ));
        };
        $show();
        GLib::timeout_add(1000, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        $area = new GtkLabel('scroll here - wheel, then touchpad');
        $area->add_css_class('card');
        $area->set_vexpand(true);
        $area->set_hexpand(true);
        $readout = Demo::label('<i>no scroll event yet</i>');
        $readout->set_halign(GtkAlign::Start);
        $seen = [];
        $legacy = new GtkEventControllerLegacy();
        $legacy->connect('event', function (
            GtkEventControllerLegacy $self,
            GdkEvent $event,
        ) use (
            $readout,
            $arrow,
            &$seen,
        ): bool {
            if (!$event instanceof GdkScrollEvent) {
                return false;
            }
            $direction = $event->get_direction();
            $seen[$direction->name] = ($seen[$direction->name] ?? 0) + 1;
            [$dx, $dy] = $event->get_deltas();
            $readout->set_markup(sprintf(
                "<tt>get_direction  %s <b>%s</b> = GdkScrollDirection::from(%d)\n"
                . "get_deltas     [%.2f, %.2f]%s\n\n%s</tt>",
                $arrow($direction),
                $direction->name,
                $direction->value,
                $dx,
                $dy,
                $direction === GdkScrollDirection::Smooth ? '' : '  (discrete: deltas are zero)',
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
    600,
    480,
);
