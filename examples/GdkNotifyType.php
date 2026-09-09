<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkCrossingEvent;
use Gtk4\GdkEvent;
use Gtk4\GdkNotifyType;
use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkNotifyType - how the widget left and the widget entered are related.
 *
 * A GEnum bound as a native PHP enum, returned by GdkCrossingEvent::get_detail().
 * Ancestor / Inferior mean the pointer moved between a widget and one of its
 * descendants, Nonlinear that it went to an unrelated widget, the Virtual
 * variants that the event was synthesised for a widget in between, and Unknown
 * that GDK cannot say. The area is an outer box with an inner box, so moving
 * between them gives Ancestor/Inferior and entering from the outside gives
 * Nonlinear; the card cycles through the cases meanwhile.
 *
 *   bin/php-gtk4 examples/demo.php GdkNotifyType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkNotifyType',
    'how the widget left and the widget entered are related - ancestor, inferior, nonlinear',
    function (GtkWindow $win): GtkWidget {
        $explain = static fn(GdkNotifyType $detail): string => match ($detail) {
            GdkNotifyType::Ancestor => 'moved to an ancestor of this widget',
            GdkNotifyType::Virtual => 'passed through - the other widget is a descendant',
            GdkNotifyType::Inferior => 'moved to a descendant of this widget',
            GdkNotifyType::Nonlinear => 'moved to an unrelated widget',
            GdkNotifyType::NonlinearVirtual => 'passed through between unrelated widgets',
            GdkNotifyType::Unknown => 'GDK cannot tell',
        };

        $cases = GdkNotifyType::cases();
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

        $readout = Demo::label('<i>no crossing event yet</i>');
        $readout->set_halign(GtkAlign::Start);
        $seen = [];
        $watch = function (GtkWidget $widget, string $name) use ($readout, $explain, &$seen): void {
            $legacy = new GtkEventControllerLegacy();
            $legacy->connect('event', function (
                GtkEventControllerLegacy $self,
                GdkEvent $event,
            ) use (
                $name,
                $readout,
                $explain,
                &$seen,
            ): bool {
                if (!$event instanceof GdkCrossingEvent) {
                    return false;
                }
                $detail = $event->get_detail();
                $seen[$detail->name] = ($seen[$detail->name] ?? 0) + 1;
                $readout->set_markup(sprintf(
                    "<tt>%s on the %s\nget_detail  <b>%s</b> = GdkNotifyType::from(%d)\n            %s\n\n%s</tt>",
                    $event->get_event_type()->name,
                    $name,
                    $detail->name,
                    $detail->value,
                    $explain($detail),
                    implode("\n", array_map(
                        static fn(string $case, int $n): string => sprintf('%-18s %d', $case, $n),
                        array_keys($seen),
                        $seen,
                    )),
                ));
                return false;
            });
            $widget->add_controller($legacy);
        };

        // An inner box inside an outer one: crossings between them are Ancestor/Inferior.
        $inner = new GtkLabel('inner');
        $inner->add_css_class('card');
        $inner->set_margin_top(36);
        $inner->set_margin_bottom(36);
        $inner->set_margin_start(80);
        $inner->set_margin_end(80);
        $inner->set_vexpand(true);
        $outer = new GtkBox(GtkOrientation::Vertical, 0);
        $outer->add_css_class('card');
        $outer->set_vexpand(true);
        $outer->append($inner);
        $watch($outer, 'outer box');
        $watch($inner, 'inner box');

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($card);
        $page->append($outer);
        $page->append($readout);
        return $page;
    },
    620,
    500,
);
