<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GSimpleAction;
use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GSimpleAction - the plain GAction implementation.
 *
 * Three shapes of action: stateless, one taking a GVariant parameter, and a
 * stateful toggle. GVariant parameters and states are plain PHP values. Clicking
 * runs the next one and the log shows what each handler was handed.
 *
 *   bin/php-gtk4 examples/demo.php GSimpleAction
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GSimpleAction',
    'the plain GAction implementation',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        /** @var list<string> $log */
        $log = [];
        $label = Demo::label();
        $button = new GtkButton();
        $button->set_child($label);

        $render = function () use (&$log, $label): void {
            $label->set_markup(
                "<b>GSimpleAction</b>\n<small>click to run the next one</small>\n\n<tt>"
                . htmlspecialchars(implode("\n", array_slice($log, -9)))
                . '</tt>',
            );
        };

        // Stateless.
        $ping = new GSimpleAction('ping');
        $ping->connect('activate', function (GSimpleAction $self) use (&$log, $render): void {
            $log[] = sprintf(
                'activate %-7s parameter_type=%s',
                $self->get_name(),
                var_export($self->get_parameter_type(), true),
            );
            $render();
        });

        // Parameterised: 's' is the GVariant type string the parameter must have.
        $greet = new GSimpleAction('greet', 's');
        $greet->connect('activate', function (GSimpleAction $self, mixed $who) use (&$log, $render): void {
            $log[] = sprintf('activate %-7s who=%s', $self->get_name(), is_string($who) ? $who : '?');
            $render();
        });

        // Stateful: the state's GVariant type is inferred from the initial value.
        $dark = new GSimpleAction('dark', null, false);
        $dark->connect('change-state', function (GSimpleAction $self, mixed $state) use (&$log, $render): void {
            $self->set_state($state);          // change-state proposes, set_state commits
            $log[] = sprintf('state    %-7s -> %s', $self->get_name(), var_export($self->get_state(), true));
            $render();
        });

        foreach ([$ping, $greet, $dark] as $action) {
            $app->add_action($action);          // reachable as "app.<name>"
        }

        $step = 0;
        $button->connect('clicked', function () use ($app, $dark, &$step): void {
            match ($step++ % 4) {
                0 => $app->activate_action('ping'),
                1 => $app->activate_action('greet', 'world'),
                2 => $app->activate_action('dark', true),
                default => $dark->activate(false),      // straight at the action object
            };
        });

        $log[] = 'registered: ' . implode(', ', $app->list_actions());
        $render();
        return $button;
    },
    520,
    340,
);
