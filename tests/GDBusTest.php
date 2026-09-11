<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GAsyncResult;
use Gtk4\GBusType;
use Gtk4\GDBusConnection;
use Gtk4\GDBusMethodInvocation;
use Gtk4\GDBusNodeInfo;
use Gtk4\GDBusProxy;
use Gtk4\GDBusProxyFlags;
use Gtk4\GError;
use Gtk4\GLib;

/**
 * D-Bus on the session bus (a private one under tests/run.sh): the connection, an exported
 * object, a proxy, signals and the typed conversions are driven against it.
 *
 * A synchronous call cannot reach an object this same process exports - the main loop that
 * would dispatch it is the one blocked in the call - so every call to our own object is
 * asynchronous and waited for. Calls to the bus daemon itself are synchronous: it answers
 * from another process.
 */
final class GDBusTest extends GtkTestCase
{
    private const IFACE = 'org.phpgtk4.Test';
    private const PATH = '/org/phpgtk4/Test';
    private const PROPS = 'org.freedesktop.DBus.Properties';
    private const XML = '<node><interface name="org.phpgtk4.Test">'
        . '<method name="Echo"><arg type="s" name="text" direction="in"/>'
        . '<arg type="u" name="times" direction="in"/>'
        . '<arg type="s" name="out" direction="out"/><arg type="u" name="count" direction="out"/></method>'
        . '<method name="Fail"/>'
        . '<method name="Slow"/>'
        . '<property name="Name" type="s" access="readwrite"/>'
        . '<property name="Path" type="o" access="read"/>'
        . '<signal name="Ping"><arg type="s" name="what"/></signal>'
        . '</interface></node>';

    private static ?GDBusConnection $conn = null;

    /**
     * What the exported object's handlers were asked, in order.
     *
     * @var list<list<mixed>>
     */
    private array $log = [];

    /**
     * The last asynchronous reply: the reply list, or the GError.
     *
     * @var list<mixed>|GError|null
     */
    private array|GError|null $outcome = null;

    /**
     * The session bus - the private one tests/run.sh starts with dbus-run-session, or the
     * environment's. Not a GTestDBus: GTK connects to the session bus at init and GLib keeps
     * that connection as the process singleton, so a bus started here would be bypassed.
     */
    protected function setUp(): void
    {
        parent::setUp();
        if (self::$conn === null) {
            try {
                self::$conn = GDBusConnection::bus_get_sync(GBusType::Session);
            } catch (GError $e) {
                self::markTestSkipped('no session bus: ' . $e->getMessage());
            }
        }
    }

    private static function connection(): GDBusConnection
    {
        self::assertNotNull(self::$conn);
        return self::$conn;
    }

    private static function me(): string
    {
        return (string) self::connection()->get_unique_name();
    }

    /**
     * Run the loop until a reply landed in $outcome, or the budget is spent; returns it.
     *
     * @return list<mixed>|GError
     */
    private function reply(int $ms = 5000): array|GError
    {
        $deadline = microtime(true) + $ms / 1000;
        while ($this->outcome === null && microtime(true) < $deadline) {
            GLib::main_context_iteration(true);
        }
        self::assertNotNull($this->outcome, 'the D-Bus round trip did not complete in time');
        $outcome = $this->outcome;
        $this->outcome = null;
        return $outcome;
    }

    /** The GAsyncReadyCallback that files the reply (or the error) in $outcome. */
    private function finish(): \Closure
    {
        return function (GDBusConnection|GDBusProxy $source, GAsyncResult $res): void {
            try {
                $reply = $source->call_finish($res);
                self::assertIsList($reply);
                $this->outcome = $reply;
            } catch (GError $e) {
                $this->outcome = $e;
            }
        };
    }

    /**
     * A call to our own object: asynchronous, the reply through finish().
     *
     * @param list<mixed>|null $body
     * @return list<mixed>|GError
     */
    private function callOwn(string $iface, string $method, ?array $body, ?string $signature = null): array|GError
    {
        self::connection(
        )->call(
            self::me(),
            self::PATH,
            $iface,
            $method,
            $body,
            null,
            0,
            -1,
            null,
            $this->finish(),
            $signature,
        );
        return $this->reply();
    }

