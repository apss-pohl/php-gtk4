<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkRevealer;
use Gtk4\GtkRevealerTransitionType;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkRevealer - animate a child in and out.
 *
 * set_reveal_child() is the request; the animation then takes
 * transition_duration milliseconds and child_revealed flips only when it has
 * finished. The page connects notify::child-revealed so the status shows the
 * two properties disagree while the slide is running.
 *
 *   bin/php-gtk4 examples/demo.php GtkRevealer
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkRevealer',
    'animate a child in and out - reveal_child asks, child_revealed answers',
    function (GtkWindow $win): GtkWidget {
        $revealer = new GtkRevealer();
        $revealer->set_transition_type(GtkRevealerTransitionType::SlideDown);
        $revealer->set_transition_duration(900);

        $panel = Demo::label("<b>the child</b>\n<small>slides down over 900 ms</small>");
        $panel->add_css_class('card');
        $panel->set_size_request(240, 100);
        $revealer->set_child($panel);

        $status = Demo::label();
        $describe = function () use ($revealer, $status): void {
            $status->set_markup(sprintf(
                "<tt>reveal_child        %s\nchild_revealed      %s\n"
                . "transition_type     %s\ntransition_duration %d</tt>",
                $revealer->get_reveal_child() ? 'true' : 'false',
                $revealer->get_child_revealed() ? 'true' : 'false',
                $revealer->get_transition_type()->name,
                $revealer->get_transition_duration(),
            ));
        };

        // child-revealed is a property: notify:: tells us when the animation ends.
        $revealer->connect('notify::child-revealed', function () use ($revealer, $describe): void {
            $describe();
            Demo::status('child_revealed ' . ($revealer->get_child_revealed() ? 'true' : 'false'));
        });

        $button = GtkButton::new_with_label('toggle reveal_child');
        $button->set_halign(GtkAlign::Center);
        $button->connect('clicked', function () use ($revealer, $describe): void {
            $revealer->set_reveal_child(!$revealer->get_reveal_child());
            $describe();
        });

        $describe();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($button);
        $page->append($revealer);
        return $page;
    },
    480,
    360,
);
