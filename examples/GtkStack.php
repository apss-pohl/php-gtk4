<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkStack;
use Gtk4\GtkStackTransitionType;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkStack - one child visible at a time, the rest waiting behind it.
 *
 * A stack holds any number of pages and shows exactly one; switching between
 * them animates with a transition. Three titled pages are added here, a timer
 * walks set_visible_child_name() through them with a slide, and the status
 * line reports get_visible_child_name() and get_transition_running().
 *
 *   bin/php-gtk4 examples/demo.php GtkStack
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkStack',
    'one child visible at a time, switched with a transition',
    function (GtkWindow $win): GtkWidget {
        $stack = new GtkStack();
        $stack->set_vexpand(true);
        $stack->set_transition_type(GtkStackTransitionType::SlideLeftRight);
        $stack->set_transition_duration(600);

        // add_titled() names the page (for set_visible_child_name) and titles it
        // (for a GtkStackSwitcher); add_named() would skip the title.
        $names = ['first', 'second', 'third'];
        foreach ($names as $i => $name) {
            $face = Demo::label(sprintf(
                "<span size='xx-large'><b>%s</b></span>\n<small>add_titled(child, '%s', '%s')</small>",
                ucfirst($name),
                $name,
                ucfirst($name),
            ));
            $face->add_css_class('card');
            $face->set_hexpand(true);
            $face->set_vexpand(true);
            $stack->add_titled($face, $name, ucfirst($name));
        }

        $status = Demo::label();
        $status->set_halign(GtkAlign::Start);
        $describe = function () use ($stack, $status): void {
            $status->set_markup(sprintf(
                "<tt>visible_child_name    %s\ntransition_type       %s\n"
                . "transition_duration   %d ms\ntransition_running    %s</tt>",
                $stack->get_visible_child_name() ?? 'null',
                $stack->get_transition_type()->name,
                $stack->get_transition_duration(),
                $stack->get_transition_running() ? 'true' : 'false',
            ));
        };

        // Advance every 1.5 s; the transition is still running when we read it
        // right afterwards, so the status also polls while it animates.
        $step = 0;
        GLib::timeout_add(1500, function () use ($stack, $names, $describe, &$step): bool {
            $step++;
            $stack->set_visible_child_name($names[$step % count($names)]);
            $describe();
            Demo::status('visible child: ' . ($stack->get_visible_child_name() ?? 'null'));
            return true;
        });
        GLib::timeout_add(100, function () use ($describe): bool {
            $describe();
            return true;
        });
        $describe();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($status);
        $page->append($stack);
        return $page;
    },
    520,
    360,
);