    /** Export the test object at PATH; the handlers log into $this->log. */
    private function export(): int
    {
        $info = GDBusNodeInfo::new_for_xml(self::XML)->lookup_interface(self::IFACE);
        self::assertNotNull($info);
        $state = ['Name' => 'initial'];
        $onCall = function (
            GDBusConnection $c,
            string $sender,
            string $path,
            string $iface,
            string $method,
            array $params,
            GDBusMethodInvocation $inv,
        ): void {
            $this->log[] = [$method, $params, $sender, $path, $iface];
            switch ($method) {
                case 'Echo':
                    [$text, $times] = $params;
                    self::assertIsString($text);
                    self::assertIsInt($times);
                    $inv->return_value([str_repeat($text, $times), $times * 2]);
                    break;
                case 'Fail':
                    $inv->return_dbus_error('org.phpgtk4.Test.Error.Nope', 'no way');
                    break;
                case 'Slow':
                    break;  // never answered: the caller's timeout is the reply
                default:
                    $inv->return_dbus_error('org.freedesktop.DBus.Error.UnknownMethod', $method);
            }
        };
        $onGet = function (GDBusConnection $c, string $s, string $p, string $i, string $prop) use (&$state): string {
            $this->log[] = ['get', $prop];
            return $prop === 'Path' ? '/answered/as/an/object/path' : (string) $state[$prop];
        };
        $onSet = function (
            GDBusConnection $c,
            string $s,
            string $p,
            string $i,
            string $prop,
            mixed $v,
        ) use (
            &$state
        ): bool {
            $this->log[] = ['set', $prop, $v];
            if (!is_string($v)) {
                return false;
            }
            $state[$prop] = $v;
            return true;
        };
        return self::connection()->register_object(self::PATH, $info, $onCall, $onGet, $onSet);
    }

    /** @return list<mixed> */
    private function lastLogged(): array
    {
        return $this->log === [] ? [] : $this->log[array_key_last($this->log)];
    }

    /** The exception $fn throws, asserted to be a $class whose message mentions $needle. */
    /**
     * @param class-string<\Throwable> $class
     */
    private function assertThrows(string $class, string $needle, callable $fn): void
    {
        try {
            $fn();
        } catch (\Throwable $e) {
            self::assertInstanceOf($class, $e);
            self::assertStringContainsString($needle, $e->getMessage());
            return;
        }
        self::fail("nothing thrown, expected $class");
    }

    public function testTheBusConnectionIsASingletonWithAName(): void
    {
        $conn = self::connection();
        self::assertSame($conn, GDBusConnection::bus_get_sync(GBusType::Session), 'one handle for the singleton');
        self::assertMatchesRegularExpression('/^:\d+\.\d+$/', self::me());
        self::assertFalse($conn->is_closed());
        // the daemon answers a synchronous call from its own process
        $dbus = 'org.freedesktop.DBus';
        $names = $conn->call_sync($dbus, '/org/freedesktop/DBus', $dbus, 'ListNames');
        self::assertIsArray($names[0]);
        self::assertContains(self::me(), $names[0]);
        self::assertSame([], $conn->call_sync($dbus, '/org/freedesktop/DBus', 'org.freedesktop.DBus.Peer', 'Ping'));
    }

    public function testATypedProxyCallReachesTheExportedObject(): void
    {
        $id = $this->export();
        self::assertGreaterThan(0, $id);
        try {
            $info = GDBusNodeInfo::new_for_xml(self::XML)->lookup_interface(self::IFACE);
            // DO_NOT_LOAD_PROPERTIES: the constructor would GetAll synchronously from our own
            // object, which this process cannot serve while blocked in the constructor
            $proxy = GDBusProxy::new_sync(
                self::connection(),
                GDBusProxyFlags::DO_NOT_LOAD_PROPERTIES,
                $info,
                self::me(),
                self::PATH,
                self::IFACE,
            );
            self::assertNull($proxy->get_cached_property('Name'));
            self::assertSame(self::IFACE, $proxy->get_interface_info()?->name);

            // the interface info types 3 as the `u` Echo declares, where inference would say `i`
            $proxy->call('Echo', ['ab', 3], 0, -1, null, $this->finish());
            self::assertSame(['ababab', 6], $this->reply());
            self::assertSame(['Echo', ['ab', 3], self::me(), self::PATH, self::IFACE], $this->lastLogged());

            $proxy->call('Fail', null, 0, -1, null, $this->finish());
            $error = $this->reply();
            self::assertInstanceOf(GError::class, $error);
            self::assertStringContainsString('org.phpgtk4.Test.Error.Nope', $error->getMessage());
            self::assertStringContainsString('no way', $error->getMessage());
        } finally {
            self::assertTrue(self::connection()->unregister_object($id));
        }
    }

