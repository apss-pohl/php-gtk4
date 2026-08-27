<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkRectangle;
use Gtk4\GdkRGBA;
use Gtk4\GtkButton;
use Gtk4\GtkLabel;

/** src/core/boxed: value-type handles, field properties, GStrv <-> array. */
final class BoxedTest extends GtkTestCase
{
    public function testRgbaParseAndFields(): void
    {
        $c = new GdkRGBA('#ff0000');
        self::assertEqualsWithDelta(1.0, $c->red, 1e-6);
        self::assertEqualsWithDelta(0.0, $c->green, 1e-6);
        self::assertEqualsWithDelta(1.0, $c->alpha, 1e-6);
        self::assertTrue($c->is_opaque());
        self::assertSame('rgb(255,0,0)', $c->to_string());

        $c->alpha = 0.5;
        self::assertFalse($c->is_opaque());
        self::assertSame('rgba(255,0,0,0.5)', $c->to_string());
        self::assertTrue($c->parse('blue'));
        self::assertFalse($c->parse('not a colour'));
        self::assertEqualsWithDelta(1.0, $c->blue, 1e-6, 'a failed parse leaves the previous value (blue) intact');
    }

    public function testRgbaDefaultsAndInvalid(): void
    {
        $c = new GdkRGBA();
        self::assertSame('rgba(0,0,0,0)', $c->to_string());
        $this->expectException(\ValueError::class);
        new GdkRGBA('#nope');
    }

    public function testBoxedIsAValueType(): void
    {
        $a = new GdkRGBA('#00ff00');
        $b = clone $a;
        self::assertNotSame($a, $b);
        self::assertTrue($a->equal($b));
        self::assertSame(0, self::compare($a, $b), 'compared by value');
        $b->green = 0.5;
        self::assertFalse($a->equal($b), 'clone is an independent copy');
        self::assertNotSame(0, self::compare($a, $b));
    }

    /** The engine's compare handler, without static analysis folding the comparison. */
    private static function compare(object $a, object $b): int
    {
        return $a <=> $b;
    }

    public function testRectangle(): void
    {
        $r = new GdkRectangle(10, 20, 100, 50);
        self::assertSame([10, 20, 100, 50], [$r->x, $r->y, $r->width, $r->height]);
        $r->width = 200;
        self::assertSame(200, $r->width);
        self::assertTrue($r->contains_point(15, 25));
        self::assertFalse($r->contains_point(0, 0));

        $other = new GdkRectangle(150, 30, 100, 100);
        $i = $r->intersect($other);
        self::assertInstanceOf(GdkRectangle::class, $i);
        self::assertSame([150, 30, 60, 40], [$i->x, $i->y, $i->width, $i->height]);
        self::assertNull($r->intersect(new GdkRectangle(1000, 1000, 1, 1)));
        $u = $r->union($other);
        self::assertSame([10, 20, 240, 110], [$u->x, $u->y, $u->width, $u->height]);
        self::assertTrue($u->equal(new GdkRectangle(10, 20, 240, 110)));
        $zero = new GdkRectangle();
        self::assertSame([0, 0, 0, 0], [$zero->x, $zero->y, $zero->width, $zero->height]);
    }

    public function testCompoundAssignmentOnFieldWritesThrough(): void
    {
        $c = new GdkRGBA();
        $c->red = 0.5;
        $c->red += 0.25;
        self::assertSame(0.75, $c->red);
        $r = new GdkRectangle();
        $r->width = 10;
        $r->width++;
        self::assertSame(11, $r->width);
    }

    public function testUnsetFieldThrows(): void
    {
        $r = new GdkRectangle();
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('Cannot unset boxed field Gtk4\GdkRectangle::$x');
        unset($r->x);
    }

    public function testVarDumpShowsFields(): void
    {
        ob_start();
        var_dump(new GdkRectangle(1, 2, 3, 4));
        $dump = (string) ob_get_clean();
        self::assertStringContainsString('["width"]=>', $dump);
        self::assertStringContainsString('int(3)', $dump);
    }

    public function testUnknownFieldFallsBackToStandardHandlers(): void
    {
        $r = new GdkRectangle();
        self::assertFalse(isset($r->nope));
        self::assertTrue(isset($r->width));
    }

    public function testWrongBoxedTypeIsATypeError(): void
    {
        $this->expectException(\TypeError::class);
        self::opaque([new GdkRectangle(), 'equal'])(new GdkRGBA());
    }

    public function testStrvPropertyRoundTrip(): void
    {
        $b = new GtkButton('labelled');
        self::assertContains('text-button', $b->get_css_classes(), 'GTK sets its own classes');
        $b->set_css_classes(['a', 'b']);
        self::assertSame(['a', 'b'], $b->get_css_classes());
        self::assertSame(['a', 'b'], $b->get_property('css-classes'), 'GStrv -> list<string>');
        $b->set_property('css-classes', ['x']);
        self::assertSame(['x'], $b->css_classes, 'list<string> -> GStrv, and @property access');
        $b->css_classes = [];
        self::assertSame([], $b->get_css_classes());
    }

    public function testStrvRejectsNonArray(): void
    {
        $this->expectException(\TypeError::class);
        new GtkButton()->set_property('css-classes', 'not-an-array');
    }

    public function testUnregisteredBoxedTypeIsATypeErrorNotACrash(): void
    {
        // GtkLabel:attributes is a PangoAttrList - a boxed type with no PHP class yet.
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessageMatches('/PangoAttrList/');
        new GtkLabel()->set_property('attributes', 'x');
    }
}
