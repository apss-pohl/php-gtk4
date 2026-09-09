<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkStack;
use Gtk4\GtkStackSwitcher;
use Gtk4\GtkStackTransitionType;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkStackSwitcher - a row of toggle buttons, one per page of a stack.
 *
 * Hand it a stack with set_stack() and it builds a button per titled page,
 * follows the visible child and switches it when clicked - no code of your
 * own in between. Click the buttons; the label underneath reports what the
 * stack shows.
 *
 *   bin/php-gtk4 examples/demo.php GtkStackSwitcher
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkStackSwitcher',
    'a row of toggle buttons, one per titled page of a stack',
    function (GtkWindow $win): GtkWidget {
        $stack = new GtkStack();
        $stack->set_vexpand(true);
        $stack->set_transition_type(GtkStackTransitionType::Crossfade);
        foreach (['Red' => '#c0392b', 'Green' => '#27ae60', 'Blue' => '#2980b9'] as $title => $colour) {
            $face = Demo::label(sprintf(
                "<span size='xx-large' foreground='%s'><b>%s</b></span>\n"
                . "<small>the button above is this page's title</small>",
                $colour,
                $title,
            ));
            $face->add_css_class('card');
            $face->set_vexpand(true);
            $stack->add_titled($face, strtolower($title), $title);
        }

        // set_stack() is the whole API: the switcher reads the pages' titles itself.
        $switcher = new GtkStackSwitcher();
        $switcher->set_stack($stack);
        $switcher->set_halign(GtkAlign::Center);

        $report = function () use ($stack, $switcher): void {
            Demo::status(sprintf(
                'get_stack() === stack: %s; visible child: %s',
                $switcher->get_stack() === $stack ? 'yes' : 'no',
                $stack->get_visible_child_name() ?? 'null',
            ));
        };
        $stack->connect('notify::visible-child', $report);
        $report();

        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $box->append($switcher);
        $box->append($stack);
        $box->append(Demo::label('<small>click a button - the switcher drives the stack, nothing else does</small>'));
        return $box;
    },
    480,
    340,
);
