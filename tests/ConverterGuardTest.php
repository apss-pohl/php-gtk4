<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GApplication;
use Gtk4\GApplicationFlags;
use Gtk4\GdkRectangle;
use Gtk4\GListModel;
use Gtk4\GObject;
use Gtk4\GSimpleAction;
use Gtk4\Gtk;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;
use Gtk4\GtkNoSelection;
use Gtk4\GtkStringList;
use Gtk4\GtkTextBuffer;

/**
 * The shared converters that sit behind the guarded method surface convert like a typed
 * parameter too: typed GVariant scalars, GStrv elements, a vfunc's return value, and emit()'s
 * (string, length) pairs. Each used to coerce raw - `-1` for a `u` reached GLib as 4294967295.
 */
final class ConverterGuardTest extends GtkTestCase
{
    private static int $apps = 0;

    private function app(): GApplication
    {
        $id = sprintf('org.phpgtk4.guard.p%d.n%d', getmypid(), self::$apps++);
        $app = new GApplication($id, GApplicationFlags::NON_UNIQUE);
        $app->register(null);

        return $app;
    }

    public function testAVariantIntegerHasToFitItsWidth(): void
    {
        $action = new GSimpleAction('n', 'u');

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('between 0 and 4294967295');
        $action->activate(-1);
    }

    public function testAVariantScalarIsStrictWhereTheCallerIs(): void
    {
        $action = new GSimpleAction('n', 'i');

        $this->expectException(\TypeError::class);
        $action->activate('12abc');
    }

    public function testAVariantBooleanTakesOnlyABoolUnderStrictTypes(): void
    {
        $action = new GSimpleAction('n', 'b');

        $this->expectException(\TypeError::class);
        $action->activate('no');
    }

    public function testAVariantDoubleWidensAnInt(): void
    {
        $received = null;
        $action = new GSimpleAction('n', 'd');
        $action->connect('activate', function (GSimpleAction $a, mixed $p) use (&$received): void {
            $received = $p;
        });

        $action->activate(2);

        self::assertSame(2.0, $received);
    }

    public function testAVariantStringTakesOnlyAStringUnderStrictTypes(): void
    {
        $action = new GSimpleAction('n', 's');

        $this->expectException(\TypeError::class);
        $action->activate(5);
    }

    public function testAStrvElementMustBeAValidString(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('null byte');
        new GtkStringList(["a\0b"]);
    }

    public function testAStrvElementMustBeUtf8(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('UTF-8');
        new GtkStringList(["\xff\xfe"]);
    }

    public function testAStrvElementIsStrictWhereTheCallerIs(): void
    {
        $this->expectException(\TypeError::class);
        new GtkStringList([1, 2]);
    }

    /** A PHP model answering -1 is a ValueError on the PHP side and 0 for GTK, not 4 billion. */
    public function testAVfuncIntegerReturnHasToFitTheCType(): void
    {
        $model = new class extends GObject implements GListModel {
            public function get_item_type(): string
            {
                return GObject::class;
            }

            public function get_n_items(): int
            {
                return -1;
            }

            public function get_item(int $position): ?GObject
            {
                return null;
            }
        };
        // GTK asks for the count as soon as it holds the model, hence the construction inside
        $seen = $this->captureHandlerException(function () use ($model): void {
            self::assertSame(0, new GtkNoSelection($model)->get_n_items());
        });

        self::assertNotNull($seen);
        self::assertStringContainsString('between 0 and', $seen[0]);
    }

    public function testEmitBoundsALengthByTheStringBeforeIt(): void
    {
        $buffer = new GtkTextBuffer(null);

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('byte length of the string before it (2)');
        $buffer->emit('insert-text', $buffer->get_start_iter(), 'ab', 200000);
    }

    public function testEmitAcceptsAWholeStringLength(): void
    {
        $buffer = new GtkTextBuffer(null);

        $buffer->emit('insert-text', $buffer->get_start_iter(), 'ab', -1);
        $buffer->emit('insert-text', $buffer->get_end_iter(), 'cd', 2);

        self::assertSame('abcd', $buffer->get_text($buffer->get_start_iter(), $buffer->get_end_iter(), true));
    }

    public function testTheDisposeHookTakesWidgetsOnly(): void
    {
        $this->expectException(\TypeError::class);
        self::opaque(Gtk::testing_run_dispose(...))($this->app());
    }

    /** A GObject property of a handle GTK disposed underneath: the same Error a method call gets. */
    public function testWritingAPropertyOfADisposedHandleIsAnError(): void
    {
        $button = GtkButton::new_with_label('before');
        Gtk::testing_run_dispose($button);

        $this->expectException(\Error::class);
        $this->expectExceptionMessage('disposed');
        $button->label = 'after';
    }

    public function testReadingAPropertyOfADisposedHandleIsAnError(): void
    {
        $button = GtkButton::new_with_label('before');
        Gtk::testing_run_dispose($button);

        self::assertFalse(isset($button->label), 'isset() answers, it does not throw');
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('disposed');
        self::assertNull($button->label);  // never reached: the read throws
    }

    public function testARectangleFieldConvertsLikeAnIntParameter(): void
    {
        $r = new GdkRectangle(1, 2, 3, 4);
        $x = self::opaque(static fn(): string => 'x')();  // the field, unseen by static analysis

        try {
            $r->$x = 'abc';
            self::fail('a string was accepted');
        } catch (\TypeError) {
        }
        self::assertSame(1, $r->x, 'and no dynamic property shadows the field');
        $this->expectException(\ValueError::class);
        $r->x = 2 ** 40;
    }

    public function testARectangleConstructorHasToFitAnInt(): void
    {
        $this->expectException(\ValueError::class);
        new GdkRectangle(PHP_INT_MAX);
    }

    /** GLib clamps what its pspec refuses and warns; PHP gets a ValueError and the old value stays. */
    public function testAPropertyWriteOutsideThePspecRangeIsAValueError(): void
    {
        $label = new GtkLabel('x');
        $label->xalign = 0.25;

        try {
            $label->set_property('xalign', -1.0);
            self::fail('accepted');
        } catch (\ValueError $e) {
            self::assertStringContainsString('GtkLabel::$xalign', $e->getMessage());
        }
        self::assertSame(0.25, $label->get_xalign());
        $this->expectException(\ValueError::class);
        $label->xalign = 5.0;
    }
}
