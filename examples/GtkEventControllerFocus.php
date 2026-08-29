<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkEventControllerFocus;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkEventControllerFocus - keyboard focus arriving and leaving.
 *
 * `enter` fires when focus moves into the widget (or any descendant), `leave`
 * when it moves out. The two getters tell the difference: `is_focus()` is true
 * only when the widget itself holds focus, `contains_focus()` also when a child
 * does. The pad is a box with two focusable children and the controller on
 * the box, so Tab moves focus between the buttons without ever leaving the box.
 *
 *   bin/php-gtk4 examples/demo.php GtkEventControllerFocus
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkEventControllerFocus',
    'keyboard focus enter / leave, is_focus versus contains_focus',
    function (GtkWindow $win): GtkWidget {
        $pad = new GtkBox(GtkOrientation::Horizontal, 12);
        $pad->add_css_class('card');
        $pad->set_size_request(420, 120);
        $pad->set_focusable(true);
        $pad->set_can_focus(true);
        $pad->set_halign(GtkAlign::Center);

        $hint = new GtkLabel('Tab or click into here');
        $pad->append($hint);
        $left = GtkButton::new_with_label('child A');
        $right = GtkButton::new_with_label('child B');
        $pad->append($left);
        $pad->append($right);

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);

        $controller = new GtkEventControllerFocus();
        $pad->add_controller($controller);

        $events = 0;
        $last = '-';
        $show = function () use ($readout, $controller, &$events, &$last): void {
            $readout->set_markup(sprintf(
                "<tt>last signal     %s\nsignals seen    %d\nis_focus        %s\ncontains_focus  %s</tt>",
                $last,
                $events,
                $controller->is_focus() ? 'true' : 'false',
                $controller->contains_focus() ? 'true' : 'false',
            ));
        };

        $controller->connect('enter', function (GtkEventControllerFocus $c) use ($hint, $show, &$events, &$last): void {
            $events++;
            $last = 'enter';
            $hint->set_text('focus is inside');
            Demo::status('focus entered the pad');
            $show();
        });
        $controller->connect('leave', function (GtkEventControllerFocus $c) use ($hint, $show, &$events, &$last): void {
            $events++;
            $last = 'leave';
            $hint->set_text('focus left');
            Demo::status('focus left the pad');
            $show();
        });
        // Focus moving between the children changes is_focus but not contains_focus.
        foreach ([$left, $right] as $button) {
            $button->connect('clicked', $show);
        }

        $show();
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($readout);
        $page->append($pad);
        return $page;
    },
    600,
    320,
);
