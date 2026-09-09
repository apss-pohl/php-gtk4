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
 * Gtk4\GtkStackTransitionType - how a GtkStack animates from one page to the next.
 *
 * A GEnum bound as a native PHP enum. The stack below flips between two pages
 * every second and, each time, moves on to the next case: crossfade, the four
 * slides, over/under in every direction, rotations. Watch the animation change.
 *
 *   bin/php-gtk4 examples/demo.php GtkStackTransitionType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkStackTransitionType',
    'how a stack animates from one page to the next - every case in turn',
    function (GtkWindow $win): GtkWidget {
        $stack = new GtkStack();
        $stack->set_vexpand(true);
        $stack->set_transition_duration(700);
        foreach (['A' => '#8e44ad', 'B' => '#f39c12'] as $name => $colour) {
            $face = Demo::label("<span size='400%' foreground='$colour'><b>$name</b></span>");
            $face->add_css_class('card');
            $face->set_vexpand(true);
            $stack->add_named($face, $name);
        }

        $cases = GtkStackTransitionType::cases();
        $status = Demo::label();
        $status->set_halign(GtkAlign::Start);
        $step = 0;
        $show = function () use ($stack, $status, $cases, &$step): void {
            $type = $cases[$step % count($cases)];
            // The typed setter takes the enum; set_visible_child_full() can pick
            // a transition for one switch only.
            $stack->set_transition_type($type);
            $stack->set_visible_child_name($stack->get_visible_child_name() === 'A' ? 'B' : 'A');
            $status->set_markup(sprintf(
                '<tt>%-18s = %2d   (%d of %d)</tt>',
                $type->name,
                $type->value,
                $step % count($cases) + 1,
                count($cases),
            ));
            Demo::status(sprintf('%s = GtkStackTransitionType::from(%d)', $type->name, $type->value));
        };

        $show();
        GLib::timeout_add(1000, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $box->append($status);
        $box->append($stack);
        return $box;
    },
    480,
    360,
);
