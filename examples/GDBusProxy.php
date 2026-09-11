<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GBusType;
use Gtk4\GDBusProxy;
use Gtk4\GDBusProxyFlags;
use Gtk4\GError;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GDBusProxy - one remote object as a handle: the bus daemon itself, through its
 * org.freedesktop.DBus interface.
 *
 * new_for_bus_sync() binds a bus name, an object path and an interface; call_sync() then
 * takes the method name and its arguments as a list. With interface info the arguments are
 * typed from the introspection; without (as here) they are inferred, which is right for the
 * daemon's string-only methods. The proxy's own signals (g-signal, g-properties-changed)
 * are plain connect() targets.
 *
 *   bin/php-gtk4 examples/demo.php GDBusProxy
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GDBusProxy',
    'a remote object as a handle: the bus daemon through org.freedesktop.DBus',
    function (GtkWindow $win): GtkWidget {
        $label = Demo::label();
        try {
            $proxy = GDBusProxy::new_for_bus_sync(
                GBusType::Session,
                GDBusProxyFlags::DO_NOT_LOAD_PROPERTIES,
                null,
                'org.freedesktop.DBus',
                '/org/freedesktop/DBus',
                'org.freedesktop.DBus',
            );
        } catch (GError $e) {
            $label->set_markup('<b>no session bus</b>: ' . htmlspecialchars($e->getMessage()));
            return $label;
        }

        $show = function (string $what, mixed $reply) use ($label, $proxy): void {
            $label->set_markup(sprintf(
                "<b>%s</b>\nname <tt>%s</tt> · path <tt>%s</tt>\ninterface <tt>%s</tt>\n\n%s\n<tt>%s</tt>",
                $proxy::class,
                htmlspecialchars((string) $proxy->get_name()),
                htmlspecialchars($proxy->get_object_path()),
                htmlspecialchars($proxy->get_interface_name()),
                htmlspecialchars($what),
                htmlspecialchars(is_string($reply) ? $reply : (json_encode($reply, JSON_PRETTY_PRINT) ?: '?')),
            ));
        };
        $show('call_sync() a method: click', '-');

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        foreach (
            [
                ['GetId', []],
                ['ListNames', []],
                ['GetNameOwner', ['org.freedesktop.DBus']],
                ['NameHasOwner', ['org.example.Nobody']],
            ] as [$method, $args]
        ) {
            $button = new GtkButton();
            $button->set_label($method . '(' . implode(', ', $args) . ')');
            $button->connect('clicked', function () use ($proxy, $method, $args, $show): void {
                try {
                    $reply = $proxy->call_sync($method, $args);
                    if ($method === 'ListNames' && is_array($reply[0] ?? null)) {
                        $names = array_map(fn(mixed $n): string => is_string($n) ? $n : '?', $reply[0]);
                        $reply = count($names) . ' names, e.g. ' . implode(', ', array_slice($names, 0, 4));
                    }
                    $show("$method -> reply tuple as a list", $reply);
                } catch (GError $e) {
                    $show("$method -> GError", $e->getMessage());
                }
            });
            $buttons->append($button);
        }

        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $box->append($label);
        $box->append($buttons);
        return $box;
    },
);
