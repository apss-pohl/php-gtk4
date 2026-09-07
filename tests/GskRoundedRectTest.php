<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkRGBA;
use Gtk4\GraphenePoint;
use Gtk4\GrapheneRect;
use Gtk4\GskColorNode;
use Gtk4\GskContainerNode;
use Gtk4\GskCorner;
use Gtk4\GskInsetShadowNode;
use Gtk4\GskRoundedClipNode;
use Gtk4\GskRoundedRect;
use Gtk4\GtkSnapshot;

/**
 * The hand-written GskRoundedRect (src/Gsk/GskRoundedRect.cpp): GSK's struct has no GType, so the
 * binding registers a boxed one - the value handle behaves like GdkRectangle, and the generated
 * code that takes or returns the struct (clip nodes, borders, shadows, the snapshot) goes
 * through it. The GSK calls that rewrite the struct answer with copies.
 */
final class GskRoundedRectTest extends GtkTestCase
{
    private static function bounds(): GrapheneRect
    {
        return GrapheneRect::alloc()->init(0.0, 0.0, 40.0, 30.0);
    }

    public function testCornersAndPredicates(): void
    {
        $rect = new GskRoundedRect(self::bounds(), 5.0, 5.0, 0.0, 0.0);
        self::assertSame(40.0, $rect->get_bounds()->get_width());
        self::assertSame(5.0, $rect->get_corner(GskCorner::TopLeft)->width);
        self::assertSame(5.0, $rect->get_corner(GskCorner::TopRight)->height);
        self::assertSame(0.0, $rect->get_corner(GskCorner::BottomRight)->width);
        self::assertFalse($rect->is_rectilinear());
        self::assertTrue(new GskRoundedRect(self::bounds())->is_rectilinear(), 'all radii default to 0');
        self::assertFalse($rect->contains_point(new GraphenePoint(0.5, 0.5)), 'cut off by the corner');
        self::assertTrue($rect->contains_point(new GraphenePoint(20.0, 15.0)));
        self::assertTrue($rect->contains_rect(GrapheneRect::alloc()->init(10.0, 10.0, 5.0, 5.0)));
        self::assertTrue($rect->intersects_rect(GrapheneRect::alloc()->init(-5.0, -5.0, 10.0, 10.0)));
        self::assertFalse($rect->intersects_rect(GrapheneRect::alloc()->init(100.0, 100.0, 1.0, 1.0)));
    }

    public function testOperationsAnswerWithCopies(): void
    {
        $rect = new GskRoundedRect(self::bounds(), 4.0, 4.0, 4.0, 4.0);
        $moved = $rect->offset(10.0, 5.0);
        self::assertSame(0.0, $rect->get_bounds()->get_x(), 'the receiver is untouched');
        self::assertSame([10.0, 5.0], [$moved->get_bounds()->get_x(), $moved->get_bounds()->get_y()]);
        $shrunk = $rect->shrink(2.0, 2.0, 2.0, 2.0);
        self::assertSame(36.0, $shrunk->get_bounds()->get_width());
        self::assertSame(2.0, $shrunk->get_corner(GskCorner::TopLeft)->width, 'the corners shrink with it');
        $odd = new GskRoundedRect(GrapheneRect::alloc()->init(0.0, 0.0, 4.0, 4.0), 100.0, 100.0, 100.0, 100.0);
        self::assertLessThanOrEqual(4.0, $odd->normalize()->get_corner(GskCorner::TopLeft)->width, 'clamped');
    }

    public function testValueSemantics(): void
    {
        $a = new GskRoundedRect(self::bounds(), 1.0, 2.0, 3.0, 4.0);
        $b = new GskRoundedRect(self::bounds(), 1.0, 2.0, 3.0, 4.0);
        self::assertTrue($a->equal($b));
        self::assertTrue($a == $b);
        self::assertNotSame($a, $b);
        $copy = clone $a;
        self::assertTrue($copy == $a);
        self::assertFalse($a == $a->offset(1.0, 0.0));
        self::assertFalse($a->equal(new GskRoundedRect(self::bounds(), 1.0, 2.0, 3.0, 5.0)));
    }

    public function testANegativeRadiusIsAValueError(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Argument #3 ($top_right) must be greater than or equal to 0');
        new GskRoundedRect(self::bounds(), 1.0, -1.0);
    }

    public function testTheNodesAndTheSnapshotTakeIt(): void
    {
        $rect = new GskRoundedRect(self::bounds(), 8.0, 8.0, 8.0, 8.0);
        $paint = new GskColorNode(new GdkRGBA('red'), self::bounds());
        $clip = new GskRoundedClipNode($paint, $rect);
        self::assertTrue($clip->get_clip()->equal($rect), 'the node answers with an equal value');
        self::assertSame($paint, $clip->get_child());
        $inset = new GskInsetShadowNode($rect, new GdkRGBA('black'), 1.0, 1.0, 0.0, 2.0);
        self::assertSame(2.0, $inset->get_blur_radius());
        self::assertTrue($inset->get_outline() == $rect);

        $snapshot = new GtkSnapshot();
        $snapshot->push_rounded_clip($rect);
        $snapshot->append_color(new GdkRGBA('red'), self::bounds());
        $snapshot->pop();
        $ink = new GdkRGBA('black');
        $snapshot->append_border($rect, [1.0, 1.0, 1.0, 1.0], [$ink, $ink, $ink, $ink]);
        $node = $snapshot->to_node();
        self::assertInstanceOf(GskContainerNode::class, $node);
        self::assertSame(2, $node->get_n_children());
        self::assertInstanceOf(GskRoundedClipNode::class, $node->get_child(0));
    }
}
