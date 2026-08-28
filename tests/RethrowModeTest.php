<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\ExceptionMode;
use Gtk4\GLib;
use Gtk4\GMainLoop;
use Gtk4\Gtk;
use Gtk4\GtkApplication;
use Gtk4\GtkOrientation;
use Gtk4\GtkSizeRequestMode;
use Gtk4\GtkWidget;

/** Gtk4\ExceptionMode::Rethrow - callback throwables propagate to PHP. */
final class RethrowModeTest extends GtkTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Gtk::set_exception_mode(ExceptionMode::Rethrow);
    }

    protected function tearDown(): void
    {
        Gtk::set_exception_mode(ExceptionMode::Log);
        parent::tearDown();
    }

    public function testDefaultModeIsLog(): void
    {
        Gtk::set_exception_mode(ExceptionMode::Log);
        self::assertSame(ExceptionMode::Log, Gtk::get_exception_mode());
        Gtk::set_exception_mode(ExceptionMode::Rethrow);
        self::assertSame(ExceptionMode::Rethrow, Gtk::get_exception_mode());
    }

    public function testSignalHandlerExceptionPropagatesFromEmittingCall(): void
    {
        $w = $this->window();
        $w->connect('notify::title', function (): void {
            throw new \DomainException('from handler', 3);
        });
        try {
            $w->set_title('x');
            self::fail('expected the handler exception to propagate');
        } catch (\DomainException $e) {
            self::assertSame('from handler', $e->getMessage());
            self::assertSame(3, $e->getCode());
        }
        self::assertSame('x', $w->get_title(), 'the C call itself completed');
    }

    public function testRemainingHandlersOfTheEmissionAreSkipped(): void
    {
        $w = $this->window();
        $second = false;
        $w->connect('notify::title', fn() => throw new \RuntimeException('first'));
        $w->connect('notify::title', function () use (&$second): void {
            $second = true;
        });
        try {
            $w->set_title('x');
        } catch (\RuntimeException) {
        }
        self::assertFalse($second);
    }

    public function testHandlerStillObservesBeforeRethrow(): void
    {
        $w = $this->window();
        $w->connect('notify::title', fn() => throw new \RuntimeException('observed'));
        $observed = null;
        Gtk::set_exception_handler(function (\Throwable $e, string $origin) use (&$observed): void {
            $observed = [$e->getMessage(), $origin];
        });
        try {
            $w->set_title('x');
        } catch (\RuntimeException) {
        }
        self::assertSame(['observed', 'notify::title'], $observed);
    }

    public function testMainLoopRunRethrowsAndStops(): void
    {
        $loop = new GMainLoop();
        $after = false;
        GLib::idle_add(fn() => throw new \LogicException('in idle'));
        GLib::timeout_add(50, function () use (&$after): bool {
            $after = true;
            return false;
        });
        try {
            $loop->run();
            self::fail('run() should rethrow');
        } catch (\LogicException $e) {
            self::assertSame('in idle', $e->getMessage());
        }
        self::assertFalse($loop->is_running(), 'loop was stopped');
        self::assertFalse($after, 'later sources did not run');
    }

    public function testApplicationRunRethrows(): void
    {
        $app = new GtkApplication(null, 1 << 5);
        $app->connect('activate', fn() => throw new \UnexpectedValueException('activate'));
        $this->expectException(\UnexpectedValueException::class);
        $app->run();
    }

    public function testThrowInsideNestedIterationIsParkedNotLeftPending(): void
    {
        // Inside a registered run(), PHP drives an unregistered nested loop with
        // main_context_iteration(). A throw there must not stay pending in the engine (C code
        // would keep running with it): it is parked, the *other* ready sources of that iteration
        // still run, and it surfaces from the main_context_iteration() call - the first boundary.
        $loop = new GMainLoop();
        $caught = null;
        $secondRan = false;
        $idleReturned = false;
        GLib::idle_add(function () use ($loop, &$caught, &$secondRan, &$idleReturned): bool {
            GLib::timeout_add(0, static function (): bool {
                throw new \DomainException('from nested iteration');
            });
            GLib::timeout_add(0, function () use (&$secondRan): bool {
                $secondRan = true;
                return false;
            });
            usleep(2000);   // both timeouts are due in the same iteration
            try {
                for ($i = 0; $i < 50 && $caught === null; $i++) {
                    GLib::main_context_iteration(true);
                }
            } catch (\DomainException $e) {
                $caught = $e;
            }
            $idleReturned = true;
            unset($loop);
            return false;
        });
        $loop->run();   // returns because report_pending_exception() quit the registered loop
        self::assertInstanceOf(\DomainException::class, $caught);
        self::assertSame('from nested iteration', $caught->getMessage());
        self::assertTrue($secondRan, 'handlers keep running while a Throwable is parked');
        self::assertTrue($idleReturned);
    }

    public function testNestedIterationHandlerObservesWithNestedOrigin(): void
    {
        $loop = new GMainLoop();
        $origins = [];
        Gtk::set_exception_handler(function (\Throwable $e, string $origin) use (&$origins): void {
            $origins[] = $origin;
        });
        GLib::idle_add(static function (): bool {
            GLib::timeout_add(0, static function (): bool {
                throw new \RangeException('x');
            });
            usleep(2000);
            try {
                GLib::main_context_iteration(true);
            } catch (\RangeException) {
            }
            return false;
        });
        $loop->run();
        Gtk::set_exception_handler(null);
        self::assertSame(['GLib::timeout_add'], $origins);
    }

    public function testCDrivenNestedLoopParksAndRunRethrowsWithPreviousChained(): void
    {
        // Needs the test hook: a nested loop driven from C with no PHP boundary in between.
        if (!str_contains((string) ini_get('gtk4.features'), 'testing=yes')) {
            self::markTestSkipped('needs --enable-gtk4-testing (FEATURES testing=yes)');
        }
        $loop = new GMainLoop();
        $afterNested = false;
        $secondRan = false;
        GLib::idle_add(function () use (&$afterNested, &$secondRan): bool {
            GLib::timeout_add(0, static function (): bool {
                throw new \DomainException('first');
            });
            GLib::timeout_add(0, static function (): bool {
                throw new \RangeException('second');
            });
            GLib::timeout_add(0, function () use (&$secondRan): bool {
                $secondRan = true;
                return false;
            });
            usleep(2000);
            Gtk::testing_iterate_nested(3);   // parks the first, chains the second
            $afterNested = true;   // C returned into PHP with nothing pending
            return false;
        });
        try {
            $loop->run();
            self::fail('run() must rethrow the parked Throwable');
        } catch (\DomainException $e) {
            self::assertSame('first', $e->getMessage());
            self::assertInstanceOf(\RangeException::class, $e->getPrevious());
            self::assertSame('second', $e->getPrevious()->getMessage());
        }
        self::assertTrue($afterNested, 'the nested loop finished normally');
        self::assertTrue($secondRan, 'handlers keep running while parked');
    }

    public function testCDrivenNestingWithoutRegisteredLoopPropagatesDirectly(): void
    {
        if (!str_contains((string) ini_get('gtk4.features'), 'testing=yes')) {
            self::markTestSkipped('needs --enable-gtk4-testing (FEATURES testing=yes)');
        }
        GLib::timeout_add(0, static function (): bool {
            throw new \LengthException('top level');
        });
        usleep(2000);
        $this->expectException(\LengthException::class);
        Gtk::testing_iterate_nested(5);   // no registered loop: pending, propagates from here
    }

    public function testErrorsPropagateToo(): void
    {
        $w = $this->window();
        $w->connect('notify::title', function (int $typed): void {
            // never reached: the first argument is a GObject, not an int
        });
        $this->expectException(\TypeError::class);
        $w->set_title('x');
    }

    public function testVfuncThunksReportOneThrowableOnce(): void
    {
        // Two PHP vfuncs run in one GTK call (measure() calls get_request_mode() and measure);
        // the first throws, the second must chain to GTK instead of reporting the same Throwable again.
        $w = new class extends GtkWidget {
            public int $calls = 0;

            public function vfunc_get_request_mode(): GtkSizeRequestMode
            {
                $this->calls++;
                throw new \RuntimeException('first');
            }

            public function vfunc_measure(GtkOrientation $orientation, int $for_size): array
            {
                $this->calls++;

                return [1, 1, -1, -1];
            }
        };
        $seen = 0;
        Gtk::set_exception_handler(function () use (&$seen): void {
            $seen++;
        });
        try {
            $w->measure(GtkOrientation::Horizontal, -1);
            self::fail('Rethrow mode must propagate the Throwable');
        } catch (\RuntimeException $e) {
            self::assertSame('first', $e->getMessage());
        } finally {
            Gtk::set_exception_handler(null);
        }
        self::assertSame(1, $seen, 'the handler saw the Throwable exactly once');
        self::assertSame(1, $w->calls, 'the second vfunc was skipped (exception pending)');
    }
}
