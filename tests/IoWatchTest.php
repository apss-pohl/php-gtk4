<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GIOCondition;
use Gtk4\GLib;

/**
 * GLib::io_add_watch(): a socket joins the main loop. A socketpair stands in for the server:
 * writing on one end makes the other end's watch fire on the next iteration.
 */
final class IoWatchTest extends GtkTestCase
{
    /** @return array{resource, resource} */
    private static function pair(): array
    {
        $pair = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
        self::assertIsArray($pair);
        return [$pair[0], $pair[1]];
    }

    /** Pump until $until() holds, at most $seconds. */
    private static function pump(callable $until, float $seconds = 2.0): void
    {
        $deadline = microtime(true) + $seconds;
        while (!$until() && microtime(true) < $deadline) {
            GLib::main_context_iteration(false);
            usleep(1000);
        }
    }

    public function testReadableFiresWithTheStreamAndCondition(): void
    {
        [$a, $b] = self::pair();
        $seen = [];
        /** @var \Closure(): list<array{int, string|false}> $recorded what the callback recorded (by reference) */
        $recorded = function () use (&$seen): array {
            return $seen;
        };
        $id = GLib::io_add_watch($a, GIOCondition::IN, function (mixed $stream, int $condition) use (&$seen, $a): bool {
            self::assertSame($a, $stream, 'the watched stream comes back');
            $seen[] = [$condition, is_resource($stream) ? fread($stream, 64) : false];
            return true;  // keep watching
        });
        self::assertGreaterThan(0, $id);
        self::assertSame([], $seen, 'nothing to read yet');
        GLib::main_context_iteration(false);
        self::assertSame([], $seen);

        fwrite($b, 'ping');
        self::pump(static fn(): bool => $recorded() !== []);
        self::assertCount(1, $recorded());
        self::assertSame(GIOCondition::IN, $recorded()[0][0] & GIOCondition::IN);
        self::assertSame('ping', $recorded()[0][1]);

        fwrite($b, 'pong');
        self::pump(static fn(): bool => count($recorded()) === 2);
        self::assertSame('pong', $recorded()[1][1], 'returning true kept the watch');

        self::assertTrue(GLib::source_remove($id));
        self::assertFalse(GLib::source_remove($id), 'already gone');
        fwrite($b, 'lost');
        GLib::main_context_iteration(false);
        self::assertCount(2, $seen, 'removed: no more callbacks');
        fclose($a);
        fclose($b);
    }

    public function testReturningFalseRemovesTheWatch(): void
    {
        [$a, $b] = self::pair();
        $calls = 0;
        $id = GLib::io_add_watch($a, GIOCondition::IN, function (mixed $stream) use (&$calls): bool {
            $calls++;
            if (is_resource($stream)) {
                fread($stream, 64);
            }
            return false;
        });
        fwrite($b, 'once');
        self::pump(static fn(): bool => $calls === 1);
        self::assertSame(1, $calls);
        self::assertFalse(GLib::source_remove($id), 'the false return already removed it');
        fwrite($b, 'twice');
        GLib::main_context_iteration(false);
        self::assertSame(1, $calls);
        fclose($a);
        fclose($b);
    }

    public function testPeerCloseIsAHangUp(): void
    {
        [$a, $b] = self::pair();
        $conditions = [];
        $onEvent = function (mixed $stream, int $condition) use (&$conditions): bool {
            $conditions[] = $condition;
            if (is_resource($stream)) {
                fread($stream, 64);
            }
            return ($condition & GIOCondition::HUP) === 0;
        };
        GLib::io_add_watch($a, GIOCondition::IN | GIOCondition::HUP, $onEvent);
        fclose($b);
        self::pump(static fn(): bool => $conditions !== []);
        self::assertNotEmpty($conditions);
        $last = end($conditions);
        self::assertIsInt($last);
        self::assertSame(GIOCondition::HUP, $last & GIOCondition::HUP);
        fclose($a);
    }

    public function testWritableFiresImmediatelyOnAnIdleSocket(): void
    {
        [$a, $b] = self::pair();
        $fired = $this->latch();
        GLib::io_add_watch($a, GIOCondition::OUT, function () use ($fired): bool {
            $fired();
            return false;
        });
        self::pump(fn(): bool => $this->latched());
        self::assertTrue($this->latched(), 'an idle socket is writable at once');
        fclose($a);
        fclose($b);
    }

