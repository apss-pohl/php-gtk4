<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\Gdk;
use PHPUnit\Framework\TestCase;

/**
 * Gtk4\Gdk: the namespace's `<constant>`s as class constants (gen/gir/emit-record.php
 * emitConstants), the C GDK_* names without their prefix. Values are literal in the stub, so a
 * handful are pinned against gdkkeysyms.h / gdkenums.h here, where a wrong one would be silent.
 */
final class GdkConstantsTest extends TestCase
{
    public function testKeysymsAreTheX11Values(): void
    {
        self::assertSame(0xff0d, Gdk::KEY_Return);
        self::assertSame(0xff1b, Gdk::KEY_Escape);
        self::assertSame(0x020, Gdk::KEY_space);
        self::assertSame(0x030, Gdk::KEY_0);
        self::assertSame(0x061, Gdk::KEY_a);
        self::assertSame(0x041, Gdk::KEY_A);
        self::assertSame(0xffbe, Gdk::KEY_F1);
        self::assertSame(0xff8d, Gdk::KEY_KP_Enter);
        self::assertSame(0xffffff, Gdk::KEY_VoidSymbol);
    }

    public function testTheNonKeyConstantsAreThere(): void
    {
        self::assertSame(1, Gdk::BUTTON_PRIMARY);
        self::assertSame(2, Gdk::BUTTON_MIDDLE);
        self::assertSame(3, Gdk::BUTTON_SECONDARY);
        self::assertSame(0, Gdk::CURRENT_TIME);
        self::assertSame([true, false], [Gdk::EVENT_STOP, Gdk::EVENT_PROPAGATE]);
        // GDK_SHIFT|LOCK|CONTROL|ALT|SUPER|HYPER|META|BUTTON1..5, as gdkenums.h ors them
        self::assertSame(0x1c001f0f, Gdk::MODIFIER_MASK);
    }

    public function testItIsAllOfThemAndNothingElse(): void
    {
        $class = new \ReflectionClass(Gdk::class);
        self::assertTrue($class->isFinal());
        self::assertSame([], $class->getMethods(), 'constants only');
        $constants = $class->getConstants();
        self::assertGreaterThan(2000, count($constants), 'gdkkeysyms.h alone has over two thousand');
        foreach ($constants as $name => $value) {
            self::assertMatchesRegularExpression('/^[A-Z][A-Za-z0-9_]*$/', $name);
            self::assertTrue(is_int($value) || is_bool($value), "$name is int or bool");
        }
        self::assertSame(Gdk::KEY_Return, constant(Gdk::class . '::KEY_Return'), 'reachable by name too');
    }
}
