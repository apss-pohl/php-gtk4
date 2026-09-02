<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkTexture;
use Gtk4\GListStore;
use Gtk4\GSimpleAction;
use Gtk4\GtkEntry;
use Gtk4\GtkLabel;
use Gtk4\GtkTextBuffer;
use Gtk4\GtkTextIter;
use Gtk4\GtkWindow;
use Gtk4\PhpValue;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * The PHP -> C argument boundary (`check_utf8` / `check_range` in src/php_gtk4.h, the depth
 * and cycle guards in src/core/variant.cpp). A PHP value is 64-bit, signed and may hold any
 * bytes; a GTK parameter is usually none of those. Everything here used to be accepted and
 * then went wrong somewhere the caller could not see it - two of them by killing the process.
 *
 * Found by the security review on 2026-08-30; each test names what happened before.
 */
final class ArgumentGuardTest extends GtkTestCase
{
    // ---------------------------------------------------------------- GVariant recursion

    /** `$a = [1]; $a[] = &$a;` recursed until the C stack was gone: SIGSEGV. */
    /**
     * It pins the individual cases where GTK refuses a value PHP can build; several are only
     * reachable *through* GTK's precondition (a reversed get_chars() range answers NULL).
     */
    protected function toleratesGtkCriticals(): bool
    {
        return true;
    }

    public function testSelfReferentialArrayIsRejected(): void
    {
        $a = [1];
        $a[] = &$a;
        $action = new GSimpleAction('x', 'v');

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('array contains itself');
        $action->activate($a);
    }

    /** 20 000 levels was a SIGSEGV; a GVariant cannot be deeper than 64 anyway. */
    public function testTooDeeplyNestedArrayIsRejected(): void
    {
        $deep = 1;
        for ($i = 0; $i < 5_000; $i++) {
            $deep = [$deep];
        }
        $action = new GSimpleAction('x', 'v');

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('nested too deeply');
        $action->activate($deep);
    }

    /** The cap is on pathological depth, not on ordinary structures. */
    public function testOrdinaryNestingStillConverts(): void
    {
        $action = new GSimpleAction('x', 'v');
        $seen = null;
        $action->connect('activate', function (GSimpleAction $a, mixed $p) use (&$seen): void {
            $seen = $p;
        });
        $action->activate(['name' => ['a', 'b'], 'flags' => [1, 2, 3], 'on' => true]);

        self::assertSame(['name' => ['a', 'b'], 'flags' => [1, 2, 3], 'on' => true], $seen);
    }

    // ---------------------------------------------------------------- strings

    /**
     * A GLib string ends at the first NUL, so PHP saw "safe\0evil" and the window title said
     * "safe" - a mismatch worth having wherever a title is built from data.
     */
    public function testEmbeddedNullByteIsRejectedInAnArgument(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must not contain a null byte');
        $this->window()->set_title("safe\0evil");
    }

    public function testEmbeddedNullByteIsRejectedInAPropertyWrite(): void
    {
        $window = $this->window();
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('null byte');
        $window->title = "a\0b";
    }

    /** Invalid UTF-8 used to reach GLib, which dropped the value with a CRITICAL on stderr. */
    public function testInvalidUtf8IsRejected(): void
    {
        $label = new GtkLabel('x');
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be valid UTF-8');
        $label->set_text("\xff\xfe not text");
    }

    public function testInvalidUtf8IsRejectedInAVariant(): void
    {
        $action = new GSimpleAction('x', 's');
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('valid UTF-8');
        $action->activate("\xff\xfe");
    }

    public function testValidUtf8Passes(): void
    {
        $window = $this->window();
        $window->set_title('Grüße ✓ 日本語');
        self::assertSame('Grüße ✓ 日本語', $window->get_title());
    }

    /** A GBytes parameter is binary by definition and must not be validated as text. */
    public function testBinaryDataIsStillAcceptedWhereItIsBytes(): void
    {
        $texture = GdkTexture::new_from_bytes(PngFixture::red(1, 1));
        self::assertSame(1, $texture->get_width());
    }

    // ---------------------------------------------------------------- property writes

