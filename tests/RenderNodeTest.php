<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkRGBA;
use Gtk4\GdkTexture;
use Gtk4\GrapheneMatrix;
use Gtk4\GraphenePoint;
use Gtk4\GrapheneRect;
use Gtk4\GrapheneVec4;
use Gtk4\GskBlendMode;
use Gtk4\GskBlendNode;
use Gtk4\GskBorderNode;
use Gtk4\GskCairoNode;
use Gtk4\GskCairoRenderer;
use Gtk4\GskColorMatrixNode;
use Gtk4\GskColorNode;
use Gtk4\GskConicGradientNode;
use Gtk4\GskContainerNode;
use Gtk4\GskLinearGradientNode;
use Gtk4\GskOpacityNode;
use Gtk4\GskRadialGradientNode;
use Gtk4\GskRenderer;
use Gtk4\GskRenderNode;
use Gtk4\GskRenderNodeType;
use Gtk4\GskRoundedRect;
use Gtk4\GskShadowNode;
use Gtk4\GskTextureNode;
use Gtk4\GskTransform;
use Gtk4\GskTransformNode;
use Gtk4\GtkSnapshot;

/**
 * GSK's scene graph from PHP: render nodes are GIR *fundamental* classes (refcounted, neither
 * GObject nor boxed) that the generator puts on src/core/fundamental - `new` adopts what the C
 * constructor made, the same instance wraps to the same handle, subclasses come back as
 * themselves. The hand-written parts are the C arrays (children, colour stops, shadows,
 * borders) as PHP lists, deserialize()'s error callback, and the renderer that must be
 * unrealized before it is dropped.
 */
final class RenderNodeTest extends GtkTestCase
{
    private static function rect(float $x, float $y, float $w, float $h): GrapheneRect
    {
        return GrapheneRect::alloc()->init($x, $y, $w, $h);
    }

    private static function red(): GskColorNode
    {
        return new GskColorNode(new GdkRGBA('red'), self::rect(0.0, 0.0, 10.0, 10.0));
    }

    public function testANodeIsBuiltFromItsPartsAndReadsThemBack(): void
    {
        $node = self::red();
        self::assertSame(GskRenderNodeType::ColorNode, $node->get_node_type());
        self::assertSame('rgb(255,0,0)', $node->get_color()->to_string());
        self::assertSame(10.0, $node->get_bounds()->get_width());
        self::assertInstanceOf(GskRenderNode::class, $node, 'the base class is the PHP parent');
    }

    public function testTheSameInstanceIsTheSameHandleAndKeepsItsClass(): void
    {
        $child = self::red();
        $opacity = new GskOpacityNode($child, 0.5);
        self::assertSame($child, $opacity->get_child(), 'identity holds while PHP keeps the child');
        self::assertSame(0.5, $opacity->get_opacity());

        // Drop the PHP reference: the parent keeps the C instance alive, and a fresh handle for it
        // still gets the concrete class from the GType.
        unset($child);
        $again = $opacity->get_child();
        self::assertInstanceOf(GskColorNode::class, $again);
        self::assertSame('rgb(255,0,0)', $again->get_color()->to_string());
    }

    public function testTheBaseClassCannotBeConstructedAndSubclassesAreFinal(): void
    {
        self::assertTrue(new \ReflectionClass(GskColorNode::class)->isFinal());
        self::assertFalse(new \ReflectionClass(GskRenderNode::class)->isFinal(), 'it has subclasses');
        self::assertTrue(
            new \ReflectionClass(GskRenderNode::class)->getConstructor()?->isPrivate() ?? false,
            'the base class comes from GTK only',
        );
    }

    public function testAContainerTakesAListOfNodes(): void
    {
        $a = self::red();
        $b = new GskColorNode(new GdkRGBA('blue'), self::rect(10.0, 0.0, 10.0, 10.0));
        $container = new GskContainerNode([$a, $b]);
        self::assertSame(2, $container->get_n_children());
        self::assertSame($b, $container->get_child(1));
        self::assertSame(20.0, $container->get_bounds()->get_width(), 'the union of the children');
        self::assertSame(0, new GskContainerNode([])->get_n_children());

        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('must be a list of GskRenderNode, string found in it');
        new GskContainerNode([$a, $this->opaque(static fn(): string => 'not a node')()]);
    }

