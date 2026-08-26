<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GObject;
use Gtk4\GParamSpec;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GObject - the root handle: properties, signals, notify.
 *
 * The same property is written three ways - typed method, set_property() by name,
 * and as a PHP property. Each write emits notify::title, and the window title in
 * the title bar changes along with the log below.
 *
 *   bin/php-gtk4 examples/demo.php GObject
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GObject',
    'the root handle: properties, signals, notify',
    function (GtkWindow $win): GtkWidget {
        /** @var list<string> $log */
        $log = [];
        $label = Demo::label();
        $render = function () use (&$log, $label): void {
            $label->set_markup('<tt>' . implode("\n", $log) . '</tt>');
        };

        // The handler gets the emitting object first, then the signal's own
        // parameters - notify passes the GParamSpec of the property that changed.
        $id = $win->connect('notify::title', function (
            GObject $obj,
            GParamSpec $spec,
        ) use (
            $win,
            &$log,
            $render
        ): void {
            $log[] = sprintf(
                '%-14s %-10s -> %s',
                $spec->get_name(),
                $spec->get_value_type(),
                htmlspecialchars((string) $win->get_title()),
            );
            $render();
        });

        $win->set_title('1 - typed method');                     // set_title()
        $win->set_property('title', '2 - set_property()');       // by property name
        $win->title = '3 - PHP property';                        // dashes become underscores

        // Identity: wrapping the same C object twice yields the same PHP handle.
        $same = $win->get_property('application') === $win->get_property('application');
        $log[] = sprintf('%-14s %-10s -> %s', 'handles', 'identity', $same ? 'same object ===' : 'differ');

        $win->handler_disconnect($id);
        $win->title = '4 - after handler_disconnect (not logged)';
        $log[] = sprintf('%-14s %-10s -> %s', 'title', 'gchararray', htmlspecialchars((string) $win->get_title()));
        $render();

        return $label;
    },
);
