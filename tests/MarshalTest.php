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
        yield 'bool true'       => ['resizable', true, true];
        yield 'bool false'      => ['resizable', false, false];
        yield 'double from int' => ['opacity', 1, 1.0];  // widening: allowed under strict_types too
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

    /**
     * The value converts like the equivalent setter's parameter, under the rules of the file that
     * makes the call: weak coercion where strict_types is not declared (an eval'd closure runs
     * without this file's declaration), a TypeError here.
     *
     * @return iterable<string, array{string, mixed, mixed}>
     */
    public static function coercedProperties(): iterable
    {
        yield 'int from float' => ['default-width', 12.0, 12];
        yield 'bool from int'  => ['resizable', 0, false];
        yield 'string from int' => ['title', 42, '42'];
    }

    #[DataProvider('coercedProperties')]
    public function testWeakCallersAreCoerced(string $property, mixed $set, mixed $expected): void
    {
        $w = $this->window();
        $weak = eval('return static fn(\Gtk4\GObject $o, string $n, mixed $v) => $o->set_property($n, $v);');
        self::assertIsCallable($weak);

        $weak($w, $property, $set);

        self::assertSame($expected, $w->get_property($property));
    }

    #[DataProvider('coercedProperties')]
    public function testStrictCallersAreNot(string $property, mixed $set, mixed $expected): void
    {
        $w = $this->window();
        self::assertNotSame($set, $expected, 'the row is a coercion');

        $this->expectException(\TypeError::class);
        $w->set_property($property, $set);
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

    /**
     * G_TYPE_GTYPE: GListStore:item-type is the only GType-valued property PHP can reach.
     * It comes back as the numeric GType, which is what the marshaller has to offer - there
     * is no PHP class for a GType itself.
     */
    /** A `GType` property is a type name, as the constructor takes it and get_item_type() answers. */
    public function testGTypePropertyIsATypeName(): void
    {
        $store = new \Gtk4\GListStore(\Gtk4\PhpValue::class);

        self::assertSame('PhpValue', $store->get_property('item-type'));
        self::assertSame($store->get_item_type(), $store->item_type, 'the property handler agrees');

        $filter = new \Gtk4\GtkFilterListModel(null, null);
        $filter->set_property('model', new \Gtk4\GListStore('GtkLabel'));
        self::assertSame('GtkLabel', $filter->get_model()?->get_item_type());
    }

    /**
     * G_TYPE_VARIANT, both ways: core/variant converts on the way in and on the way back out,
     * so a stateful action's state is a plain PHP value at the property boundary.
     */
    public function testVariantPropertyRoundTrip(): void
    {
        $action = \Gtk4\GSimpleAction::new_stateful('mode', null, 'hello');
        self::assertSame('hello', $action->get_property('state'));

        $action->set_property('state', 'world');
        self::assertSame('world', $action->get_property('state'));
        self::assertSame('world', $action->state, 'the property handler agrees with get_property()');
    }

    /**
     * A boxed type with a bound class travels in a GValue as itself: GtkPopover:pointing-to is
     * a GdkRectangle in and a GdkRectangle out, not an array and not an opaque handle.
     */
    public function testBoxedPropertyRoundTrip(): void
    {
        $popover = new \Gtk4\GtkPopover();
        $popover->set_property('pointing-to', new \Gtk4\GdkRectangle(1, 2, 3, 4));

        $got = $popover->get_property('pointing-to');
        self::assertInstanceOf(\Gtk4\GdkRectangle::class, $got);
        self::assertSame([1, 2, 3, 4], [$got->x, $got->y, $got->width, $got->height]);
    }

    /**
     * A `GVariantType` property is its type string (GSimpleAction's `parameter-type` and
     * `state-type`), both ways: read as a string or null, and written as the construct
     * property a PHP subclass' constructor hands to g_object_new() - the only writable shape
     * GLib gives it. (A string that is no type string is refused by the constructor itself; the
     * marshaller's own refusal is what PhpActionTest sees on an interface property.)
     */
    public function testVariantTypePropertyIsATypeString(): void
    {
        $action = new \Gtk4\GSimpleAction('typed', 's');

        self::assertSame('s', $action->parameter_type);
        self::assertNull($action->state_type);
        self::assertSame('s', $action->get_property('parameter-type'));
        self::assertSame('b', \Gtk4\GSimpleAction::new_stateful('flag', null, true)->state_type);

        $sub = new class ('sub', 'as') extends \Gtk4\GSimpleAction {
            // a PHP subtype: the constructor arguments travel as construct properties
        };
        self::assertSame('as', $sub->parameter_type);
    }
}