    /**
     * `$win->title = 'x'` is the idiom the README teaches, and it used to be the one API
     * surface with no type checking at all: `$win->default_width = 'garbage'` stored 0,
     * PHP_INT_MAX stored -1 and strict_types was ignored. It now converts exactly like the
     * equivalent setter's parameter does.
     *
     * @return iterable<string, array{string, mixed, class-string<\Throwable>}>
     */
    public static function refusedPropertyWrites(): iterable
    {
        yield 'non-numeric string into int' => ['default_width', 'garbage', \TypeError::class];
        yield 'float into int (strict)' => ['default_width', 3.7, \TypeError::class];
        yield 'bool into int (strict)' => ['default_width', true, \TypeError::class];
        yield 'out of range for gint' => ['default_width', PHP_INT_MAX, \ValueError::class];
        yield 'non-numeric string into double' => ['opacity', 'x', \TypeError::class];
        yield 'int into string (strict)' => ['title', 42, \TypeError::class];
        yield 'string into bool (strict)' => ['resizable', 'yes', \TypeError::class];
    }

    /** @param class-string<\Throwable> $expected */
    #[DataProvider('refusedPropertyWrites')]
    public function testPropertyWritesConvertLikeAParameter(
        string $property,
        mixed $value,
        string $expected,
    ): void {
        $window = $this->window();
        $this->expectException($expected);
        $window->{$property} = $value;
    }

    public function testPropertyWritesStillTakeWhatTheTypeAllows(): void
    {
        $window = $this->window();
        $window->default_width = 320;          // int into gint
        $window->opacity = 1;                  // int into gdouble is a widening conversion
        $window->title = 'plain';
        $window->resizable = false;
        self::assertSame(320, $window->default_width);
        self::assertEqualsWithDelta(1.0, $window->opacity, 1e-9);
        self::assertSame('plain', $window->title);
        self::assertFalse($window->resizable);
    }

    // ---------------------------------------------------------------- integers

    /** -1 reached g_list_store_remove() as 4294967295 and only GLib's assertion caught it. */
    public function testNegativeUnsignedArgumentIsRejected(): void
    {
        $store = new GListStore('PhpValue');
        $store->append(new PhpValue(1));

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be between 0 and 4294967295');
        $store->remove(-1);
    }

    /** PHP_INT_MAX truncated to -1, which GTK reads as "natural size". */
    public function testTooLargeArgumentIsRejected(): void
    {
        $label = new GtkLabel('x');
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be between -2147483648 and 2147483647');
        $label->set_size_request(PHP_INT_MAX, 10);
    }

    /** -1 is a real value for a signed parameter (GTK: "whatever you need"). */
    public function testNegativeSignedArgumentStillPasses(): void
    {
        $label = new GtkLabel('x');
        $label->set_size_request(-1, -1);
        self::assertSame([-1, -1], $label->get_size_request());
    }

    // ---------------------------------------------------------------- GtkTextIter

    /**
     * A negative index is `g_error("Byte index -1 is off the end of the line")` inside GTK,
     * which aborts the process - the two methods that take one are guarded by hand
     * (gen/overrides/Gtk.TextIter.set_line_index.cpp and .set_line_offset.cpp).
     *
     * @param callable(\Gtk4\GtkTextIter): void $call
     */
    #[DataProvider('negativeIterIndexes')]
    public function testNegativeLineIndexIsRejected(callable $call): void
    {
        $buffer = new GtkTextBuffer();
        $buffer->set_text('hello world', -1);

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must be greater than or equal to 0');
        $call($buffer->get_start_iter());
    }

    /** @return iterable<string, array{callable(\Gtk4\GtkTextIter): void}> */
    public static function negativeIterIndexes(): iterable
    {
        yield 'set_line_index' => [static fn(GtkTextIter $i) => $i->set_line_index(-1)];
        yield 'set_line_offset' => [static fn(GtkTextIter $i) => $i->set_line_offset(-1)];
    }

    /** The guard is on negative values only; the end of the line stays reachable. */
    public function testLineIndexWithinTheLineStillPasses(): void
    {
        $buffer = new GtkTextBuffer();
        $buffer->set_text('hello world', -1);
        $iter = $buffer->get_start_iter();

        $iter->set_line_index(5);
        self::assertSame(5, $iter->get_offset());
        $iter->set_line_offset(0);
        self::assertSame(0, $iter->get_offset());
    }

    /**
     * GTK refuses `end < start` with a precondition and returns NULL; the declared return
     * type is `string`, and reading that NULL back was a SIGSEGV.
     */
    public function testReversedRangeReturnsAnEmptyStringInsteadOfCrashing(): void
    {
        $entry = new GtkEntry();
        $entry->set_text('hello');

        self::assertSame('hello', $entry->get_chars(0, -1));
        self::assertSame('', $entry->get_chars(1, 0));
    }

    public function testWindowTitleRoundTripIsUnaffected(): void
    {
        $window = new GtkWindow();
        $window->set_title('plain');
        self::assertSame('plain', $window->get_title());
        $window->destroy();
    }
}