    public function testArgumentsAreValidated(): void
    {
        [$a, $b] = self::pair();
        try {
            self::opaque([GLib::class, 'io_add_watch'])('not a stream', GIOCondition::IN, static fn(): bool => false);
            self::fail('a string is not a stream');
        } catch (\TypeError $e) {
            self::assertStringContainsString('Argument #1 ($stream)', $e->getMessage());
        }
        try {
            GLib::io_add_watch($a, 0, static fn(): bool => false);
            self::fail('no condition bit');
        } catch (\ValueError $e) {
            self::assertStringContainsString('GIOCondition', $e->getMessage());
        }
        try {
            GLib::io_add_watch($a, 1024, static fn(): bool => false);
            self::fail('an unknown bit');
        } catch (\ValueError $e) {
            self::assertStringContainsString('Argument #2 ($condition)', $e->getMessage());
        }
        $file = fopen('php://memory', 'r+');
        self::assertIsResource($file);
        try {
            GLib::io_add_watch($file, GIOCondition::IN, static fn(): bool => false);
            self::fail('a memory stream has no socket descriptor');
        } catch (\ValueError $e) {
            self::assertStringContainsString('socket descriptor', $e->getMessage());
        }
        fclose($a);
        try {
            GLib::io_add_watch($a, GIOCondition::IN, static fn(): bool => false);
            self::fail('closed');
        } catch (\TypeError $e) {  // PHP's own resource check: a closed resource is no stream
            self::assertStringContainsString('not a valid stream resource', $e->getMessage());
        }
        fclose($b);
    }

    public function testExceptionInTheCallbackGoesThroughTheBoundary(): void
    {
        [$a, $b] = self::pair();
        GLib::io_add_watch($a, GIOCondition::IN, static function (): bool {
            throw new \RuntimeException('watch boom');
        });
        fwrite($b, 'x');
        $seen = $this->captureHandlerException(static function (): void {
            $deadline = microtime(true) + 2;
            while (microtime(true) < $deadline) {
                GLib::main_context_iteration(false);
                usleep(1000);
            }
        });
        self::assertNotNull($seen);
        self::assertSame(['watch boom', 'GLib::io_add_watch'], [$seen[0], $seen[1]]);
        fclose($a);
        fclose($b);
    }

    public function testAnExtSocketsSocketJoinsThroughAnExportedStream(): void
    {
        if (!function_exists('socket_create_pair')) {
            self::markTestSkipped('ext-sockets not loaded');
        }
        $ok = socket_create_pair(AF_UNIX, SOCK_STREAM, 0, $pair);
        self::assertTrue($ok);
        self::assertIsArray($pair);
        [$sa, $sb] = $pair;
        self::assertInstanceOf(\Socket::class, $sa);
        self::assertInstanceOf(\Socket::class, $sb);
        $stream = socket_export_stream($sa);
        self::assertIsResource($stream);
        $got = null;
        GLib::io_add_watch($stream, GIOCondition::IN, function (mixed $s) use (&$got): bool {
            $got = is_resource($s) ? fread($s, 64) : false;
            return false;
        });
        socket_write($sb, 'from ext-sockets');
        self::pump(static fn(): bool => $got !== null);
        self::assertSame('from ext-sockets', $got);
        socket_close($sb);
    }

    /** @var list<resource> the pair a watch is left on until request shutdown */
    private static array $leftBehind = [];

    public function testAWatchLeftBehindIsDroppedAtShutdownSafely(): void
    {
        // The watch (and the stream it keeps) are released by teardown, not by GLib after
        // Zend is gone. Both ends stay open (static): a closed peer would make the watch
        // report HUP on every iteration for the rest of the process.
        [$a, $b] = self::pair();
        self::$leftBehind = [$a, $b];
        $id = GLib::io_add_watch($a, GIOCondition::IN, static fn(): bool => true);
        self::assertGreaterThan(0, $id);
        self::assertCount(2, self::$leftBehind, 'both ends stay open until the request ends');
    }
}
