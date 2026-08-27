<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GActionGroup;
use Gtk4\GLib;
use Gtk4\GSimpleAction;
use Gtk4\GtkApplication;
use Gtk4\GtkButton;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GActionGroup - activating actions by name.
 *
 * has_action(), list_actions() and activate_action() only work once the
 * application is registered, i.e. from `startup` on - before that the list is
 * empty, which is why this waits for the first idle tick before reading it.
 * Clicking activates the next action by name and the counters move.
 *
 *   bin/php-gtk4 examples/demo.php GActionGroup
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GActionGroup',
    'activating actions by name',
    function (GtkWindow $win, GtkApplication $app): GtkWidget {
        $label = Demo::label();
        $button = new GtkButton();
        $button->set_child($label);

        /** @var array<string, int> $hits */
        $hits = ['first' => 0, 'second' => 0, 'third' => 0];
        foreach (array_keys($hits) as $name) {
            $action = new GSimpleAction($name);
            $action->connect('activate', function (GSimpleAction $self) use (&$hits): void {
                $hits[$self->get_name()]++;
            });
            $app->add_action($action);
        }

        $render = function () use ($label, $app, &$hits): void {
            $rows = [];
            foreach ($hits as $name => $count) {
                $rows[] = sprintf(
                    "has_action('%s')%s%-5s activated %dx",
                    $name,
                    str_repeat(' ', 8 - strlen($name)),
                    $app->has_action($name) ? 'true' : 'false',
                    $count,
                );
            }
            $label->set_markup(sprintf(
                "<b>GActionGroup</b> <small>(interface)</small>\n\n<tt>%s</tt>\n\n"
                . "list_actions(): <tt>%s</tt>\n\n<small>click to activate the next one by name</small>",
                htmlspecialchars(implode("\n", $rows)),
                htmlspecialchars(implode(', ', $app->list_actions())),
            ));
        };

        $step = 0;
        $button->connect('clicked', function () use ($app, &$hits, &$step, $render): void {
            $names = array_keys($hits);
            $app->activate_action($names[$step++ % count($names)]);
            $render();
        });

        // Registered by the time the loop goes idle.
        GLib::idle_add(function () use ($render): bool {
            $render();
            return false;      // one-shot
        });

        $render();
        return $button;
    },
    560,
    320,
);