    public function testAnUntypedCallInfersAndASignatureTypes(): void
    {
        $id = $this->export();
        try {
            // inferred: 2 becomes `i`, Echo wants `u` - the remote side refuses the signature
            $error = $this->callOwn(self::IFACE, 'Echo', ['x', 2]);
            self::assertInstanceOf(GError::class, $error);
            self::assertStringContainsString('(si)', $error->getMessage());

            self::assertSame(['xx', 4], $this->callOwn(self::IFACE, 'Echo', ['x', 2], '(su)'));

            // a reply type that does not match is the transport's error, not a crash
            self::connection(
            )->call(
                self::me(),
                self::PATH,
                self::IFACE,
                'Echo',
                ['x', 1],
                '(i)',
                0,
                -1,
                null,
                $this->finish(),
                '(su)',
            );
            self::assertInstanceOf(GError::class, $this->reply());

            // the unanswered method: the caller's timeout is the reply, and the handler ran
            self::connection(
            )->call(
                self::me(),
                self::PATH,
                self::IFACE,
                'Slow',
                null,
                null,
                0,
                200,
                null,
                $this->finish(),
            );
            self::assertInstanceOf(GError::class, $this->reply());
            self::assertSame('Slow', $this->lastLogged()[0]);
        } finally {
            self::assertTrue(self::connection()->unregister_object($id));
        }
    }

    public function testPropertiesGoThroughTheHandlersTypedByTheIntrospection(): void
    {
        $id = $this->export();
        try {
            // Path is declared `o`: the handler's plain string was converted to an object path
            self::assertSame(
                ['/answered/as/an/object/path'],
                $this->callOwn(self::PROPS, 'Get', [self::IFACE, 'Path']),
            );

            self::assertSame([], $this->callOwn(self::PROPS, 'Set', [self::IFACE, 'Name', 'renamed'], '(ssv)'));
            self::assertSame(['set', 'Name', 'renamed'], $this->lastLogged());
            self::assertSame(['renamed'], $this->callOwn(self::PROPS, 'Get', [self::IFACE, 'Name']));

            // a refused set is a D-Bus error to the caller
            self::assertInstanceOf(
                GError::class,
                $this->callOwn(self::PROPS, 'Set', [self::IFACE, 'Name', 7], '(ssv)'),
            );

            $all = [['Name' => 'renamed', 'Path' => '/answered/as/an/object/path']];
            self::assertSame($all, $this->callOwn(self::PROPS, 'GetAll', [self::IFACE]));
        } finally {
            self::assertTrue(self::connection()->unregister_object($id));
        }
    }

    public function testASignalRoundTrip(): void
    {
        $conn = self::connection();
        $got = null;
        $sub = $conn->signal_subscribe(null, self::IFACE, 'Ping', null, null, 0, function (
            GDBusConnection $c,
            ?string $sender,
            string $path,
            string $iface,
            string $signal,
            array $params,
        ) use (&$got): void {
            $got = [$sender, $path, $iface, $signal, $params];
        });
        self::assertGreaterThan(0, $sub);
        self::assertTrue($conn->emit_signal(null, self::PATH, self::IFACE, 'Ping', ['hello']));
        $deadline = microtime(true) + 5;
        while ($got === null && microtime(true) < $deadline) {
            GLib::main_context_iteration(true);
        }
        self::assertSame([self::me(), self::PATH, self::IFACE, 'Ping', ['hello']], $got);
        $conn->signal_unsubscribe($sub);
        // the unsubscribe's destroy notify comes from an idle: pump it, nothing may explode
        for ($i = 0; $i < 20; $i++) {
            GLib::main_context_iteration(false);
        }
        $got = 'nothing';
        $conn->emit_signal(null, self::PATH, self::IFACE, 'Ping', ['again']);
        for ($i = 0; $i < 50; $i++) {
            GLib::main_context_iteration(false);
        }
        self::assertSame('nothing', $got, 'unsubscribed');
    }

