<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\Gtk;

/** src/core/error: the C++/PHP exception boundary. */
final class ErrorTest extends GtkTestCase
{
    public function testExceptionInHandlerIsRoutedToExceptionHandler(): void
    {
        $w = $this->window();
        $w->connect('notify::title', function (): void {
            throw new \RuntimeException('boom', 42);
        });
        $got = $this->captureHandlerException(fn() => $w->set_title('x'));
        self::assertSame(['boom', 'notify::title', 42], $got);
    }

    public function testErrorNotJustExceptionIsCaught(): void
    {
        $w = $this->window();
        $w->connect('notify::title', function (): void {
            throw new \Error('undefined_function_xyz');  // \Error, not \Exception
        });
        $got = $this->captureHandlerException(fn() => $w->set_title('x'));
        self::assertNotNull($got);
        self::assertStringContainsString('undefined_function_xyz', $got[0]);
        self::assertSame('notify::title', $got[1]);
    }

    public function testTypeErrorFromWrongHandlerSignatureIsCaught(): void
    {
        $w = $this->window();
        $w->connect('notify::title', function (int $mustBeInt): void {
            // never reached: 'title' is a string
        });
        $got = $this->captureHandlerException(fn() => $w->set_title('x'));
        self::assertNotNull($got);
        self::assertStringContainsString('must be of type int', $got[0]);
    }

    public function testExceptionDoesNotPropagateToEmitter(): void
    {
        $w = $this->window();
        $w->connect('notify::title', function (): void {
            throw new \RuntimeException('boom');
        });
        // No handler installed: falls back to g_critical() on stderr. Must not throw here.
        $w->set_title('x');
        self::assertSame('x', $w->get_title(), 'emitter continues after the failing handler');
    }

    public function testEmissionContinuesAfterFailingHandler(): void
    {
        $w = $this->window();
        $second = false;
        $w->connect('notify::title', function (): void {
            throw new \RuntimeException('first fails');
        });
        $w->connect('notify::title', function () use (&$second): void {
            $second = true;
        });
        $this->captureHandlerException(fn() => $w->set_title('x'));
        self::assertTrue($second, 'a failing handler must not stop emission');
    }

    public function testFailingReturnValueHandlerDoesNotVeto(): void
    {
        $w = $this->window();
        $w->present();
        $w->connect('close-request', function (): bool {
            throw new \RuntimeException('x');
        });
        $this->captureHandlerException(fn() => $w->close());
        self::assertFalse($w->get_property('visible'), 'failed handler must not block default behaviour');
    }

    public function testNullRemovesHandler(): void
    {
        $w = $this->window();
        $w->connect('notify::title', function (): void {
            throw new \RuntimeException('boom');
        });
        $calls = 0;
        Gtk::set_exception_handler(function () use (&$calls): void {
            $calls++;
        });
        $w->set_title('a');
        Gtk::set_exception_handler(null);
        $w->set_title('b');  // now goes to g_critical instead
        self::assertSame(1, $calls);
    }

    public function testNonCallableExceptionHandlerIsRejected(): void
    {
        $this->expectExceptionMessage('expects a callable or null');
        self::opaque([Gtk::class, 'set_exception_handler'])('not_a_function_at_all');
    }

    public function testThrowingExceptionHandlerFallsBackWithoutCrashing(): void
    {
        $w = $this->window();
        $w->connect('notify::title', function (): void {
            throw new \RuntimeException('inner');
        });
        Gtk::set_exception_handler(function (): void {
            throw new \LogicException('handler itself is broken');
        });
        $w->set_title('x');  // g_critical twice on stderr; process must survive
        self::assertSame('x', $w->get_title());
    }
}
