<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkRGBA;
use Gtk4\GrapheneRect;
use Gtk4\GskBorderNode;
use Gtk4\GskCairoRenderer;
use Gtk4\GskColorNode;
use Gtk4\GskContainerNode;
use Gtk4\GskCorner;
use Gtk4\GskRoundedClipNode;
use Gtk4\GskRoundedRect;
use Gtk4\GtkButton;
use Gtk4\GtkContentFit;
use Gtk4\GtkPicture;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskRoundedRect - a rectangle with a radius per corner, the shape GSK clips and borders with.
 *
 * GSK gives the struct no GType, so the binding registers one: the value handle is cloneable and
 * compared by value like GdkRectangle, and the GSK calls that rewrite it in place (offset, shrink,
 * normalize) answer with a copy here. The page builds a scene - a colour clipped to the rounded
 * rectangle, a border drawn along it - renders it to a texture with a GskCairoRenderer and shows
 * that in a GtkPicture. Clicking walks through corner shapes; the status line reads the corners
 * back with get_corner() and asks contains_point() about the top-left pixel.
 *
 *   bin/php-gtk4 examples/demo.php GskRoundedRect
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskRoundedRect',
    'a rectangle with a radius per corner: what GSK clips and borders with',
    function (GtkWindow $win): GtkWidget {
        $shapes = [
            'square' => [0.0, 0.0, 0.0, 0.0],
            'rounded' => [24.0, 24.0, 24.0, 24.0],
            'pill left' => [60.0, 0.0, 0.0, 60.0],
            'one corner' => [0.0, 0.0, 80.0, 0.0],
            'shrunk by 20' => 'shrink',
        ];
        $names = array_keys($shapes);
        $bounds = GrapheneRect::alloc()->init(20.0, 20.0, 280.0, 120.0);
        $renderer = new GskCairoRenderer();
        $renderer->realize(null);
        $picture = new GtkPicture();
        $picture->set_content_fit(GtkContentFit::Contain);
        $picture->set_size_request(320, 160);

        $render = function (int $step) use ($shapes, $names, $bounds, $renderer, $picture): void {
            $name = $names[$step % count($names)];
            $radii = $shapes[$name];
            $rect = $radii === 'shrink'
                ? new GskRoundedRect($bounds, 24.0, 24.0, 24.0, 24.0)->shrink(20.0, 20.0, 20.0, 20.0)
                : new GskRoundedRect($bounds, ...$radii);
            $fill = new GskRoundedClipNode(
                new GskColorNode(new GdkRGBA(Demo::ACCENT), GrapheneRect::alloc()->init(0.0, 0.0, 320.0, 160.0)),
                $rect,
            );
            $ink = new GdkRGBA(Demo::INK);
            $border = new GskBorderNode($rect, [3.0, 3.0, 3.0, 3.0], [$ink, $ink, $ink, $ink]);
            $scene = new GskContainerNode([$fill, $border]);
            $viewport = GrapheneRect::alloc()->init(0.0, 0.0, 320.0, 160.0);
            $picture->set_paintable($renderer->render_texture($scene, $viewport));
            $corners = implode(' ', array_map(
                static fn(GskCorner $c): string => sprintf('%s=%g', $c->name, $rect->get_corner($c)->width),
                GskCorner::cases(),
            ));
            $inside = $rect->contains_point($rect->get_bounds()->get_top_left()) ? 'inside' : 'outside';
            Demo::status(sprintf(
                '%s · %s · rectilinear=%s · top-left pixel %s',
                $name,
                $corners,
                var_export($rect->is_rectilinear(), true),
                $inside,
            ));
        };

        $step = 0;
        $button = new GtkButton();
        $button->set_child($picture);
        $button->connect('clicked', function () use (&$step, $render): void {
            $render(++$step);
        });
        $render(0);
        return $button;
    },
    380,
    240,
);