    public function testGradientsTakeColourStopsAsPairs(): void
    {
        $stops = [[0.0, new GdkRGBA('red')], [0.5, new GdkRGBA('lime')], [1.0, new GdkRGBA('blue')]];
        $bounds = self::rect(0.0, 0.0, 20.0, 20.0);
        $linear = new GskLinearGradientNode($bounds, new GraphenePoint(0.0, 0.0), new GraphenePoint(20.0, 0.0), $stops);
        $back = $linear->get_color_stops();
        self::assertCount(3, $back);
        self::assertSame(0.5, $back[1][0]);
        self::assertSame('rgb(0,255,0)', $back[1][1]->to_string());
        self::assertSame(3, $linear->get_n_color_stops());

        $radial = new GskRadialGradientNode($bounds, new GraphenePoint(10.0, 10.0), 10.0, 5.0, 0.0, 1.0, $stops);
        self::assertSame(10.0, $radial->get_hradius());
        self::assertSame(5.0, $radial->get_vradius());
        $conic = new GskConicGradientNode($bounds, new GraphenePoint(10.0, 10.0), 90.0, $stops);
        self::assertSame(90.0, $conic->get_rotation());
    }

    public function testAGradientRefusesWhatGskWouldAssert(): void
    {
        $bounds = self::rect(0.0, 0.0, 20.0, 20.0);
        $p = new GraphenePoint(0.0, 0.0);
        try {
            new GskLinearGradientNode($bounds, $p, $p, [[0.0, new GdkRGBA('red')]]);
            self::fail('one stop');
        } catch (\ValueError $e) {
            self::assertStringContainsString('at least two colour stops, 1 given', $e->getMessage());
        }
        try {
            new GskLinearGradientNode($bounds, $p, $p, [[0.8, new GdkRGBA('red')], [0.2, new GdkRGBA('red')]]);
            self::fail('descending offsets');
        } catch (\ValueError $e) {
            self::assertStringContainsString('item 1 must have an offset within 0.0 .. 1.0', $e->getMessage());
        }
        try {
            new GskLinearGradientNode($bounds, $p, $p, [[0.0, 'red'], [1.0, 'blue']]);
            self::fail('a string is not a colour');
        } catch (\ValueError $e) {
            self::assertStringContainsString('item 0 must carry a GdkRGBA colour', $e->getMessage());
        }
        try {
            $stops = [[0.0, new GdkRGBA('red')], [1.0, new GdkRGBA('blue')]];
            new GskRadialGradientNode($bounds, $p, 0.0, 5.0, 0.0, 1.0, $stops);
            self::fail('a zero radius');
        } catch (\ValueError $e) {
            self::assertStringContainsString('Argument #3 ($hradius) must be greater than 0', $e->getMessage());
        }
    }

    public function testShadowsAndBordersAreListsToo(): void
    {
        $shadow = new GskShadowNode(self::red(), [[new GdkRGBA('black'), 2.0, 3.0, 4.0]]);
        self::assertSame(1, $shadow->get_n_shadows());
        [$color, $dx, $dy, $radius] = $shadow->get_shadow(0);
        self::assertSame('rgb(0,0,0)', $color->to_string());
        self::assertSame([2.0, 3.0, 4.0], [$dx, $dy, $radius]);
        try {
            $shadow->get_shadow(1);
            self::fail('past the end');
        } catch (\ValueError $e) {
            self::assertStringContainsString('must be between 0 and 0', $e->getMessage());
        }
        try {
            new GskShadowNode(self::red(), []);
            self::fail('no shadow');
        } catch (\ValueError $e) {
            self::assertStringContainsString('at least one shadow', $e->getMessage());
        }

        $outline = new GskRoundedRect(self::rect(0.0, 0.0, 40.0, 30.0), 5.0);
        $ink = new GdkRGBA('black');
        $border = new GskBorderNode($outline, [1.0, 2.0, 3.0, 4.0], [$ink, $ink, $ink, $ink]);
        self::assertSame([1.0, 2.0, 3.0, 4.0], $border->get_widths());
        self::assertTrue($border->get_outline()->equal($outline));
        try {
            new GskBorderNode($outline, [1.0, 2.0, 3.0], [$ink, $ink, $ink, $ink]);
            self::fail('three widths');
        } catch (\ValueError $e) {
            self::assertStringContainsString('exactly four widths', $e->getMessage());
        }
    }

    public function testTheRendererDrawsATreeIntoATexture(): void
    {
        $renderer = new GskCairoRenderer();
        self::assertInstanceOf(GskRenderer::class, $renderer);
        self::assertFalse($renderer->is_realized());
        self::assertTrue($renderer->realize(null), 'the cairo renderer needs no surface');
        $scene = new GskContainerNode([
            self::red(),
            new GskTransformNode(
                self::red(),
                new GskTransform()->translate(new GraphenePoint(10.0, 0.0)) ?? throw new \RuntimeException('translate'),
            ),
            new GskBlendNode(self::red(), self::red(), GskBlendMode::Multiply),
        ]);
        $texture = $renderer->render_texture($scene, self::rect(0.0, 0.0, 20.0, 10.0));
        self::assertInstanceOf(GdkTexture::class, $texture);
        self::assertSame([20, 10], [$texture->get_width(), $texture->get_height()]);
        $png = $texture->save_to_png_bytes();
        self::assertStringStartsWith("\x89PNG", $png);
        $renderer->unrealize();
        self::assertFalse($renderer->is_realized());
    }

