<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkEventSequenceState;
use Gtk4\GtkGestureClick;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkEventSequenceState - what a gesture has decided about an event sequence.
 *
 * A GEnum bound as a native PHP enum. Every touch or pointer sequence starts
 * as None; a gesture that calls set_state(Claimed) keeps it for itself (other
 * gestures on the same widget are cancelled), Denied gives it up. The label
 * carries a GtkGestureClick whose `pressed` handler applies the case chosen
 * with the button: claim it and `released` still arrives, deny it and the
 * gesture is reset before the release.
 *
 *   bin/php-gtk4 examples/demo.php GtkEventSequenceState
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkEventSequenceState',
    'what a gesture has decided about an event sequence',
    function (GtkWindow $win): GtkWidget {
        $face = Demo::label();
        $face->set_size_request(520, 220);
        $face->add_css_class('card');

        $cases = GtkEventSequenceState::cases();
        $step = 0;
        $log = [];

        $show = static function (string $line = '') use ($face, $cases, &$step, &$log): void {
            if ($line !== '') {
                $log[] = $line;
                $log = array_slice($log, -4);
            }
            $face->set_markup(sprintf(
                "<b>on press: set_state(GtkEventSequenceState::%s)</b> <small>(%d)</small>\n\n"
                . "<tt>%s</tt>\n\n<small>%s</small>",
                $cases[$step]->name,
                $cases[$step]->value,
                $log === [] ? 'click me' : htmlspecialchars(implode("\n", $log)),
                implode(' · ', array_map(static fn(GtkEventSequenceState $s): string => $s->name, $cases)),
            ));
        };

        $click = new GtkGestureClick();
        $face->add_controller($click);
        $click->connect('pressed', function (GtkGestureClick $g, int $n_press) use ($show, $cases, &$step): void {
            // set_state() returns whether the transition was allowed (None -> Claimed/Denied are).
            $ok = $g->set_state($cases[$step]);
            $show(sprintf(
                'pressed(%d)   set_state(%-7s) -> %s',
                $n_press,
                $cases[$step]->name,
                $ok ? 'true' : 'false',
            ));
        });
        $click->connect('released', function (GtkGestureClick $g, int $n_press) use ($show): void {
            $show(sprintf('released(%d)', $n_press));
        });
        // A denied sequence never reaches released: the gesture is reset instead, so the
        // log shows the press alone. (The `cancel` signal carries a GdkEventSequence,
        // which is not bound.)

        $next = GtkButton::new_with_label('next case');
        $next->connect('clicked', function () use ($show, $cases, &$step): void {
            $step = ($step + 1) % count($cases);
            $show();
            Demo::status(sprintf('%s = GtkEventSequenceState::from(%d)', $cases[$step]->name, $cases[$step]->value));
        });

        $show();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($face);
        $page->append($next);
        return $page;
    },
    560,
    340,
);
