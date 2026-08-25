<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\ExceptionMode;
use Gtk4\GLib;
use Gtk4\GMainLoop;
use Gtk4\Gtk;
use Gtk4\GtkApplication;

/** Gtk4\ExceptionMode::Rethrow - callback throwables propagate to PHP. */
final class RethrowModeTest extends GtkTestCase
{
    protected function setUp(): void
    {
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

    public function testErrorsPropagateToo(): void
    {
        $w = $this->window();
        $w->connect('notify::title', function (int $typed): void {
            // never reached: the first argument is a GObject, not an int
        });
        $this->expectException(\TypeError::class);
        $w->set_title('x');
    }
}
