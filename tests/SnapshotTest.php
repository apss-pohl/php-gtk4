<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkPaintable;
use Gtk4\GdkRGBA;
use Gtk4\GdkTexture;
use Gtk4\GraphenePoint;
use Gtk4\GrapheneRect;
use Gtk4\GrapheneSize;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkSnapshot;
use Gtk4\GtkWidget;
use PhpGtk4\Tests\Subclass\PaintedWidget;

/**
 * What a widget paints. Every GTK 4 widget draws by filling a GtkSnapshot, so binding the type
 * is what lets a PHP subclass override `vfunc_snapshot()` - the last GtkWidget slot it could not
 * reach. `append_cairo()` is the bridge: it answers with the CairoContext the drawing API here
 * already speaks, so a snapshot mixes GSK render nodes and cairo in one pass.
 */
final class SnapshotTest extends GtkTestCase
{
    /**
     * Have GTK render $widget through its own snapshot path and answer with what it drew into.
     *
     * `snapshot_child()` is the parent's call into a child's snapshot vfunc, so this drives the
     * real code path without waiting on a frame clock - the widget is allocated directly, the
     * way LayoutManagerTest allocates one.
     */
    private function paint(PaintedWidget $widget): GtkSnapshot
    {
        $box = new GtkBox(GtkOrientation::Vertical, 0);
        $box->append($widget);
        $win = $this->window();
        $win->set_child($box);
        $win->present();
        $box->allocate(100, 60);

        $snapshot = new GtkSnapshot();
        $box->snapshot_child($widget, $snapshot);

        return $snapshot;
    }

    public function testGtkDrivesVfuncSnapshotOnAPhpWidget(): void
    {
        $widget = new PaintedWidget();
        $snapshot = $this->paint($widget);

        self::assertSame(1, $widget->snapshots, 'GTK asked the widget to paint, exactly once');
        self::assertSame(100, $widget->lastWidth, 'it saw the allocation it was given');
        self::assertTrue($widget->cairoDrawn, 'the cairo pass inside the snapshot ran');
        self::assertInstanceOf(GdkPaintable::class, $snapshot->to_paintable(null), 'it produced nodes');
    }

    public function testASnapshotOfItsOwnTakesNodesAndBecomesAPaintable(): void
    {
        $snapshot = new GtkSnapshot();
        $bounds = GrapheneRect::alloc()->init(0.0, 0.0, 20.0, 10.0);
        $snapshot->append_color(new GdkRGBA('#000000'), $bounds);

        $paintable = $snapshot->to_paintable(GrapheneSize::alloc()->init(20.0, 10.0));
        self::assertInstanceOf(GdkPaintable::class, $paintable);
        self::assertSame(20, $paintable->get_intrinsic_width());
        self::assertSame(10, $paintable->get_intrinsic_height());
    }

    public function testTheTransformStackSavesAndRestores(): void
    {
        // None of these answer anything; the point is that a PHP script can drive the stack
        // without GTK complaining, which a mismatched save/restore or pop would.
        $snapshot = new GtkSnapshot();
        $bounds = GrapheneRect::alloc()->init(0.0, 0.0, 10.0, 10.0);

        $snapshot->save();
        $snapshot->translate(new GraphenePoint(5.0, 5.0));
        $snapshot->scale(2.0, 2.0);
        $snapshot->rotate(45.0);
        $snapshot->push_opacity(0.5);
        $snapshot->push_clip($bounds);
        $snapshot->append_color(new GdkRGBA('#ff0000'), $bounds);
        $snapshot->pop();
        $snapshot->pop();
        $snapshot->restore();

        self::assertInstanceOf(GdkPaintable::class, $snapshot->to_paintable(null));
    }

