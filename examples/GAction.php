<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GAction;
use Gtk4\GLib;
use Gtk4\GSimpleAction;
use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GAction - the interface every action implements.
 *
 * A read-only view: name, enabled, the GVariant type string of the parameter, and
 * the current state. A timer activates the action twice a second; clicking flips
 * `enabled`, and while it is false the activation is refused - the counter below
 * visibly stops.
 *
 *   bin/php-gtk4 examples/GAction.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GAction', function (GtkWindow $win, GtkApplication $app): GtkWidget {
    $label = Demo::label();
    $button = new GtkButton();
    $button->set_child($label);

    $counter = new GSimpleAction('counter', null, 0);
    $counter->connect('activate', function (GSimpleAction $self): void {
        $state = $self->get_state();
        $self->set_state(is_int($state) ? $state + 1 : 0);
    });
    $app->add_action($counter);

    // Typed against the interface, not the implementation.
    $describe = static function (GAction $action): string {
        return sprintf(
            "get_name()            %s\nget_enabled()         %s\nget_parameter_type()  %s\nget_state()           %s",
            $action->get_name(),
            $action->get_enabled() ? 'true' : 'false',
            var_export($action->get_parameter_type(), true),
            var_export($action->get_state(), true),
        );
    };

    $attempts = 0;
    $render = function () use ($label, $counter, $describe, &$attempts): void {
        $label->set_markup(sprintf(
            "<b>GAction</b> <small>(interface)</small>\n\n<tt>%s</tt>\n\n"
            . "<small>%d activation(s) attempted · click to toggle <b>enabled</b></small>",
            htmlspecialchars($describe($counter)),
            $attempts,
        ));
    };

    $button->connect('clicked', function () use ($counter, $render): void {
        $counter->set_enabled(!$counter->get_enabled());
        $render();
    });

    // A disabled action never reaches its handler, so the state stops moving.
    GLib::timeout_add(500, function () use ($app, $render, &$attempts): bool {
        $attempts++;
        $app->activate_action('counter');
        $render();
        return true;
    });

    $render();
    return $button;
}, 520, 320);
