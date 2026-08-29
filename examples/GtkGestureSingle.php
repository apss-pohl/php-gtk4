<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkCheckButton;
use Gtk4\GtkGestureClick;
use Gtk4\GtkGestureSingle;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkGestureSingle - the base of every one-pointer gesture.
 *
 * Click, drag, long-press, swipe and pan gestures all derive from it: it owns
 * the mouse button the gesture listens to (set_button(), 0 for any),
 * get_current_button() for the one currently pressed, set_exclusive() (only
 * pointer emulation or only real touch, never both) and set_touch_only().
 * GtkGestureSingle is a GTK-abstract class, so the page drives its API through
 * a GtkGestureClick on a large label; the button cycles set_button() through
 * 1, 2, 3 and 0, the checks toggle the two flags.
 *
 *   bin/php-gtk4 examples/demo.php GtkGestureSingle
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkGestureSingle',
    'the base of every one-pointer gesture: button, exclusive, touch-only',
    function (GtkWindow $win): GtkWidget {
        $face = Demo::label();
        $face->set_size_request(560, 220);
        $face->add_css_class('card');

        $click = new GtkGestureClick();
        $face->add_controller($click);

        $describe = static function (GtkGestureSingle $g, string $event = 'waiting for a press') use ($face): void {
            $face->set_markup(sprintf(
                "<b>%s</b>\n\n<tt>get_button()          %d%s\nget_current_button()  %d\n"
                . "get_exclusive()       %s\nget_touch_only()      %s</tt>",
                htmlspecialchars($event),
                $g->get_button(),
                $g->get_button() === 0 ? ' (any button)' : '',
                $g->get_current_button(),
                $g->get_exclusive() ? 'true' : 'false',
                $g->get_touch_only() ? 'true' : 'false',
            ));
        };

        $click->connect('pressed', function (
            GtkGestureClick $g,
            int $n_press,
            float $x,
            float $y,
        ) use ($describe): void {
            // get_current_button() is only non-zero while a button is down.
            $describe($g, sprintf('pressed with button %d at %.0f,%.0f', $g->get_current_button(), $x, $y));
        });
        $click->connect('released', function (GtkGestureClick $g) use ($describe): void {
            $describe($g, 'released');
        });

        $buttons = [1, 2, 3, 0];
        $step = 0;
        $cycle = GtkButton::new_with_label('set_button(2)');
        $cycle->connect('clicked', function (GtkButton $self) use ($click, $describe, $buttons, &$step): void {
            $step = ($step + 1) % count($buttons);
            $click->set_button($buttons[$step]);
            $self->set_label(sprintf('set_button(%d)', $buttons[($step + 1) % count($buttons)]));
            $describe($click, sprintf('now listening to button %d', $buttons[$step]));
            Demo::status(sprintf('set_button(%d)', $buttons[$step]));
        });

        $exclusive = GtkCheckButton::new_with_label('set_exclusive()');
        $exclusive->connect('toggled', function (GtkCheckButton $self) use ($click, $describe): void {
            $click->set_exclusive($self->get_active());
            $describe($click);
        });
        $touch = GtkCheckButton::new_with_label('set_touch_only()  - a mouse no longer counts');
        $touch->connect('toggled', function (GtkCheckButton $self) use ($click, $describe): void {
            $click->set_touch_only($self->get_active());
            $describe($click);
        });

        $describe($click);

        $controls = new GtkBox(GtkOrientation::Horizontal, 12);
        $controls->append($cycle);
        $controls->append($exclusive);
        $controls->append($touch);

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($face);
        $page->append($controls);
        return $page;
    },
    600,
    340,
);
