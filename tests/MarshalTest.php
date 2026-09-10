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

    /**
     * G_TYPE_STRV is a value mapping - `list<string>` in, `list<string>` out - and every
     * element crosses like a string parameter would: no null bytes, and a list, not a string.
     */
    public function testAStringListPropertyIsAListOfStrings(): void
    {
        $label = new \Gtk4\GtkLabel('x');
        $label->set_property('css-classes', ['a', 'b']);
        self::assertSame(['a', 'b'], $label->get_css_classes());
        self::assertSame(['a', 'b'], $label->css_classes, 'the property handler agrees');
        $label->css_classes = [];
        self::assertSame([], $label->get_property('css-classes'));

        try {
            $label->set_property('css-classes', ['a', "b\0c"]);
            self::fail('a null byte in an element');
        } catch (\ValueError $e) {
            self::assertStringContainsString('null byte', $e->getMessage());
        }
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('expected an array of strings');
        $label->set_property('css-classes', 'not-a-list');
    }

    // ---------------------------------------------------------------- the arms emit() reaches

    /**
     * G_TYPE_PARAM: `notify` carries a GParamSpec, a fundamental with a handle class of its own.
     * A spec that came out of one emission goes back in through emit() - the only way PHP hands
     * the marshaller a GParamSpec - and arrives as the same handle.
     */
    public function testAParamSpecTravelsThroughASignalArgument(): void
    {
        $w = $this->window();
        $spec = null;
        $w->connect('notify::title', function (\Gtk4\GObject $o, \Gtk4\GParamSpec $p) use (&$spec): void {
            $spec = $p;
        });
        $w->set_title('first');
        self::assertInstanceOf(\Gtk4\GParamSpec::class, $spec);

        $seen = null;
        $w->connect('notify', function (\Gtk4\GObject $o, \Gtk4\GParamSpec $p) use (&$seen): void {
            $seen = $p;
        });
        $w->emit('notify', $spec);
        self::assertSame($spec, $seen, 'the same GParamSpec, in and out');

        $this->expectException(\TypeError::class);
        $w->emit('notify', 'not a spec');
    }

    /**
     * A fundamental with a handle class (GdkEvent) and a GVariant both take null in a GValue:
     * `event` on a legacy controller carries a GdkEvent PHP can never build, and `activate`
     * on an action carries a GVariant that is null for a parameterless action.
     */
    public function testNullTravelsAsAFundamentalAndAsAVariant(): void
    {
        $controller = new \Gtk4\GtkEventControllerLegacy();
        $got = 'unset';
        $controller->connect('event', function (\Gtk4\GtkEventControllerLegacy $c, mixed $event) use (&$got): bool {
            $got = $event;
            return false;
        });
        $controller->emit('event', null);
        self::assertNull($got, 'a null GdkEvent arrives as null');

        $action = new \Gtk4\GSimpleAction('plain', null);
        $param = 'unset';
        $action->connect('activate', function (\Gtk4\GSimpleAction $a, mixed $p) use (&$param): void {
            $param = $p;
        });
        $action->emit('activate', null);
        self::assertNull($param, 'a null GVariant arrives as null');

        $this->expectException(\TypeError::class);
        $controller->emit('event', 'not an event');
    }

    /** G_TYPE_ULONG, read: a memory output stream's data-size is a gulong property. */
    public function testAnUnsignedLongPropertyReadsAsInt(): void
    {
        $stream = \Gtk4\GMemoryOutputStream::new_resizable();
        self::assertSame(0, $stream->data_size);
        $stream->write_bytes('seven!!', null);
        self::assertSame(7, $stream->get_property('data-size'));
        self::assertSame($stream->get_data_size(), $stream->data_size, 'the getter agrees');
    }

    /**
     * The 64-bit integers, both ways - GTK itself has no property or signal of either type, so
     * WebKit's are where the arms get exercised: page-id is a guint64 read and
     * Download::received-data carries a guint64 argument.
     */
    public function testTheWideIntegersTravelBothWays(): void
    {
        Features::requires('webkit');
        $view = new \Gtk4\WebKitWebView();
        self::assertGreaterThan(0, $view->page_id, 'a page id is assigned at construction');
        self::assertSame($view->get_page_id(), $view->page_id, 'the getter agrees');

        $download = $view->download_uri('about:blank');
        $got = 'unset';
        $download->connect('received-data', function (\Gtk4\WebKitDownload $d, int $length) use (&$got): void {
            $got = $length;
        });
        $download->emit('received-data', PHP_INT_MAX);
        self::assertSame(PHP_INT_MAX, $got, 'a guint64 argument round-trips at the top of the PHP range');
        $download->cancel();

        $this->expectException(\ValueError::class);
        $download->emit('received-data', -1);
    }
}
