<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkRGBA;
use Gtk4\GraphenePoint;
use Gtk4\GrapheneRect;
use Gtk4\GskColorNode;
use Gtk4\GskTransform;
use Gtk4\GskTransformCategory;
use Gtk4\GskTransformNode;
use Gtk4\GtkFixed;
use Gtk4\GtkLabel;

/**
 * GskTransform, the immutable 2D/3D transform GSK and GtkFixed work with: every operation
 * answers with a new transform (GIR's `next` parameter consumes its receiver, which the boxed
 * emitter hands over as a copy so the PHP handle keeps its value). parse() is an override over
 * GIR's out parameter.
 */
final class GskTransformTest extends GtkTestCase
{
    public function testOperationsChainIntoNewTransforms(): void
    {
        $identity = new GskTransform();
        self::assertSame('none', $identity->to_string());
        self::assertSame(GskTransformCategory::Identity, $identity->get_category());
        $moved = $identity->translate(new GraphenePoint(5.0, 5.0));
        self::assertNotNull($moved);
        $scaled = $moved->scale(2.0, 2.0);
        self::assertNotNull($scaled);
        self::assertSame('translate(5, 5) scale(2)', $scaled->to_string());
        self::assertSame('translate(5, 5)', $moved->to_string(), 'the receiver kept its value');
        self::assertSame('none', $identity->to_string());
        self::assertSame(GskTransformCategory::DAffine2, $scaled->get_category());
    }

    public function testParseAndEquality(): void
    {
        $parsed = GskTransform::parse('translate(3, 4) scale(2)');
        self::assertNotNull($parsed);
        self::assertSame('translate(3, 4) scale(2)', $parsed->to_string());
        $built = new GskTransform()->translate(new GraphenePoint(3.0, 4.0))?->scale(2.0, 2.0);
        self::assertNotNull($built);
        self::assertTrue($parsed->equal($built));
        self::assertTrue($parsed == $built, '== compares by value');
        self::assertFalse($parsed->equal(new GskTransform()));
        self::assertNull(GskTransform::parse('rotate('), 'not a transform');
        self::assertNull(GskTransform::parse(''), 'the identity is GSK\'s NULL');
    }

    public function testTransformingGeometry(): void
    {
        $t = new GskTransform()->translate(new GraphenePoint(10.0, 20.0))?->scale(2.0, 3.0);
        self::assertNotNull($t);
        $point = $t->transform_point(new GraphenePoint(1.0, 1.0));
        self::assertSame([12.0, 23.0], [$point->x, $point->y]);
        $bounds = $t->transform_bounds(GrapheneRect::alloc()->init(0.0, 0.0, 1.0, 1.0));
        self::assertSame([10.0, 20.0, 2.0, 3.0], [
            $bounds->get_x(), $bounds->get_y(), $bounds->get_width(), $bounds->get_height(),
        ]);
        $inverse = $t->invert();
        self::assertNotNull($inverse);
        $back = $inverse->transform_point($point);
        self::assertEqualsWithDelta(1.0, $back->x, 1e-5);
        self::assertEqualsWithDelta(1.0, $back->y, 1e-5);
        $rotated = new GskTransform()->rotate(90.0);
        self::assertNotNull($rotated);
        self::assertSame(GskTransformCategory::D2, $rotated->get_category(), 'a rotation is a general 2D transform');
    }

    public function testATransformNodeAndAFixedChildCarryOne(): void
    {
        $t = new GskTransform()->translate(new GraphenePoint(1.0, 2.0));
        self::assertNotNull($t);
        $node = new GskTransformNode(
            new GskColorNode(new GdkRGBA('red'), GrapheneRect::alloc()->init(0.0, 0.0, 4.0, 4.0)),
            $t,
        );
        self::assertSame('translate(1, 2)', $node->get_transform()->to_string());
        self::assertSame(1.0, $node->get_bounds()->get_x(), 'the child bounds, transformed');

        $fixed = new GtkFixed();
        $label = new GtkLabel('x');
        $fixed->put($label, 0.0, 0.0);
        $fixed->set_child_transform($label, $t);
        self::assertSame('translate(1, 2)', $fixed->get_child_transform($label)?->to_string());
        $fixed->set_child_transform($label, null);
        self::assertNull($fixed->get_child_transform($label));
    }

    public function testAFixedRefusesATransformForSomebodyElsesChild(): void
    {
        $fixed = new GtkFixed();
        $stranger = new GtkLabel('not mine');
        $this->expectException(\LogicException::class);
        $fixed->set_child_transform($stranger, new GskTransform());
    }
}
