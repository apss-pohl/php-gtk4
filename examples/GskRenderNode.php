<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkRGBA;
use Gtk4\GraphenePoint;
use Gtk4\GrapheneRect;
use Gtk4\GskBlurNode;
use Gtk4\GskCairoRenderer;
use Gtk4\GskColorNode;
use Gtk4\GskContainerNode;
use Gtk4\GskLinearGradientNode;
use Gtk4\GskOpacityNode;
use Gtk4\GskRenderNode;
use Gtk4\GskRoundedClipNode;
use Gtk4\GskRoundedRect;
use Gtk4\GskShadowNode;
use Gtk4\GskTransform;
use Gtk4\GskTransformNode;
use Gtk4\GtkButton;
use Gtk4\GtkContentFit;
use Gtk4\GtkPicture;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskRenderNode - the scene graph GTK renders: immutable nodes, built from their parts.
 *
 * A render node is neither a widget nor a GObject: a refcounted description of something to
 * draw (a colour over bounds, a gradient, a texture) or of what to do with other nodes (clip,
 * blur, fade, transform, stack). A GskRenderer turns a tree of them into pixels - here the
 * cairo renderer draws into a GdkTexture that a GtkPicture shows - and serialize() writes the
 * tree as text that deserialize() reads back. The page keeps one scene and wraps it in a
 * different node on every click; the status line shows the tree's type, bounds and how many
 * bytes it serializes to.
 *
 *   bin/php-gtk4 examples/demo.php GskRenderNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskRenderNode',
    'the scene graph: immutable nodes a renderer turns into pixels',
    function (GtkWindow $win): GtkWidget {
        $bounds = GrapheneRect::alloc()->init(40.0, 30.0, 240.0, 140.0);
        $scene = new GskContainerNode([
            new GskLinearGradientNode(
                $bounds,
                new GraphenePoint(40.0, 30.0),
                new GraphenePoint(280.0, 170.0),
                [[0.0, new GdkRGBA(Demo::ACCENT)], [1.0, new GdkRGBA(Demo::GOOD)]],
            ),
            new GskColorNode(new GdkRGBA(Demo::PAPER), GrapheneRect::alloc()->init(80.0, 70.0, 160.0, 60.0)),
        ]);

        /** @var list<array{string, callable(GskRenderNode): GskRenderNode}> $wrappers */
        $wrappers = [
            ['the scene itself (GskContainerNode)', static fn(GskRenderNode $n): GskRenderNode => $n],
            ['GskOpacityNode 0.4', static fn(GskRenderNode $n): GskRenderNode => new GskOpacityNode($n, 0.4)],
            ['GskBlurNode radius 6', static fn(GskRenderNode $n): GskRenderNode => new GskBlurNode($n, 6.0)],
            ['GskRoundedClipNode', static fn(GskRenderNode $n): GskRenderNode => new GskRoundedClipNode(
                $n,
                new GskRoundedRect(GrapheneRect::alloc()->init(40.0, 30.0, 240.0, 140.0), 40.0, 40.0, 40.0, 40.0),
            )],
            ['GskShadowNode', static fn(GskRenderNode $n): GskRenderNode => new GskShadowNode(
                $n,
                [[new GdkRGBA('rgba(0,0,0,0.5)'), 6.0, 6.0, 8.0]],
            )],
            ['GskTransformNode rotate(12)', static function (GskRenderNode $n): GskRenderNode {
                $t = new GskTransform()->translate(new GraphenePoint(160.0, 100.0))?->rotate(12.0)
                    ?->translate(new GraphenePoint(-160.0, -100.0));
                return new GskTransformNode($n, $t ?? new GskTransform());
            }],
        ];

        $renderer = new GskCairoRenderer();
        $renderer->realize(null);
        $picture = new GtkPicture();
        $picture->set_content_fit(GtkContentFit::Contain);
        $picture->set_size_request(320, 200);
        $viewport = GrapheneRect::alloc()->init(0.0, 0.0, 320.0, 200.0);

        $step = 0;
        $show = function () use (&$step, $wrappers, $scene, $renderer, $picture, $viewport): void {
            [$name, $wrap] = $wrappers[$step % count($wrappers)];
            $node = $wrap($scene);
            $picture->set_paintable($renderer->render_texture($node, $viewport));
            $b = $node->get_bounds();
            Demo::status(sprintf(
                '%s · %s · bounds %gx%g at (%g, %g) · serialize() = %d bytes',
                $name,
                $node->get_node_type()->name,
                $b->get_width(),
                $b->get_height(),
                $b->get_x(),
                $b->get_y(),
                strlen($node->serialize()),
            ));
        };

        $button = new GtkButton();
        $button->set_child($picture);
        $button->connect('clicked', function () use (&$step, $show): void {
            $step++;
            $show();
        });
        $show();
        return $button;
    },
    380,
    260,
);
