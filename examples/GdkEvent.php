<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkEvent;
use Gtk4\GdkModifierType;
use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEventControllerLegacy;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkEvent - the base class of everything the windowing system reports.
 *
 * Events cannot be constructed from PHP (`new GdkEvent()` throws): GDK creates
 * them and GTK hands them to event controllers. GtkEventControllerLegacy is the
 * one controller that passes every raw event through its `event` signal, so the
 * grey area below gets a wrapper for each key, button, motion, scroll, crossing
 * and focus event and prints what the *base* class knows about all of them:
 * get_event_type(), get_time(), get_modifier_state(), get_position(),
 * get_pointer_emulated(), triggers_context_menu() and get_display(). The
 * subclass pages (GdkKeyEvent, GdkButtonEvent, ...) add the type-specific
 * getters. Move the mouse over the area, click it and type into it.
 *
 *   bin/php-gtk4 examples/demo.php GdkEvent
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkEvent',
    'the base class of everything the windowing system reports',
    function (GtkWindow $win): GtkWidget {
        $area = new GtkLabel('move, click, scroll and type here');
        $area->add_css_class('card');
        $area->set_focusable(true);
        $area->set_vexpand(true);
        $area->set_hexpand(true);

        $readout = Demo::label('<i>waiting for the first event</i>');
        $readout->set_halign(GtkAlign::Start);

        $modifiers = static function (int $state): string {
            $held = [];
            $masks = [
                'Shift' => GdkModifierType::SHIFT_MASK,
                'Ctrl' => GdkModifierType::CONTROL_MASK,
                'Alt' => GdkModifierType::ALT_MASK,
                'Super' => GdkModifierType::SUPER_MASK,
                'Button1' => GdkModifierType::BUTTON1_MASK,
                'Button2' => GdkModifierType::BUTTON2_MASK,
                'Button3' => GdkModifierType::BUTTON3_MASK,
            ];
            foreach ($masks as $name => $mask) {
                if (($state & $mask) !== 0) {
                    $held[] = $name;
                }
            }
            return $held === [] ? 'none' : implode('+', $held);
        };

        // Count what arrives per type: the class of the handle already says which subclass it is.
        $counts = [];
        $legacy = new GtkEventControllerLegacy();
        $legacy->connect('event', function (
            GtkEventControllerLegacy $self,
            GdkEvent $event,
        ) use (
            $readout,
            $modifiers,
            &$counts,
        ): bool {
            $type = $event->get_event_type()->name;
            $counts[$type] = ($counts[$type] ?? 0) + 1;
            $position = $event->get_position();
            $display = $event->get_display();
            $readout->set_markup(sprintf(
                "<tt>class            %s\nget_event_type   %s\nget_time         %d ms\n"
                . "get_modifier_state  %s\nget_position     %s\npointer_emulated %s\n"
                . "context_menu     %s\nget_display      %s\n\n%s</tt>",
                htmlspecialchars(new \ReflectionClass($event)->getShortName()),
                $type,
                $event->get_time(),
                $modifiers($event->get_modifier_state()),
                $position === null ? 'null' : sprintf('%.0f, %.0f', $position[0], $position[1]),
                $event->get_pointer_emulated() ? 'true' : 'false',
                $event->triggers_context_menu() ? 'true' : 'false',
                $display === null ? 'null' : htmlspecialchars($display->get_name()),
                implode("\n", array_map(
                    static fn(string $name, int $n): string => sprintf('%-16s %d', $name, $n),
                    array_keys($counts),
                    $counts,
                )),
            ));
            return false;   // not handled - let the event reach the ordinary controllers too
        });
        $area->add_controller($legacy);

        // Key events only arrive once the area has the keyboard focus.
        GLib::idle_add(function () use ($area): bool {
            $area->grab_focus();
            return false;
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($area);
        $page->append($readout);
        return $page;
    },
    600,
    520,
);
