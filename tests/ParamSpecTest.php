<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GObject;
use Gtk4\GParamSpec;

/** Gtk4\GParamSpec as delivered to notify handlers. */
final class ParamSpecTest extends GtkTestCase
{
    private function specFromNotify(): GParamSpec
    {
        $w = $this->window();
        $spec = null;
        $w->connect('notify::title', function (GObject $o, GParamSpec $p) use (&$spec): void {
            $spec = $p;
        });
        $w->set_title('t');
        self::assertInstanceOf(GParamSpec::class, $spec);
        return $spec;
    }

    public function testMetadata(): void
    {
        $spec = $this->specFromNotify();
        self::assertSame('title', $spec->get_name());
        self::assertSame('gchararray', $spec->get_value_type());
        self::assertTrue($spec->is_readable());
        self::assertTrue($spec->is_writable());
        self::assertSame(3, $spec->get_flags() & 3, 'READABLE|WRITABLE bits');
        self::assertNull($spec->get_default_value());
    }

    public function testDefaultValueOfNumericProperty(): void
    {
        $w = $this->window();
        $spec = null;
        $w->connect('notify::default-width', function (GObject $o, GParamSpec $p) use (&$spec): void {
            $spec = $p;
        });
        $w->set_default_size(50, 50);
        self::assertInstanceOf(GParamSpec::class, $spec);
        self::assertSame('gint', $spec->get_value_type());
        self::assertIsInt($spec->get_default_value());
    }

    public function testNotCloneable(): void
    {
        $spec = $this->specFromNotify();
        $this->expectException(\Error::class);
        $c = clone $spec;
        unset($c);
    }
}
