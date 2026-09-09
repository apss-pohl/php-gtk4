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
 * Gtk4\GdkKeyMatch - how well a key event matches an accelerator.
 *
 * A GEnum bound as a native PHP enum, returned by GdkKeyEvent::matches().
 * Exact means keyval and modifiers agree; Partial that the key would match on
 * another layout or shift level (Ctrl+Shift+A typed as Ctrl+Shift+a); None
 * that it is a different key. The card cycles the three cases; the readout
 * tests every key you type against three accelerators - a, Ctrl+A and
 * Ctrl+Shift+A - and shows what matches() says for each. Click the area, then
 * type.
 *
 *   bin/php-gtk4 examples/demo.php GdkKeyMatch
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkKeyMatch',
    'how well a key event matches an accelerator - exact, partial or not at all',
    function (GtkWindow $win): GtkWidget {
        $explain = static fn(GdkKeyMatch $match): string => match ($match) {
            GdkKeyMatch::None => 'a different key',
            GdkKeyMatch::Partial => 'same key on another layout or level',
            GdkKeyMatch::Exact => 'keyval and modifiers agree',
        };

        $cases = GdkKeyMatch::cases();
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

        // The accelerators every key press is tested against (0x61 = GDK_KEY_a).
        $accelerators = [
            'a' => [0x61, 0],
            'Ctrl+a' => [0x61, GdkModifierType::CONTROL_MASK],
            'Ctrl+Shift+a' => [0x61, GdkModifierType::CONTROL_MASK | GdkModifierType::SHIFT_MASK],
        ];

        $area = new GtkLabel('type here: a, A, Ctrl+A, Ctrl+Shift+A, something else');
        $area->add_css_class('card');
        $area->set_focusable(true);
        $area->set_vexpand(true);
        $area->set_hexpand(true);
        $readout = Demo::label('<i>no key event yet</i>');
        $readout->set_halign(GtkAlign::Start);
        $legacy = new GtkEventControllerLegacy();
        $legacy->connect('event', function (
            GtkEventControllerLegacy $self,
            GdkEvent $event,
        ) use (
            $readout,
            $accelerators,
        ): bool {
            if (!$event instanceof GdkKeyEvent || $event->is_modifier()) {
                return false;
            }
            $rows = [];
            foreach ($accelerators as $label => [$keyval, $mods]) {
                $match = $event->matches($keyval, $mods);
                $rows[] = sprintf(
                    '%-14s %s',
                    $label,
                    $match === GdkKeyMatch::None ? $match->name : sprintf('<b>%s</b>', $match->name),
                );
            }
            $readout->set_markup(sprintf(
                "<tt>%s keyval 0x%x\n\n%s</tt>",
                $event->get_event_type()->name,
                $event->get_keyval(),
                implode("\n", $rows),
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
    460,
);
