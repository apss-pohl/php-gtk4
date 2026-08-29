<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkCheckButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkCheckButton - a label next to an indicator.
 *
 * On its own it is a check box; put several into one group with set_group() and
 * they become radio buttons, exactly one of them active. GTK 4 has no separate
 * GtkRadioButton any more. The page shows both: a column of check boxes and a
 * radio group, every `toggled` written to the label below, plus the third state
 * the API can express but the user cannot click into - inconsistent.
 *
 *   bin/php-gtk4 examples/demo.php GtkCheckButton
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkCheckButton',
    'check boxes, radio groups via set_group(), and the inconsistent state',
    function (GtkWindow $win): GtkWidget {
        $status = Demo::label();

        // Plain check boxes: independent of each other.
        $checks = new GtkBox(GtkOrientation::Vertical, 4);
        $toppings = [];
        foreach (['cheese', 'olives', 'basil'] as $name) {
            $check = GtkCheckButton::new_with_label($name);
            $toppings[$name] = $check;
            $checks->append($check);
        }
        $toppings['cheese']->set_active(true);

        // Radio buttons: the same class, grouped - the second parameter of
        // set_group() is any member of the group.
        $radios = new GtkBox(GtkOrientation::Vertical, 4);
        $sizes = [];
        $first = null;
        foreach (['small', 'medium', 'large'] as $name) {
            $radio = GtkCheckButton::new_with_label($name);
            $radio->set_group($first);
            $first ??= $radio;
            $sizes[$name] = $radio;
            $radios->append($radio);
        }
        $sizes['medium']->set_active(true);

        // new_with_mnemonic(): Alt+I toggles it, and it can be inconsistent.
        $mixed = GtkCheckButton::new_with_mnemonic('_Inconsistent (partial selection)');
        $mixed->set_inconsistent(true);

        $describe = function () use ($status, $toppings, $sizes, $mixed): void {
            $on = array_keys(array_filter(
                $toppings,
                static fn(GtkCheckButton $c): bool => $c->get_active(),
            ));
            $size = array_keys(array_filter(
                $sizes,
                static fn(GtkCheckButton $c): bool => $c->get_active(),
            ));
            $status->set_markup(sprintf(
                "<tt>checked      %s\nradio        %s\ninconsistent %s  active %s</tt>",
                $on === [] ? '(none)' : implode(', ', $on),
                $size === [] ? '(none)' : $size[0],
                $mixed->get_inconsistent() ? 'true ' : 'false',
                $mixed->get_active() ? 'true' : 'false',
            ));
        };

        // `toggled` fires on every state change - for a radio group that is two
        // emissions per click, the old one going off and the new one coming on.
        foreach ([...$toppings, ...$sizes, $mixed] as $button) {
            $button->connect('toggled', function (GtkCheckButton $self) use ($describe): void {
                $state = $self->get_active() ? 'on' : 'off';
                Demo::status(sprintf('%s toggled -> %s', $self->get_label() ?? '?', $state));
                $describe();
            });
        }

        // The script can toggle too, and clicking the inconsistent one resolves it.
        $step = 0;
        GLib::timeout_add(1500, function () use ($toppings, $sizes, $mixed, &$step): bool {
            match ($step++ % 3) {
                0 => $toppings['olives']->set_active(!$toppings['olives']->get_active()),
                1 => $sizes['large']->set_active(true),
                default => $mixed->set_inconsistent(!$mixed->get_inconsistent()),
            };
            return true;
        });

        $describe();

        $row = new GtkBox(GtkOrientation::Horizontal, 24);
        $row->set_halign(GtkAlign::Center);
        $row->append($checks);
        $row->append($radios);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($row);
        $mixed->set_halign(GtkAlign::Center);
        $page->append($mixed);
        $page->append($status);
        return $page;
    },
    520,
    340,
);
