<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GdkRGBA;
use Gtk4\GraphenePoint;
use Gtk4\GrapheneRect;
use Gtk4\GskColorNode;
use Gtk4\GskFillNode;
use Gtk4\GskFillRule;
use Gtk4\GskLineCap;
use Gtk4\GskPath;
use Gtk4\GskPathBuilder;
use Gtk4\GskPathMeasure;
use Gtk4\GskPathPoint;
use Gtk4\GskStroke;
use Gtk4\GskStrokeNode;

/**
 * GSK 4.14's paths: built with GskPathBuilder, measured, hit-tested, stroked and filled. The
 * builder's free_to_path() is deliberately not bound (it frees the builder under the handle);
 * to_path() is the same path. The dash pattern is a PHP list.
 */
final class GskPathTest extends GtkTestCase
{
    private static function triangle(): GskPath
    {
        $builder = new GskPathBuilder();
        $builder->move_to(0.0, 0.0);
        $builder->line_to(10.0, 0.0);
        $builder->line_to(10.0, 10.0);
        $builder->close();
        return $builder->to_path();
    }

    public function testBuilderMakesAPathAndCanBeReused(): void
    {
        $builder = new GskPathBuilder();
        $builder->move_to(1.0, 2.0);
        self::assertSame([1.0, 2.0], [$builder->get_current_point()->x, $builder->get_current_point()->y]);
        $builder->line_to(3.0, 4.0);
        $first = $builder->to_path();
        self::assertSame('M 1 2 L 3 4', $first->to_string());
        self::assertFalse($first->is_closed());
        // to_path() reset the builder; it goes on working (free_to_path() would have freed it)
        $builder->add_rect(GrapheneRect::alloc()->init(0.0, 0.0, 2.0, 2.0));
        self::assertStringContainsString('M 0 0', $builder->to_path()->to_string());
        self::assertFalse(new \ReflectionClass($builder)->hasMethod('free_to_path'), 'not bound: it frees the builder');
    }

    public function testParseAndToString(): void
    {
        $path = GskPath::parse('M 0 0 L 10 0 L 10 10 Z');
        self::assertInstanceOf(GskPath::class, $path);
        self::assertTrue($path->is_closed());
        self::assertSame('M 0 0 L 10 0 L 10 10 Z', $path->to_string());
        self::assertNull(GskPath::parse('this is not SVG path data'));
        self::assertTrue(GskPath::parse('')?->is_empty() ?? false, 'the empty string is the empty path');
    }

    public function testHitTestingAndBounds(): void
    {
        $path = self::triangle();
        self::assertTrue($path->in_fill(new GraphenePoint(8.0, 2.0), GskFillRule::Winding));
        self::assertFalse($path->in_fill(new GraphenePoint(2.0, 8.0), GskFillRule::Winding), 'the other half');
        $bounds = $path->get_bounds();
        self::assertNotNull($bounds);
        self::assertSame([10.0, 10.0], [$bounds->get_width(), $bounds->get_height()]);
        $start = $path->get_start_point();
        self::assertInstanceOf(GskPathPoint::class, $start);
        $position = $start->get_position($path);
        self::assertSame([0.0, 0.0], [$position->x, $position->y]);
    }

    public function testMeasure(): void
    {
        $measure = new GskPathMeasure(self::triangle());
        self::assertEqualsWithDelta(10.0 + 10.0 + sqrt(200.0), $measure->get_length(), 0.01);
        $half = $measure->get_point($measure->get_length() / 2);
        self::assertInstanceOf(GskPathPoint::class, $half);
        self::assertSame(self::triangle()->to_string(), $measure->get_path()->to_string());
    }

    public function testStrokeAndDash(): void
    {
        $stroke = new GskStroke(2.0);
        self::assertSame(2.0, $stroke->get_line_width());
        $stroke->set_line_cap(GskLineCap::Round);
        self::assertSame(GskLineCap::Round, $stroke->get_line_cap());
        self::assertSame([], $stroke->get_dash(), 'solid by default');
        $stroke->set_dash([4.0, 2.0]);
        self::assertSame([4.0, 2.0], $stroke->get_dash());
        $stroke->set_dash_offset(1.0);
        self::assertSame(1.0, $stroke->get_dash_offset());
        $stroke->set_dash([]);
        self::assertSame([], $stroke->get_dash());
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('must contain a length above zero');
        $stroke->set_dash([0.0, 0.0]);
    }

    public function testStrokeRefusesWhatGskWouldAssert(): void
    {
        $stroke = new GskStroke(1.0);
        try {
            $stroke->set_dash([1.0, -1.0]);
            self::fail('negative');
        } catch (\ValueError $e) {
            self::assertStringContainsString('negative length', $e->getMessage());
        }
        try {
            $stroke->set_line_width(0.0);
            self::fail('zero width');
        } catch (\ValueError $e) {
            self::assertStringContainsString('must be greater than 0, 0 given', $e->getMessage());
        }
        $this->expectException(\ValueError::class);
        new GskStroke(-1.0);
    }

    public function testFillAndStrokeNodes(): void
    {
        $path = self::triangle();
        $paint = new GskColorNode(new GdkRGBA('red'), GrapheneRect::alloc()->init(0.0, 0.0, 10.0, 10.0));
        $fill = new GskFillNode($paint, $path, GskFillRule::EvenOdd);
        self::assertSame(GskFillRule::EvenOdd, $fill->get_fill_rule());
        self::assertSame($path->to_string(), $fill->get_path()->to_string());
        self::assertSame($paint, $fill->get_child());
        $stroke = new GskStrokeNode($paint, $path, new GskStroke(3.0));
        self::assertSame(3.0, $stroke->get_stroke()->get_line_width());
        $bounds = $path->get_stroke_bounds(new GskStroke(2.0));
        self::assertNotNull($bounds);
        self::assertGreaterThan(10.0, $bounds->get_width(), 'the stroke sticks out');
    }
}
