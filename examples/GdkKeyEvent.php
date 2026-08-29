<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkEvent;
use Gtk4\GdkKeyEvent;
use Gtk4\GdkKeyMatch;
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
 * Gtk4\GdkKeyEvent - a key press or release.
 *
 * GDK creates these; PHP only ever receives them (`new GdkKeyEvent()` throws).
 * A GtkEventControllerLegacy on the focused area gets every event, the handler
 * keeps the GdkKeyEvent ones and prints everything the class knows: keyval and
 * hardware keycode, keyboard layout and shift level, whether the key itself is
 * a modifier, which modifiers the layout consumed, the [keyval, modifiers] an
 * accelerator would need (get_match()) and how well the event matches Ctrl+A
 * (matches(): GdkKeyMatch::Exact, Partial or None). Click the area, then type.
 *
 *   bin/php-gtk4 examples/demo.php GdkKeyEvent
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkKeyEvent',
    'a key press or release - keyval, keycode, layout, level and accelerator matching',
    function (GtkWindow $win): GtkWidget {
        $area = new GtkLabel('type here (try Ctrl+A, Shift, a dead key)');
        $area->add_css_class('card');
        $area->set_focusable(true);
        $area->set_vexpand(true);
        $area->set_hexpand(true);

        $readout = Demo::label('<i>waiting for a key</i>');
        $readout->set_halign(GtkAlign::Start);

        $modifiers = static function (int $state): string {
            $held = [];
            $masks = [
                'Shift' => GdkModifierType::SHIFT_MASK,
                'Lock' => GdkModifierType::LOCK_MASK,
                'Ctrl' => GdkModifierType::CONTROL_MASK,
                'Alt' => GdkModifierType::ALT_MASK,
                'Super' => GdkModifierType::SUPER_MASK,
            ];
            foreach ($masks as $name => $mask) {
                if (($state & $mask) !== 0) {
                    $held[] = $name;
                }
            }
            return $held === [] ? 'none' : implode('+', $held);
        };
        // Keyvals for Latin-1 are the code point, other Unicode keyvals carry a 0x01000000 flag.
        $glyph = static function (int $keyval): string {
            $code = $keyval < 0x100 ? $keyval : (($keyval & 0x01000000) !== 0 ? $keyval & 0x00ffffff : 0);
            return $code >= 0x20 ? htmlspecialchars(mb_chr($code) ?: '') : '';
        };

        $legacy = new GtkEventControllerLegacy();
        $legacy->connect('event', function (
            GtkEventControllerLegacy $self,
            GdkEvent $event,
        ) use (
            $readout,
            $modifiers,
            $glyph,
        ): bool {
            if (!$event instanceof GdkKeyEvent) {
                return false;
            }
            $match = $event->get_match();
            $readout->set_markup(sprintf(
                "<tt>event_type          %s\nget_keyval          0x%x %s\nget_keycode         %d\n"
                . "get_layout          %d\nget_level           %d\nis_modifier         %s\n"
                . "modifier_state      %s\nconsumed_modifiers  %s\nget_match           %s\n"
                . 'matches(a, Ctrl)    %s</tt>',
                $event->get_event_type()->name,
                $event->get_keyval(),
                $glyph($event->get_keyval()),
                $event->get_keycode(),
                $event->get_layout(),
                $event->get_level(),
                $event->is_modifier() ? 'true' : 'false',
                $modifiers($event->get_modifier_state()),
                $modifiers($event->get_consumed_modifiers()),
                $match === null ? 'null' : sprintf('[0x%x, %s]', $match[0], $modifiers($match[1])),
                match ($event->matches(0x61, GdkModifierType::CONTROL_MASK)) {
                    GdkKeyMatch::Exact => 'Exact',
                    GdkKeyMatch::Partial => 'Partial',
                    GdkKeyMatch::None => 'None',
                },
            ));
            return false;
        });
        $area->add_controller($legacy);

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
    480,
);
