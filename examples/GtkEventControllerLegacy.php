<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkButtonEvent;
use Gtk4\GdkEvent;
use Gtk4\GdkKeyEvent;
use Gtk4\GdkScrollEvent;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkEventControllerLegacy - the raw GdkEvent stream, one signal for all.
 *
 * The other controllers pre-digest events into typed signals; this one hands
 * every GdkEvent to a single `event` handler and lets you sort them out. The
 * event arrives as its real subclass (GdkKeyEvent, GdkButtonEvent, GdkScrollEvent,
 * ...) so `instanceof` picks the extra getters, and the common ones -
 * get_event_type(), get_time(), get_modifier_state(), get_position() - are on
 * every event. Return true to stop the event there. Do anything over the pad.
 *
 *   bin/php-gtk4 examples/demo.php GtkEventControllerLegacy
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkEventControllerLegacy',
    'every raw GdkEvent through one signal - typed subclass, position, time, modifiers',
    function (GtkWindow $win): GtkWidget {
        $pad = new GtkLabel('click, type, scroll or move here');
        $pad->add_css_class('card');
        $pad->set_size_request(420, 160);
        $pad->set_focusable(true);
        $pad->set_can_focus(true);

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $counts = [];
        $last = '-';
        $show = function () use ($readout, &$counts, &$last): void {
            arsort($counts);
            $rows = array_map(
                static fn(string $type, int $n): string => sprintf('%-16s %d', $type, $n),
                array_keys($counts),
                $counts,
            );
            $readout->set_markup(sprintf(
                "<tt>%s\n\n%s</tt>",
                htmlspecialchars($last),
                $rows === [] ? 'no events yet' : htmlspecialchars(implode("\n", $rows)),
            ));
        };

        $controller = new GtkEventControllerLegacy();
        $pad->add_controller($controller);
        $onEvent = function (GtkEventControllerLegacy $c, GdkEvent $event) use ($pad, $show, &$counts, &$last): bool {
            $type = $event->get_event_type()->name;
            $counts[$type] = ($counts[$type] ?? 0) + 1;

            $pos = $event->get_position();
            $detail = match (true) {
                $event instanceof GdkKeyEvent => sprintf('keyval 0x%04x', $event->get_keyval()),
                $event instanceof GdkButtonEvent => sprintf('button %d', $event->get_button()),
                $event instanceof GdkScrollEvent => sprintf('deltas %s', implode(' ', array_map(
                    static fn(float $d): string => sprintf('%+.2f', $d),
                    $event->get_deltas(),
                ))),
                default => '',
            };
            $last = sprintf(
                "%s\ntype      %s\ntime      %d\nstate     0x%x\nposition  %s\n%s",
                $event::class,
                $type,
                $event->get_time(),
                $event->get_modifier_state(),
                $pos === null ? '(none)' : sprintf('%.0f, %.0f', $pos[0], $pos[1]),
                $detail,
            );
            if ($type !== 'MotionNotify') {
                $pad->set_text($type . ($detail === '' ? '' : ' - ' . $detail));
                Demo::status($type);
            }
            $show();
            return false;   // observe only, let the widget's other controllers run
        };
        $controller->connect('event', $onEvent);

        $show();
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($readout);
        $page->append($pad);
        return $page;
    },
    600,
    460,
);