    public function testAnInvocationAnswersOnce(): void
    {
        $conn = self::connection();
        $info = GDBusNodeInfo::new_for_xml(self::XML)->interfaces[0];
        $second = null;
        $once = function ($c, $s, $p, $i, $m, $params, GDBusMethodInvocation $inv) use (&$second): void {
            $inv->return_value(['x', 1]);
            try {
                $inv->return_dbus_error('org.x.Y', 'twice');
            } catch (\LogicException $e) {
                $second = $e->getMessage();
            }
        };
        $id = $conn->register_object('/org/phpgtk4/Once', $info, $once);
        try {
            $conn->call(
                self::me(),
                '/org/phpgtk4/Once',
                self::IFACE,
                'Echo',
                ['x', 1],
                null,
                0,
                -1,
                null,
                $this->finish(),
                '(su)',
            );
            self::assertSame(['x', 1], $this->reply());
            self::assertStringContainsString('answered already', (string) $second);
        } finally {
            $conn->unregister_object($id);
        }
    }

    public function testBodiesMustBeListsAndSignaturesTuples(): void
    {
        $conn = self::connection();
        $dbus = 'org.freedesktop.DBus';
        $ping = fn(
            ?array $body,
            ?string $sig,
        ) => $conn->call_sync(
            $dbus,
            '/',
            $dbus,
            'Ping',
            $body,
            null,
            0,
            -1,
            null,
            $sig,
        );
        $this->assertThrows(\TypeError::class, 'must be a list', fn() => $ping(['a' => 1], null));
        $this->assertThrows(\ValueError::class, 'tuple type', fn() => $ping(['a'], 's'));
        $this->assertThrows(\TypeError::class, '(s)', fn() => $ping(null, '(s)'));
        $info = GDBusNodeInfo::new_for_xml(self::XML)->interfaces[0];
        $register = fn() => $conn->register_object('not a path', $info, fn() => null);
        $this->assertThrows(\ValueError::class, 'object path', $register);
    }

    public function testIntrospectionRecordsReadAsProperties(): void
    {
        $node = GDBusNodeInfo::new_for_xml(self::XML);
        self::assertNull($node->path);
        $iface = $node->interfaces[0];
        self::assertSame(self::IFACE, $iface->name);
        self::assertSame(['Echo', 'Fail', 'Slow'], array_map(fn($m) => $m->name, $iface->methods));
        $echo = $iface->lookup_method('Echo');
        self::assertNotNull($echo);
        self::assertSame(['text' => 's', 'times' => 'u'], array_combine(
            array_map(fn($a) => (string) $a->name, $echo->in_args),
            array_map(fn($a) => $a->signature, $echo->in_args),
        ));
        self::assertSame('u', $echo->out_args[1]->signature);
        self::assertSame('Ping', $iface->signals[0]->name);
        self::assertSame('s', $iface->signals[0]->args[0]->signature);
        self::assertSame('Path', $iface->properties[1]->name);
        self::assertSame(1, $iface->properties[1]->flags, 'READABLE');
        self::assertNull($iface->lookup_method('Nope'));
        try {
            GDBusNodeInfo::new_for_xml('<node><interface/></node>');
            self::fail('an interface needs a name');
        } catch (GError) {
            $this->addToAssertionCount(1);
        }
    }

    public function testWhatIsLeftBehindIsCleanedUpAtShutdown(): void
    {
        // Registered and subscribed, never released: RSHUTDOWN's teardown unregisters and
        // unsubscribes (the keyed clears), and the destroy notifies GLib defers to an idle
        // find the callables gone. The exit path itself is tests/scripts/shutdown.php's.
        $conn = self::connection();
        $info = GDBusNodeInfo::new_for_xml(self::XML)->interfaces[0];
        $left = 'org.phpgtk4.Left';
        self::assertGreaterThan(0, $conn->register_object('/org/phpgtk4/Left', $info, fn() => null));
        self::assertGreaterThan(0, $conn->signal_subscribe(null, $left, null, null, null, 0, fn() => null));
    }
}
