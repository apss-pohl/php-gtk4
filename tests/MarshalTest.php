<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use PHPUnit\Framework\Attributes\DataProvider;

/** src/core/marshal: GValue <-> Php::Value through get/set_property. */
final class MarshalTest extends GtkTestCase
{
    /** @return iterable<string, array{string, mixed, mixed}> property, value to set, expected read-back */
    public static function scalarProperties(): iterable
    {
        yield 'string'          => ['title', 'hello', 'hello'];
        yield 'string null'     => ['title', null, null];
        yield 'int'             => ['default-width', 321, 321];
        // set_property() takes `mixed`, so the marshaller applies PHP's weak rules; a float
        // that would lose precision is E_DEPRECATED there, as it is for any int parameter.
        yield 'int from float'  => ['default-width', 12.0, 12];
        yield 'bool true'       => ['resizable', true, true];
        yield 'bool false'      => ['resizable', false, false];
        yield 'bool from int'   => ['resizable', 0, false];
        yield 'double from int' => ['opacity', 1, 1.0];
        yield 'enum from int'   => ['halign', 2, \Gtk4\GtkAlign::End];   // GTK_ALIGN_END -> PHP enum
        yield 'enum from case'  => ['halign', \Gtk4\GtkAlign::Center, \Gtk4\GtkAlign::Center];
    }

    #[DataProvider('scalarProperties')]
    public function testScalarRoundTrip(string $property, mixed $set, mixed $expected): void
    {
        $w = $this->window();
        $w->set_property($property, $set);
        $got = $w->get_property($property);
        self::assertSame($expected, $got);
    }

    public function testDoubleRoundTrip(): void
    {
        $w = $this->window();
        $w->set_property('opacity', 0.5);
        $got = $w->get_property('opacity');
        self::assertIsFloat($got);
        self::assertEqualsWithDelta(0.5, $got, 1 / 255, 'GTK quantizes opacity to 8 bit');
    }

    public function testReadBackTypesAreNative(): void
    {
        $w = $this->window();
        self::assertIsString($w->get_property('title') ?? '');
        self::assertIsInt($w->get_property('default-width'));
        self::assertIsBool($w->get_property('resizable'));
        self::assertIsFloat($w->get_property('opacity'));
        self::assertInstanceOf(\Gtk4\GtkAlign::class, $w->get_property('halign'), 'registered enums are PHP enums');
        self::assertIsInt($w->get_property('scale-factor'));
    }

    public function testUnknownPropertyThrows(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage("no property 'no-such-prop' on GtkWindow");
        $this->window()->get_property('no-such-prop');
    }

    public function testUnknownPropertyOnSetThrows(): void
    {
        $this->expectExceptionMessage("no property 'no-such-prop' on GtkWindow");
        $this->window()->set_property('no-such-prop', 1);
    }

    public function testUnsupportedGTypeIsAnExceptionNotACrash(): void
    {
        // GtkLabel:attributes is a PangoAttrList - a boxed type without a PHP class (yet).
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/PangoAttrList/');
        new \Gtk4\GtkLabel()->set_property('attributes', 'x');
    }

    public function testStringCoercion(): void
    {
        $w = $this->window();
        $w->set_property('title', 42);
        self::assertSame('42', $w->get_property('title'));
    }
}
