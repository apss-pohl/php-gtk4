<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GObject;

/** src/core/gsignal: connect(), marshalling of signal args, return values. */
final class SignalTest extends GtkTestCase
{
    public function testHandlerReceivesObjectSignalArgsAndUserData(): void
    {
        $w = $this->window();
        $args = [];
        $w->connect('notify::title', function (...$a) use (&$args): void {
            $args = $a;
        }, 'u1', 2);
        $w->set_title('x');

        self::assertCount(4, $args);
        self::assertSame($w, $args[0]);
        self::assertSame('title', $args[1], 'GParamSpec is exposed as its name for now');
        self::assertSame('u1', $args[2]);
        self::assertSame(2, $args[3]);
    }

    public function testConnectReturnsUsableHandlerId(): void
    {
        $w = $this->window();
        $calls = 0;
        $id = $w->connect('notify::title', function () use (&$calls): void {
            $calls++;
        });
        self::assertGreaterThan(0, $id);

        $w->set_title('a');
        $w->handler_disconnect($id);
        $w->set_title('b');
        self::assertSame(1, $calls);

        $w->handler_disconnect($id);  // second disconnect is a no-op, not a GLib critical
    }

    public function testConnectAfterRunsAfterNormalHandlers(): void
    {
        $w = $this->window();
        $order = [];
        $w->connect_after('notify::title', function () use (&$order): void {
            $order[] = 'after';
        });
        $w->connect('notify::title', function () use (&$order): void {
            $order[] = 'normal';
        });
        $w->set_title('x');
        self::assertSame(['normal', 'after'], $order);
    }

    public function testDetailedSignalOnlyFiresForItsDetail(): void
    {
        $w = $this->window();
        $n = 0;
        $w->connect('notify::title', function () use (&$n): void {
            $n++;
        });
        $w->set_property('resizable', false);
        $w->set_title('x');
        self::assertSame(1, $n);
    }

    public function testUndetailedNotifyFiresForEveryProperty(): void
    {
        $w = $this->window();
        $props = [];
        $w->connect('notify', function (GObject $o, string $pspec) use (&$props): void {
            $props[] = $pspec;
        });
        $w->set_property('resizable', false);
        $w->set_title('x');
        self::assertSame(['resizable', 'title'], $props);
    }

    public function testBooleanReturnValueStopsDefaultHandler(): void
    {
        $w = $this->window();
        $w->present();
        $w->connect('close-request', fn() => true);  // "handled": veto the close
        $w->close();
        self::assertTrue($w->get_property('visible'), 'returning true from close-request must veto');
    }

    public function testFalseReturnValueLetsDefaultHandlerRun(): void
    {
        $w = $this->window();
        $w->present();
        $w->connect('close-request', fn() => false);
        $w->close();
        self::assertFalse($w->get_property('visible'));
    }

    public function testUnknownSignalThrows(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage("unknown signal 'no-such-signal' on GtkWindow");
        $this->window()->connect('no-such-signal', fn() => null);
    }

    public function testNonCallableHandlerThrows(): void
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/must be a valid callback|must be of type callable/');
        self::opaque([$this->window(), 'connect'])('notify::title', 'definitely_not_a_function');
    }

    public function testMissingArgumentsAreRejected(): void
    {
        // PHP 8 semantics for internal functions: ArgumentCountError, not a warning.
        $this->expectException(\ArgumentCountError::class);
        self::opaque([$this->window(), 'connect'])('notify::title');
    }

    public function testHandlerOnDestroyedWindowStillWorksWhileHandleLives(): void
    {
        $w = $this->window();
        $w->destroy();
        $n = 0;
        $w->connect('notify::title', function () use (&$n): void {
            $n++;
        });
        $w->set_title('x');
        self::assertSame(1, $n);
    }

    public function testManyHandlersAndDisconnectsDoNotLeakOrCrash(): void
    {
        $w = $this->window();
        $ids = [];
        for ($i = 0; $i < 500; $i++) {
            $ids[] = $w->connect('notify::title', fn() => null, str_repeat('x', 100));
        }
        foreach ($ids as $id) {
            $w->handler_disconnect($id);
        }
        $w->set_title('x');
        self::assertSame('x', $w->get_title());
    }
}