    public function testATextureCanBeAppended(): void
    {
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==',
        );
        $snapshot = new GtkSnapshot();
        $snapshot->append_texture(
            GdkTexture::new_from_bytes($png),
            GrapheneRect::alloc()->init(0.0, 0.0, 8.0, 8.0),
        );
        self::assertInstanceOf(GdkPaintable::class, $snapshot->to_paintable(null));
    }

    public function testTheSnapshotIsNotPhpsToFree(): void
    {
        // gtk_snapshot_free_to_paintable() frees the GtkSnapshot itself, which leaves the handle
        // on freed memory - its next qdata or toggle-ref touch is a SEGV. to_paintable() answers
        // with the same paintable and leaves the object alive, so only that one is bound.
        self::assertFalse(method_exists(GtkSnapshot::class, 'free_to_paintable'));
        // to_paintable() is the replacement, and the object survives it - the handle is still
        // unreffed normally at teardown, which is what ASan checks.
        $snapshot = new GtkSnapshot();
        $snapshot->append_color(new GdkRGBA('#000000'), GrapheneRect::alloc()->init(0.0, 0.0, 4.0, 4.0));
        self::assertInstanceOf(GdkPaintable::class, $snapshot->to_paintable(null));
    }

    public function testAnEmptySnapshotIsStillAPaintable(): void
    {
        // GTK answers with an empty paintable rather than NULL, so the declared ?GdkPaintable
        // never actually comes back null here.
        $paintable = new GtkSnapshot()->to_paintable(null);
        self::assertInstanceOf(GdkPaintable::class, $paintable);
        self::assertSame(0, $paintable->get_intrinsic_width());
    }

    public function testRectangleGeometry(): void
    {
        $rect = GrapheneRect::alloc()->init(10.0, 20.0, 30.0, 40.0);
        self::assertSame(10.0, $rect->get_x());
        self::assertSame(20.0, $rect->get_y());
        self::assertSame(30.0, $rect->get_width());
        self::assertSame(40.0, $rect->get_height());
        self::assertSame(1200.0, $rect->get_area());

        // A graphene point carries its coordinates as boxed fields, so they are properties.
        $centre = $rect->get_center();
        self::assertSame(25.0, $centre->x);
        self::assertSame(40.0, $centre->y);

        self::assertTrue($rect->contains_point(new GraphenePoint(15.0, 25.0)));
        self::assertFalse($rect->contains_point(new GraphenePoint(0.0, 0.0)));
    }

    public function testRectanglesIntersectOrDoNot(): void
    {
        $a = GrapheneRect::alloc()->init(0.0, 0.0, 10.0, 10.0);
        $b = GrapheneRect::alloc()->init(5.0, 5.0, 10.0, 10.0);
        $overlap = $a->intersection($b);
        self::assertNotNull($overlap);
        self::assertSame(25.0, $overlap->get_area());

        $far = GrapheneRect::alloc()->init(100.0, 100.0, 1.0, 1.0);
        self::assertNull($a->intersection($far), 'no overlap is null, not an empty rectangle');
    }

    public function testRectangleCorners(): void
    {
        $rect = GrapheneRect::alloc()->init(0.0, 0.0, 10.0, 20.0);
        self::assertSame([0.0, 0.0], [$rect->get_top_left()->x, $rect->get_top_left()->y]);
        self::assertSame([10.0, 0.0], [$rect->get_top_right()->x, $rect->get_top_right()->y]);
        self::assertSame([0.0, 20.0], [$rect->get_bottom_left()->x, $rect->get_bottom_left()->y]);
        self::assertSame([10.0, 20.0], [$rect->get_bottom_right()->x, $rect->get_bottom_right()->y]);
    }

    public function testRectanglesMoveAndResize(): void
    {
        $rect = GrapheneRect::alloc()->init(10.0, 10.0, 10.0, 10.0);

        $moved = $rect->offset(5.0, -5.0);
        self::assertSame(15.0, $moved->get_x());
        self::assertSame(5.0, $moved->get_y());

        $bigger = GrapheneRect::alloc()->init(0.0, 0.0, 4.0, 4.0)->scale(2.0, 3.0);
        self::assertSame(8.0, $bigger->get_width());
        self::assertSame(12.0, $bigger->get_height());

        // inset() shrinks from every edge, so the area drops by more than the inset itself.
        $inset = GrapheneRect::alloc()->init(0.0, 0.0, 10.0, 10.0)->inset(1.0, 2.0);
        self::assertSame(8.0, $inset->get_width());
        self::assertSame(6.0, $inset->get_height());
    }

    public function testRectanglesCombine(): void
    {
        $a = GrapheneRect::alloc()->init(0.0, 0.0, 10.0, 10.0);
        $b = GrapheneRect::alloc()->init(20.0, 0.0, 10.0, 10.0);

        $union = $a->union($b);
        self::assertSame(0.0, $union->get_x());
        self::assertSame(30.0, $union->get_width(), 'the union spans both');

        self::assertTrue($union->contains_rect($a));
        self::assertFalse($a->contains_rect($union));

        // A rectangle grown to reach a point outside it.
        $expanded = $a->expand(new GraphenePoint(15.0, 0.0));
        self::assertSame(15.0, $expanded->get_width());
    }

    public function testDegenerateRectanglesNormalise(): void
    {
        // A negative size is the same rectangle described backwards; normalize() fixes the origin.
        $backwards = GrapheneRect::alloc()->init(10.0, 10.0, -10.0, -10.0);
        $normal = $backwards->normalize();
        self::assertSame(0.0, $normal->get_x());
        self::assertSame(10.0, $normal->get_width());

        self::assertSame(0.0, GrapheneRect::zero()->get_area());
    }

    public function testSizesScaleAndCompare(): void
    {
        $size = new GrapheneSize(10.0, 4.0);
        $doubled = $size->scale(2.0);
        self::assertSame(20.0, $doubled->width);
        self::assertSame(8.0, $doubled->height);

        self::assertTrue($size->equal(GrapheneSize::alloc()->init(10.0, 4.0)));
        self::assertFalse($size->equal(GrapheneSize::zero()));

        $half = $size->interpolate(GrapheneSize::zero(), 0.5);
        self::assertSame(5.0, $half->width);
    }

    public function testPointsMeasureAndInterpolate(): void
    {
        $origin = GraphenePoint::zero();
        $far = new GraphenePoint(3.0, 4.0);
        self::assertSame(0.0, $origin->x);
        self::assertTrue($far->near(GraphenePoint::alloc()->init(3.0, 4.0), 0.001));
        self::assertFalse($far->near($origin, 0.001));

        $mid = $origin->interpolate($far, 0.5);
        self::assertSame(1.5, $mid->x);
        self::assertSame(2.0, $mid->y);
    }

    public function testGeometryMethodsLeaveTheReceiverAlone(): void
    {
        // graphene's inset()/offset()/normalize() rewrite the rectangle they are given and hand
        // the same one back. A boxed handle here is a value - clone and compare by value - so the
        // binding uses graphene's `_r` forms under the plain names, and the `_r` names are not
        // bound at all. Without this, `$rect->inset(1, 1)` silently shrank $rect.
        $rect = GrapheneRect::alloc()->init(0.0, 0.0, 10.0, 10.0);

        $inset = $rect->inset(1.0, 1.0);
        self::assertSame(8.0, $inset->get_width());
        self::assertSame(10.0, $rect->get_width(), 'inset() did not touch the receiver');

        $moved = $rect->offset(2.0, 3.0);
        self::assertSame(2.0, $moved->get_x());
        self::assertSame(0.0, $rect->get_x(), 'offset() did not touch the receiver');

        // The `_r` names stay unbound: the plain ones already are that form.
        self::assertNotContains(
            'inset_r',
            array_map(
                static fn(\ReflectionMethod $m): string => $m->getName(),
                new \ReflectionClass(GrapheneRect::class)->getMethods(),
            ),
        );
    }

    public function testFractionalRectanglesRoundOutwards(): void
    {
        $rect = GrapheneRect::alloc()->init(0.5, 0.5, 9.0, 9.0);
        $whole = $rect->round_extents();
        self::assertSame(0.0, $whole->get_x(), 'the origin rounds down');
        self::assertSame(10.0, $whole->get_width(), 'the extent rounds up to cover the original');
    }

    public function testValuesCopyFromAnother(): void
    {
        self::assertTrue(
            GrapheneRect::alloc()->init_from_rect(GrapheneRect::alloc()->init(1.0, 2.0, 3.0, 4.0))
                ->equal(GrapheneRect::alloc()->init(1.0, 2.0, 3.0, 4.0)),
        );
        self::assertSame(7.0, GraphenePoint::alloc()->init_from_point(new GraphenePoint(7.0, 8.0))->x);
        self::assertSame(9.0, GrapheneSize::alloc()->init_from_size(new GrapheneSize(9.0, 1.0))->width);
    }

    public function testTheEffectStackTakesEveryPushWeBind(): void
    {
        // Each push needs its own pop; an unbalanced stack is a GTK CRITICAL, so this walks the
        // whole set the binding exposes and unwinds it.
        $snapshot = new GtkSnapshot();
        $bounds = GrapheneRect::alloc()->init(0.0, 0.0, 10.0, 10.0);

        $snapshot->push_blur(2.0);
        $snapshot->push_opacity(0.5);
        $snapshot->push_repeat($bounds, null);
        $snapshot->append_color(new GdkRGBA('#00ff00'), $bounds);
        $snapshot->pop();
        $snapshot->pop();
        $snapshot->pop();

        $snapshot->perspective(100.0);
        $snapshot->scale_3d(1.0, 1.0, 1.0);

        self::assertInstanceOf(GdkPaintable::class, $snapshot->to_paintable(null));
    }

    public function testAScaledTextureCanBeAppended(): void
    {
        $png = base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==',
        );
        $snapshot = new GtkSnapshot();
        $snapshot->append_scaled_texture(
            GdkTexture::new_from_bytes($png),
            \Gtk4\GskScalingFilter::Nearest,
            GrapheneRect::alloc()->init(0.0, 0.0, 16.0, 16.0),
        );
        self::assertInstanceOf(GdkPaintable::class, $snapshot->to_paintable(null));
    }

    public function testAWindowIsANativeWithASurfaceTransform(): void
    {
        $win = $this->window();
        $win->present();
        self::assertNotNull($win->get_surface());
        // The offset of the widget inside its surface: two doubles, both real numbers.
        $transform = $win->get_surface_transform();
        self::assertCount(2, $transform);
        self::assertGreaterThanOrEqual(0.0, $transform[0]);
    }

    public function testRectanglesCompareByValue(): void
    {
        $a = GrapheneRect::alloc()->init(1.0, 2.0, 3.0, 4.0);
        $b = GrapheneRect::alloc()->init(1.0, 2.0, 3.0, 4.0);
        self::assertNotSame($a, $b);
        self::assertTrue($a->equal($b));
        self::assertEquals($a, $b, 'a boxed handle compares by value');
    }
}
