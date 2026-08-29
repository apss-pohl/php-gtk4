<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkButtonEvent;
use Gtk4\GdkModifierType;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkGestureClick;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkButtonEvent - a mouse button press or release.
 *
 * This page takes the other route to an event: a GtkGestureClick (button 0 =
 * any button) reports presses through its `pressed` signal, and inside the
 * handler get_current_event() on the controller returns the GdkButtonEvent
 * that triggered it. Besides its own get_button() the readout shows the base
 * class getters that matter for buttons - get_position(), get_modifier_state()
 * and triggers_context_menu(), which is true for the right button (and Ctrl+click
 * on macOS). Click the area with each button.
 *
 *   bin/php-gtk4 examples/demo.php GdkButtonEvent
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkButtonEvent',
    'a mouse button press or release - which button, where, with what held',
    function (GtkWindow $win): GtkWidget {
        $area = new GtkLabel('click here with every button you have');
        $area->add_css_class('card');
        $area->set_vexpand(true);
        $area->set_hexpand(true);

        $readout = Demo::label('<i>waiting for a click</i>');
        $readout->set_halign(GtkAlign::Start);

        $modifiers = static function (int $state): string {
            $held = [];
            $masks = [
                'Shift' => GdkModifierType::SHIFT_MASK,
                'Ctrl' => GdkModifierType::CONTROL_MASK,
                'Alt' => GdkModifierType::ALT_MASK,
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

        $click = new GtkGestureClick();
        $click->set_button(0);   // 0 = react to any button, not just the primary one
        $names = [1 => 'primary', 2 => 'middle', 3 => 'secondary'];
        $click->connect('pressed', function (
            GtkGestureClick $self,
            int $n_press,
            float $x,
            float $y,
        ) use (
            $readout,
            $modifiers,
            $names,
        ): void {
            $event = $self->get_current_event();
            if (!$event instanceof GdkButtonEvent) {
                return;
            }
            $position = $event->get_position();
            $readout->set_markup(sprintf(
                "<tt>event_type            %s\nget_button            %d (%s)\nn_press               %d\n"
                . "get_position          %s\nget_modifier_state    %s\ntriggers_context_menu %s\n"
                . 'get_time              %d ms</tt>',
                $event->get_event_type()->name,
                $event->get_button(),
                $names[$event->get_button()] ?? 'extra',
                $n_press,
                $position === null ? 'null' : sprintf('%.0f, %.0f', $position[0], $position[1]),
                $modifiers($event->get_modifier_state()),
                $event->triggers_context_menu() ? 'true' : 'false',
                $event->get_time(),
            ));
        });
        $area->add_controller($click);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($area);
        $page->append($readout);
        return $page;
    },
    600,
    440,
);
