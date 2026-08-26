<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GActionMap;
use Gtk4\GSimpleAction;
use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GActionMap - the container side of actions: add, look up, remove.
 *
 * GtkApplication implements it. Clicking walks through adding two actions,
 * then removing them again; the table shows what lookup_action() returns at each
 * step - an action object, or null once it is gone.
 *
 *   bin/php-gtk4 examples/GActionMap.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GActionMap', function (GtkWindow $win, GtkApplication $app): GtkWidget {
    $label = Demo::label();
    $button = new GtkButton();
    $button->set_child($label);

    $names = ['save', 'open'];
    $note = 'nothing added yet';

    $render = function () use ($label, $app, $names, &$note): void {
        $rows = [];
        foreach ($names as $name) {
            $found = $app->lookup_action($name);
            $rows[] = sprintf(
                "lookup_action('%s')%s-> %s",
                $name,
                str_repeat(' ', 8 - strlen($name)),
                $found === null ? 'null' : sprintf('%s named "%s"', $found::class, $found->get_name()),
            );
        }
        $label->set_markup(sprintf(
            "<b>GActionMap</b> <small>(interface)</small>\n\n<tt>%s</tt>\n\n"
            . "last step: <i>%s</i>\n\n<small>click for the next one</small>",
            htmlspecialchars(implode("\n", $rows)),
            htmlspecialchars($note),
        ));
    };

    /** @var list<array{string, callable(): void}> $steps */
    $steps = [
        ["add_action(new GSimpleAction('save'))", static fn() => $app->add_action(new GSimpleAction('save'))],
        ["add_action(new GSimpleAction('open'))", static fn() => $app->add_action(new GSimpleAction('open'))],
        ["remove_action('save')", static fn() => $app->remove_action('save')],
        ["remove_action('open')", static fn() => $app->remove_action('open')],
    ];

    $step = 0;
    $button->connect('clicked', function () use ($steps, &$step, &$note, $render): void {
        [$description, $apply] = $steps[$step++ % count($steps)];
        $apply();
        $note = $description;
        $render();
    });

    $render();
    return $button;
}, 560, 320);
