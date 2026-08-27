<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GLib;
use Gtk4\GMainLoop;
use Gtk4\Gtk;

/** Gtk4\GMainLoop + Gtk4\GLib idle/timeout sources. */
final class MainLoopTest extends GtkTestCase
{
    public function testIdleCallbackRunsAndLoopQuits(): void
    {
        $loop = new GMainLoop();
        $ran = false;
        GLib::idle_add(function () use (&$ran, $loop): bool {
            $ran = true;
            $loop->quit();
            return false;
        });
        self::assertFalse($loop->is_running());
        $loop->run();
        self::assertTrue($ran);
        self::assertFalse($loop->is_running());
    }

    public function testTimeoutRepeatsWhileReturningTrue(): void
    {
        $loop = new GMainLoop();
        $ticks = 0;
        GLib::timeout_add(1, function () use (&$ticks, $loop): bool {
            $ticks++;
            if ($ticks === 3) {
                $loop->quit();
                return false;
            }
            return true;
        });
        $loop->run();
        self::assertSame(3, $ticks);
    }

    public function testMissingReturnRemovesSource(): void
    {
        $loop = new GMainLoop();
        $calls = 0;
        GLib::idle_add(function () use (&$calls): void {
            $calls++;
        });
        GLib::timeout_add(20, function () use ($loop): bool {
            $loop->quit();
            return false;
        });
        $loop->run();
        self::assertSame(1, $calls, 'a void callback runs once (null -> remove)');
    }

    public function testSourceRemove(): void
    {
        $loop = new GMainLoop();
        $calls = 0;
        $id = GLib::timeout_add(1, function () use (&$calls): bool {
            $calls++;
            return true;
        });
        self::assertTrue(GLib::source_remove($id));
        self::assertFalse(GLib::source_remove($id), 'second removal reports false');
        GLib::timeout_add(15, function () use ($loop): bool {
            $loop->quit();
            return false;
        });
        $loop->run();
        self::assertSame(0, $calls);
    }

    public function testMainContextIterationDispatchesWithoutRun(): void
    {
        $ran = false;
        GLib::idle_add(function () use (&$ran): bool {
            $ran = true;
            return false;
        });
        self::assertTrue(GLib::main_context_iteration(false), 'one source dispatched');
        self::assertTrue($ran);
        // Drain whatever else is ready, then a non-blocking iteration dispatches nothing.
        for ($i = 0; $i < 20; $i++) {
            GLib::main_context_iteration(false);
        }
        self::assertFalse(GLib::main_context_iteration(false));
    }

    public function testMainContextIterationPropagatesInLogModeAsNothing(): void
    {
        $seen = null;
        Gtk::set_exception_handler(function (\Throwable $e) use (&$seen): void {
            $seen = $e;
        });
        GLib::idle_add(static function (): bool {
            throw new \LogicException('logged');
        });
        GLib::main_context_iteration(true);   // Log mode: handler, no propagation
        Gtk::set_exception_handler(null);
        self::assertInstanceOf(\LogicException::class, $seen);
    }

    public function testNegativeIntervalRejected(): void
    {
        $this->expectException(\ValueError::class);
        GLib::timeout_add(-1, fn() => false);
    }

    public function testReentrantRunIsRefused(): void
    {
        $loop = new GMainLoop();
        $inner = null;
        GLib::idle_add(function () use ($loop, &$inner): bool {
            try {
                $loop->run();
            } catch (\LogicException $e) {
                $inner = $e;
            }
            $loop->quit();
            return false;
        });
        $loop->run();
        self::assertInstanceOf(\LogicException::class, $inner);
    }

    public function testExceptionInSourceIsReportedWithOrigin(): void
    {
        $loop = new GMainLoop();
        GLib::idle_add(function () use ($loop): bool {
            $loop->quit();
            throw new \RuntimeException('tick failed');
        });
        $got = $this->captureHandlerException(fn() => $loop->run());
        self::assertSame(['tick failed', 'GLib::idle_add', 0], $got);
    }

    public function testLoopStaysAliveWhileRunningWithoutPhpHandle(): void
    {
        $quit = null;
        $run = function () use (&$quit): void {
            $loop = new GMainLoop();
            $quit = static fn() => $loop->quit();
            GLib::idle_add(function () use (&$quit): bool {
                ($quit)();
                return false;
            });
            $loop->run();
        };
        $run();
        self::assertNotNull($quit);
    }
}
