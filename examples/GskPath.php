<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GdkRGBA;
use Gtk4\GraphenePoint;
use Gtk4\GrapheneRect;
use Gtk4\GskFillRule;
use Gtk4\GskPath;
use Gtk4\GskPathBuilder;
use Gtk4\GskPathMeasure;
use Gtk4\GskRoundedRect;
use Gtk4\GtkButton;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskPath - a vector path, built with GskPathBuilder or parsed from SVG path data.
 *
 * A path is immutable: the builder collects move_to/line_to/cubic_to/close (and whole shapes -
 * add_rect, add_circle, add_rounded_rect), to_path() hands the result over and resets the
 * builder. The page draws the current path on a cairo canvas through to_cairo(), fills the
 * inside, and asks GskPathMeasure for its length and in_fill() whether the canvas centre is
 * inside. Clicking walks through the shapes; the last one comes from GskPath::parse().
 *
 *   bin/php-gtk4 examples/demo.php GskPath
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskPath',
    'a vector path from GskPathBuilder or SVG path data, drawn through cairo',
    function (GtkWindow $win): GtkWidget {
        /** @var list<array{string, callable(): GskPath}> $shapes */
        $shapes = [
            ['triangle (move_to / line_to / close)', static function (): GskPath {
                $b = new GskPathBuilder();
                $b->move_to(40.0, 200.0);
                $b->line_to(200.0, 40.0);
                $b->line_to(360.0, 200.0);
                $b->close();
                return $b->to_path();
            }],
            ['add_rounded_rect()', static function (): GskPath {
                $b = new GskPathBuilder();
                $bounds = GrapheneRect::alloc()->init(60.0, 60.0, 280.0, 120.0);
                $b->add_rounded_rect(new GskRoundedRect($bounds, 40.0, 10.0, 40.0, 10.0));
                return $b->to_path();
            }],
            ['add_circle() twice', static function (): GskPath {
                $b = new GskPathBuilder();
                $b->add_circle(new GraphenePoint(200.0, 120.0), 100.0);
                $b->add_circle(new GraphenePoint(200.0, 120.0), 50.0);
                return $b->to_path();
            }],
            ['cubic_to() and quad_to() waves', static function (): GskPath {
                $b = new GskPathBuilder();
                $b->move_to(40.0, 120.0);
                $b->cubic_to(120.0, 20.0, 200.0, 220.0, 280.0, 120.0);
                $b->quad_to(320.0, 60.0, 360.0, 120.0);
                return $b->to_path();
            }],
            ['GskPath::parse() of SVG path data (two subpaths)', static function (): GskPath {
                return GskPath::parse('M 60 200 L 200 40 L 340 200 Z M 200 100 l 40 60 l -80 0 Z')
                    ?? throw new \RuntimeException('valid SVG path data');
            }],
        ];
        $step = 0;
        $path = $shapes[0][1]();

        $canvas = Demo::canvas(400, 240, function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (&$path): void {
            Demo::sheet($cr);
            $path->to_cairo($cr);
            $cr->set_source_color(new GdkRGBA(Demo::ACCENT));
            $cr->fill_preserve();
            $cr->set_source_color(new GdkRGBA(Demo::INK));
            $cr->set_line_width(3.0);
            $cr->stroke();
            $centre = new GraphenePoint($width / 2.0, $height / 2.0);
            $inside = $path->in_fill($centre, GskFillRule::Winding);
            $cr->set_source_color(new GdkRGBA($inside ? Demo::GOOD : Demo::WARN));
            $cr->arc($centre->x, $centre->y, 5.0, 0.0, 2 * M_PI);
            $cr->fill();
        });

        $describe = function () use (&$path, &$step, $shapes): void {
            $measure = new GskPathMeasure($path);
            Demo::status(sprintf(
                '%d/%d %s · length %.0f · %s · %s',
                $step % count($shapes) + 1,
                count($shapes),
                $shapes[$step % count($shapes)][0],
                $measure->get_length(),
                $path->is_closed() ? 'closed' : 'open',
                strlen($path->to_string()) > 60 ? substr($path->to_string(), 0, 57) . '…' : $path->to_string(),
            ));
        };

        $button = new GtkButton();
        $button->set_child($canvas);
        $button->connect('clicked', function () use (&$step, &$path, $shapes, $canvas, $describe): void {
            $step++;
            $path = $shapes[$step % count($shapes)][1]();
            $canvas->queue_draw();
            $describe();
        });
        $describe();
        return $button;
    },
    440,
    300,
);
