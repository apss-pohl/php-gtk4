<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkStack;
use Gtk4\GtkStackSidebar;
use Gtk4\GtkStackTransitionType;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkStackSidebar - a vertical list of a stack's page titles.
 *
 * The sidebar cousin of GtkStackSwitcher: set_stack() and it lists every titled
 * page in a column, highlighting the visible one and switching on click. The
 * page puts a sidebar to the left of a four-page stack.
 *
 *   bin/php-gtk4 examples/demo.php GtkStackSidebar
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkStackSidebar',
    'a vertical list of page titles beside a stack',
    function (GtkWindow $win): GtkWidget {
        $stack = new GtkStack();
        $stack->set_hexpand(true);
        $stack->set_vexpand(true);
        $stack->set_transition_type(GtkStackTransitionType::SlideUpDown);
        foreach (['General', 'Appearance', 'Keyboard', 'About'] as $i => $title) {
            $face = Demo::label(sprintf(
                "<span size='xx-large'><b>%s</b></span>\n<small>page %d of the stack</small>",
                $title,
                $i + 1,
            ));
            $face->add_css_class('card');
            $face->set_vexpand(true);
            $stack->add_titled($face, strtolower($title), $title);
        }

        $sidebar = new GtkStackSidebar();
        $sidebar->set_stack($stack);
        $sidebar->set_size_request(150, -1);

        $report = function () use ($stack, $sidebar): void {
            Demo::status(sprintf(
                'get_stack() === stack: %s; visible child: %s',
                $sidebar->get_stack() === $stack ? 'yes' : 'no',
                $stack->get_visible_child_name() ?? 'null',
            ));
        };
        $stack->connect('notify::visible-child', $report);
        $report();

        $box = new GtkBox(GtkOrientation::Horizontal, 12);
        $box->append($sidebar);
        $box->append($stack);
        return $box;
    },
    560,
    340,
);
