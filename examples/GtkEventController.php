<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkEventControllerKey;
use Gtk4\GtkLabel;
use Gtk4\GtkOrientation;
use Gtk4\GtkPropagationLimit;
use Gtk4\GtkPropagationPhase;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkEventController - the base of every input controller.
 *
 * Abstract in GTK: `new GtkEventController()` refuses, you always attach one
 * of the subclasses (here a GtkEventControllerKey). What the base class owns is
 * the plumbing shared by all of them - a name for debugging, the propagation
 * phase (Capture on the way down, Target, Bubble on the way up) and limit
 * (whether events from other native surfaces are seen), the widget it hangs on,
 * and, while a handler runs, the current event with its time and modifier state.
 * `reset()` throws away any state the controller accumulated. Type into the pad
 * and click the buttons.
 *
 *   bin/php-gtk4 examples/demo.php GtkEventController
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkEventController',
    'the abstract base - name, propagation phase/limit, widget and the current event',
    function (GtkWindow $win): GtkWidget {
        $pad = new GtkLabel('click here, then press keys');
        $pad->add_css_class('card');
        $pad->set_size_request(420, 120);
        $pad->set_focusable(true);
        $pad->set_can_focus(true);

        $controller = new GtkEventControllerKey();
        $controller->set_name('demo');
        $pad->add_controller($controller);

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $inside = 'no event yet';
        $show = function () use ($readout, $controller, &$inside): void {
            // Outside a handler there is no current event: null, time 0, state 0.
            $event = $controller->get_current_event();
            $readout->set_markup(sprintf(
                "<tt>name               %s\npropagation_phase  %s\npropagation_limit  %s\nwidget             %s\n"
                . "current_event      %s\ncurrent_time       %d\ncurrent_state      0x%x\n\n"
                . 'inside handler:    %s</tt>',
                $controller->get_name() ?? '(null)',
                $controller->get_propagation_phase()->name,
                $controller->get_propagation_limit()->name,
                get_debug_type($controller->get_widget()),   // null until add_controller()
                $event === null ? 'null' : $event::class,
                $controller->get_current_event_time(),
                $controller->get_current_event_state(),
                htmlspecialchars($inside),
            ));
        };

        $onKey = function (GtkEventControllerKey $c, int $keyval) use ($pad, $show, &$inside): bool {
            $event = $c->get_current_event();
            $inside = sprintf(
                '%s time %d state 0x%x keyval 0x%x',
                $event === null ? 'null' : $event::class,
                $c->get_current_event_time(),
                $c->get_current_event_state(),
                $keyval,
            );
            $pad->set_text(sprintf('keyval 0x%x', $keyval));
            Demo::status('key-pressed via ' . $c->get_name());
            $show();
            return false;
        };
        $controller->connect('key-pressed', $onKey);

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $buttons->set_halign(GtkAlign::Center);
        $phase = GtkButton::new_with_label('set_propagation_phase()');
        $phase->connect('clicked', function () use ($controller, $show): void {
            $cases = GtkPropagationPhase::cases();
            $next = (array_search($controller->get_propagation_phase(), $cases, true) + 1) % count($cases);
            $controller->set_propagation_phase($cases[$next]);
            $off = $cases[$next] === GtkPropagationPhase::None ? ' - controller is off' : '';
            Demo::status('phase ' . $cases[$next]->name . $off);
            $show();
        });
        $limit = GtkButton::new_with_label('set_propagation_limit()');
        $limit->connect('clicked', function () use ($controller, $show): void {
            $controller->set_propagation_limit(
                $controller->get_propagation_limit() === GtkPropagationLimit::SameNative
                    ? GtkPropagationLimit::None
                    : GtkPropagationLimit::SameNative,
            );
            $show();
        });
        $name = GtkButton::new_with_label('set_name()');
        $name->connect('clicked', function () use ($controller, $show): void {
            $controller->set_name($controller->get_name() === 'demo' ? 'renamed' : 'demo');
            $show();
        });
        $reset = GtkButton::new_with_label('reset()');
        $reset->connect('clicked', function () use ($controller, $pad, $show, &$inside): void {
            $controller->reset();
            $inside = 'reset() - state cleared';
            $pad->set_text('click here, then press keys');
            $show();
        });
        foreach ([$phase, $limit, $name, $reset] as $button) {
            $buttons->append($button);
        }

        $show();
        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $page->append($readout);
        $page->append($pad);
        $page->append($buttons);
        return $page;
    },
    640,
    440,
);
