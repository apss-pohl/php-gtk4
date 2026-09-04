<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GObject;

/** src/core/gsignal: connect(), marshalling of signal args, return values. */
final class SignalTest extends GtkTestCase
{
    public function testHandlerReceivesObjectAndSignalArgs(): void
    {
        $w = $this->window();
        $args = [];
        $context = 'captured with use()';
        $w->connect('notify::title', function (...$a) use (&$args, $context): void {
            $args = [...$a, $context];
        });
        $w->set_title('x');

        self::assertCount(3, $args);
        self::assertSame($w, $args[0]);
        self::assertInstanceOf(\Gtk4\GParamSpec::class, $args[1]);
        self::assertSame('title', $args[1]->get_name());
        self::assertSame('captured with use()', $args[2]);
    }

    public function testExtraArgumentsAreRejected(): void
    {
        // php-gtk3 style user data is gone: closures capture context with use().
        $this->expectException(\ArgumentCountError::class);
        self::opaque([$this->window(), 'connect'])('notify::title', fn() => null, 'userdata');
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
        $w->connect('notify', function (GObject $o, \Gtk4\GParamSpec $pspec) use (&$props): void {
            $props[] = $pspec->get_name();
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

    public function testEmitDeliversArgumentsAndReturnValue(): void
    {
        $w = $this->window();
        $w->present();
        $w->connect('close-request', fn() => true);
        self::assertTrue($w->emit('close-request'), 'handler return value comes back');
        self::assertTrue($w->get_property('visible'), 'the veto really applied');
    }

    public function testEmitRejectsWrongArgumentCount(): void
    {
        $this->expectException(\ArgumentCountError::class);
        $this->window()->emit('close-request', 'extra');
    }

    public function testEmitUnknownSignalThrows(): void
    {
        $this->expectException(\ValueError::class);
        $this->window()->emit('no-such-signal');
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
            $pad = str_repeat('x', 100);
            $ids[] = $w->connect('notify::title', fn() => $pad);
        }
        foreach ($ids as $id) {
            $w->handler_disconnect($id);
        }
        $w->set_title('x');
        self::assertSame('x', $w->get_title());
    }
    /** list_signals(): the class', the ancestors' and the interfaces' signals, with their shape. */
    public function testListSignalsDescribesEverySignal(): void
    {
        $button = new \Gtk4\GtkButton();

        $signals = $button->list_signals();

        self::assertSame(
            ['params' => [], 'return' => null, 'action' => true, 'detailed' => false],
            $signals['clicked'],
        );
        self::assertSame(['GParam'], $signals['notify']['params']);   // GObject's, via the chain
        self::assertTrue($signals['notify']['detailed']);
        self::assertSame('gboolean', $signals['query-tooltip']['return']);
        self::assertSame(['gint', 'gint', 'gboolean', 'GtkTooltip'], $signals['query-tooltip']['params']);
    }
}
