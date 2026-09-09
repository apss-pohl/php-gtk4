<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkNotebook;
use Gtk4\GtkPackType;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPackType - which end of a row something is packed at.
 *
 * A two-case GEnum (Start, End) bound as a native PHP enum. GtkNotebook uses it
 * for set_action_widget(): a widget that sits in the tab row before the tabs
 * (Start) or after them (End). One button is packed at each end; a timer
 * shows one at a time so the enum case is visible, and clicking a button
 * swaps them too.
 *
 *   bin/php-gtk4 examples/demo.php GtkPackType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPackType',
    'Start or End - which end of the tab row an action widget sits at',
    function (GtkWindow $win): GtkWidget {
        $notebook = new GtkNotebook();
        $notebook->set_vexpand(true);
        foreach (['One', 'Two', 'Three'] as $title) {
            $notebook->append_page(
                Demo::label(
                    "<span size='xx-large'><b>$title</b></span>\n<small>the action widget is in the tab row</small>",
                ),
                new GtkLabel($title),
            );
        }

        // cases() is the ordinary PHP enum API; one action widget per case.
        $cases = GtkPackType::cases();
        /** @var array<string, GtkButton> $buttons */
        $buttons = [];
        foreach ($cases as $type) {
            $button = GtkButton::new_with_label(sprintf('GtkPackType::%s (%d)', $type->name, $type->value));
            $notebook->set_action_widget($button, $type);   // typed: takes the enum
            $buttons[$type->name] = $button;
        }

        $step = 0;
        $place = function () use ($notebook, $buttons, $cases, &$step): void {
            $current = $cases[$step % count($cases)];
            foreach ($cases as $type) {
                $buttons[$type->name]->set_visible($type === $current);
            }
            Demo::status(sprintf(
                'showing %s; get_action_widget(Start) is %s, get_action_widget(End) is %s',
                $current->name,
                $notebook->get_action_widget(GtkPackType::Start) === $buttons['Start'] ? 'the Start button' : 'null',
                $notebook->get_action_widget(GtkPackType::End) === $buttons['End'] ? 'the End button' : 'null',
            ));
        };
        foreach ($buttons as $button) {
            $button->connect('clicked', function () use ($place, &$step): void {
                $step++;
                $place();
            });
        }
        $place();
        GLib::timeout_add(1500, function () use ($place, &$step): bool {
            $step++;
            $place();
            return true;
        });

        return $notebook;
    },
    520,
    320,
);
