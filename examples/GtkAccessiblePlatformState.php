<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAccessiblePlatformState;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkEntry;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkAccessiblePlatformState - the three states an accessibility backend
 * asks a widget about: is it Focusable, is it Focused, is it Active.
 *
 * GtkEntry answers through delegate_get_accessible_platform_state(), which it
 * forwards to the GtkText it wraps. The two entries below take turns owning the
 * keyboard focus - the button moves it, a timer moves it too - and the readout
 * queries every case on both entries each time. Making an entry unfocusable
 * flips Focusable off and takes Focused with it.
 *
 *   bin/php-gtk4 examples/demo.php GtkAccessiblePlatformState
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkAccessiblePlatformState',
    'Focusable / Focused / Active - what an entry tells the accessibility backend',
    function (GtkWindow $win): GtkWidget {
        $first = new GtkEntry();
        $first->set_text('first entry');
        $second = new GtkEntry();
        $second->set_text('second entry');
        $entries = ['first' => $first, 'second' => $second];

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $show = function () use ($entries, $readout): void {
            $rows = [];
            foreach ($entries as $name => $entry) {
                $states = array_map(
                    static fn(GtkAccessiblePlatformState $s): string => sprintf(
                        '%s %-9s',
                        $entry->delegate_get_accessible_platform_state($s) ? '●' : '○',
                        $s->name,
                    ),
                    GtkAccessiblePlatformState::cases(),
                );
                $rows[] = sprintf('%-7s %s', $name, implode('  ', $states));
            }
            $cases = array_map(
                static fn(GtkAccessiblePlatformState $s): string => $s->name . '=' . $s->value,
                GtkAccessiblePlatformState::cases(),
            );
            $readout->set_markup(sprintf(
                "<tt>%s</tt>\n\n<small>%s</small>",
                implode("\n", $rows),
                implode(' · ', $cases),
            ));
        };

        // Focus moves: the window's focus widget decides Focused.
        $flip = function () use ($first, $second, $show): void {
            ($first->has_focus() ? $second : $first)->grab_focus();
            $show();
            Demo::status(($first->has_focus() ? 'first' : 'second') . ' entry has the focus');
        };
        $button = GtkButton::new_with_label('move the focus');
        $button->set_halign(GtkAlign::Center);
        $button->connect('clicked', $flip);

        $toggle = GtkButton::new_with_label('second: focusable on/off');
        $toggle->set_halign(GtkAlign::Center);
        $toggle->connect('clicked', function () use ($second, $show): void {
            $second->set_focusable(!$second->get_focusable());
            $show();
        });

        foreach ($entries as $entry) {
            $entry->connect('notify::has-focus', $show);
        }
        $first->grab_focus();
        $show();
        GLib::timeout_add(1500, function () use ($flip): bool {
            $flip();
            return true;
        });

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $buttons->set_halign(GtkAlign::Center);
        $buttons->append($button);
        $buttons->append($toggle);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($first);
        $page->append($second);
        $page->append($buttons);
        $page->append($readout);
        return $page;
    },
    560,
    320,
);
