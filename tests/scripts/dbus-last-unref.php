<?php

declare(strict_types=1);

/*
 * Regression script for GDBusTest::testALastUnrefOnTheWorkerThreadRunsNoPhpCode(). Subscribes
 * and exports on a fresh session-bus connection and drops it, many times: GDBus's worker thread
 * is still handling the AddMatch reply, so it often drops the connection's last reference, and
 * what the module watches the connection with must not run PHP code on that thread (it did: a
 * segfault on ZTS, 2026-11-24). Must exit 0 and print "dbus ok".
 */

use Gtk4\GBusType;
use Gtk4\GDBusConnection;
use Gtk4\GDBusNodeInfo;

$rounds = (int) ($argv[1] ?? 300);
$info = GDBusNodeInfo::new_for_xml('<node><interface name="org.phpgtk4.LastUnref">'
    . '<method name="Never"/></interface></node>')->interfaces[0];
for ($i = 0; $i < $rounds; $i++) {
    $conn = GDBusConnection::bus_get_sync(GBusType::Session);
    $conn->signal_subscribe(null, 'org.phpgtk4.LastUnref', null, null, null, 0, function (): void {
        echo "never\n";
    });
    // a path of its own: the previous round's connection may still be the singleton
    $conn->register_object("/org/phpgtk4/LastUnref/r$i", $info, function (): void {
        echo "never\n";
    });
    unset($conn);
}
echo "dbus ok\n";
