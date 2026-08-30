<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkTexture;
use Gtk4\GListStore;
use Gtk4\GSimpleAction;
use Gtk4\GtkLabel;
use Gtk4\GtkWindow;
use Gtk4\PhpValue;

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

    public function testWindowTitleRoundTripIsUnaffected(): void
    {
        $window = new GtkWindow();
        $window->set_title('plain');
        self::assertSame('plain', $window->get_title());
        $window->destroy();
    }
}
