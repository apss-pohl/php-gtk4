<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GAsyncResult;
use Gtk4\GBusType;
use Gtk4\GDBusConnection;
use Gtk4\GDBusMethodInvocation;
use Gtk4\GDBusNodeInfo;
use Gtk4\GError;
use Gtk4\GtkBox;
use Gtk4\GtkButton;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GDBusConnection - the session bus: a call to the daemon, an object of our own
 * exported on it, and a signal round trip.
 *
 * bus_get_sync() is the process's one connection to the session bus. The page exports a
 * counter object (register_object() with an introspection XML and three PHP handlers), calls
 * it back through the bus - asynchronously, because a synchronous call to an object this
 * same process serves would wait on the main loop that is blocked in the call - and
 * subscribes to the signal the counter emits. Every body is a PHP list; the reply from
 * return_value() is typed by the method's introspection (here `u` for the count).
 *
 *   bin/php-gtk4 examples/demo.php GDBusConnection
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GDBusConnection',
    'the session bus: call the daemon, export an object, call it back, get its signal',
    function (GtkWindow $win): GtkWidget {
        $label = Demo::label();
        try {
            $bus = GDBusConnection::bus_get_sync(GBusType::Session);
        } catch (GError $e) {
            $label->set_markup('<b>no session bus</b>: ' . htmlspecialchars($e->getMessage()));
            return $label;
        }

        $iface = 'org.phpgtk4.Counter';
        $path = '/org/phpgtk4/Counter';
        $info = GDBusNodeInfo::new_for_xml(
            "<node><interface name=\"$iface\">"
            . '<method name="Increment"><arg type="u" name="by" direction="in"/>'
            . '<arg type="u" name="count" direction="out"/></method>'
            . '<property name="Count" type="u" access="read"/>'
            . '<signal name="Changed"><arg type="u" name="count"/></signal>'
            . '</interface></node>',
        )->interfaces[0];

        $state = new class {
            public int $count = 0;
            public string $lastSignal = '-';
            public string $lastReply = '-';
        };
        // (connection, sender, path, interface, method, arguments, invocation): the reply goes
        // through the invocation, typed by the introspection above
        $onCall = function (
            GDBusConnection $c,
            string $sender,
            string $p,
            string $i,
            string $method,
            array $args,
            GDBusMethodInvocation $inv,
        ) use (
            $state,
            $path,
            $iface
        ): void {
            $state->count += is_int($args[0] ?? null) ? $args[0] : 0;
            $inv->return_value([$state->count]);
            $c->emit_signal(null, $path, $iface, 'Changed', [$state->count], '(u)');
        };
        $onGet = fn(GDBusConnection $c, string $s, string $p, string $i, string $prop): int => $state->count;
        $bus->register_object($path, $info, $onCall, $onGet);

        $onSignal = function (
            GDBusConnection $c,
            ?string $sender,
            string $p,
            string $i,
            string $signal,
            array $args,
        ) use ($state): void {
            $state->lastSignal = sprintf('%s(%s) from %s', $signal, json_encode($args), $sender ?? '?');
        };
        $bus->signal_subscribe(null, $iface, 'Changed', $path, null, 0, $onSignal);

        $refresh = function () use ($label, $bus, $state): void {
            $label->set_markup(sprintf(
                "<b>%s</b>\nunique name <tt>%s</tt>\n\ncount <b>%d</b>\n"
                . "last reply: <tt>%s</tt>\nlast signal: <tt>%s</tt>",
                $bus::class,
                htmlspecialchars((string) $bus->get_unique_name()),
                $state->count,
                htmlspecialchars($state->lastReply),
                htmlspecialchars($state->lastSignal),
            ));
        };
        $refresh();

        $increment = new GtkButton();
        $increment->set_label('call Increment(5) through the bus');
        $increment->connect('clicked', function () use ($bus, $path, $iface, $state, $refresh): void {
            $bus->call(
                (string) $bus->get_unique_name(),
                $path,
                $iface,
                'Increment',
                [5],
                null,
                0,
                -1,
                null,
                function (GDBusConnection $c, GAsyncResult $res) use ($state, $refresh): void {
                    try {
                        $state->lastReply = json_encode($c->call_finish($res)) ?: '?';
                    } catch (GError $e) {
                        $state->lastReply = 'GError: ' . $e->getMessage();
                    }
                    $refresh();
                },
                '(u)',
            );
            Demo::status('call() sent; the reply and the Changed signal arrive on the main loop');
        });
        $names = new GtkButton();
        $names->set_label('ListNames on the daemon (sync)');
        $names->connect('clicked', function () use ($bus): void {
            $dbus = 'org.freedesktop.DBus';
            $reply = $bus->call_sync($dbus, '/org/freedesktop/DBus', $dbus, 'ListNames');
            $names = is_array($reply[0] ?? null) ? $reply[0] : [];
            Demo::status(count($names) . ' names on the session bus');
        });

        $buttons = new GtkBox(GtkOrientation::Horizontal, 6);
        $buttons->append($increment);
        $buttons->append($names);
        $box = new GtkBox(GtkOrientation::Vertical, 12);
        $box->append($label);
        $box->append($buttons);
        return $box;
    },
);