    public function testARealizedRendererMayBeDropped(): void
    {
        // gsk_renderer_dispose() aborts on a realized renderer; the handle unrealizes it when
        // it holds the last reference, so this statement is not fatal.
        $texture = new GskCairoRenderer()->realize(null)
            ? self::renderOnce()
            : null;
        self::assertInstanceOf(GdkTexture::class, $texture);
    }

    private static function renderOnce(): GdkTexture
    {
        $renderer = new GskCairoRenderer();
        $renderer->realize(null);
        return $renderer->render_texture(self::red(), self::rect(0.0, 0.0, 4.0, 4.0));
    }

    public function testSerializeAndDeserializeRoundTrip(): void
    {
        $scene = new GskContainerNode([self::red(), new GskOpacityNode(self::red(), 0.25)]);
        $text = $scene->serialize();
        self::assertStringContainsString('color', $text);
        self::assertStringContainsString('opacity', $text);
        $back = GskRenderNode::deserialize($text);
        self::assertInstanceOf(GskContainerNode::class, $back);
        self::assertSame(2, $back->get_n_children());
        self::assertSame($text, $back->serialize());

        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('is not a serialized render node (1:1:');
        GskRenderNode::deserialize('this is not a node file {');
    }

    public function testTextureAndCairoNodes(): void
    {
        $texture = GdkTexture::new_from_bytes(PngFixture::red(2, 1));
        $node = new GskTextureNode($texture, self::rect(0.0, 0.0, 2.0, 1.0));
        self::assertSame($texture, $node->get_texture());

        $cairo = new GskCairoNode(self::rect(0.0, 0.0, 8.0, 8.0));
        $cr = $cairo->get_draw_context();
        $cr->set_source_rgb(0.0, 1.0, 0.0);
        $cr->paint();
        unset($cr);   // drawing lands in the node's (recording) surface when the context is released
        self::assertSame(8.0, $cairo->get_bounds()->get_width());
    }

    /**
     * The colour-matrix node was excused as "needs a graphene matrix and vec4 (not bound)" long
     * after both were bound and its constructor became public - the stale excuse the smaller notes
     * in docs/TODO.md warn about. It builds, keeps what it was given, and renders.
     */
    public function testAColorMatrixNodeKeepsItsMatrixAndOffset(): void
    {
        $matrix = GrapheneMatrix::alloc();
        $matrix->init_scale(1.0, 0.5, 0.25);
        $offset = GrapheneVec4::alloc();
        $offset->init(0.1, 0.0, 0.0, 0.0);

        $child = self::red();
        $node = new GskColorMatrixNode($child, $matrix, $offset);

        self::assertSame($child, $node->get_child());
        self::assertSame(0.5, $node->get_color_matrix()->get_y_scale());
        self::assertEqualsWithDelta(0.1, $node->get_color_offset()->get_x(), 1.0e-6);
        self::assertSame($child->get_bounds()->get_width(), $node->get_bounds()->get_width());

        // and it survives the renderer, which is what a node is for
        $renderer = new GskCairoRenderer();
        $renderer->realize(null);
        $texture = $renderer->render_texture($node, null);
        self::assertGreaterThan(0, $texture->get_width());
        $renderer->unrealize();
    }

    public function testASnapshotBecomesANodeTree(): void
    {
        $snapshot = new GtkSnapshot();
        $snapshot->append_color(new GdkRGBA('red'), self::rect(0.0, 0.0, 4.0, 4.0));
        $snapshot->append_node(self::red());
        $node = $snapshot->to_node();
        self::assertInstanceOf(GskContainerNode::class, $node);
        self::assertSame(2, $node->get_n_children());
    }

    public function testAMethodOnAHandleWhoseConstructorNeverRanIsAnError(): void
    {
        // A PHP subclass that skips parent::__construct() is the one way to get such a handle.
        $node = new class extends GskRenderNode {
            public function __construct()
            {
                // deliberately not chaining to parent::__construct(): the handle stays empty
            }
        };
        $this->expectException(\Error::class);
        $this->expectExceptionMessage('this handle has no instance');
        $node->get_bounds();
    }
}
