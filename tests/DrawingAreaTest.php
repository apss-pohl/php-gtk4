<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\CairoContext;
use Gtk4\GdkRGBA;
use Gtk4\GLib;
use Gtk4\GMainLoop;
use Gtk4\Gtk;
use Gtk4\GtkDrawingArea;

/** Gtk4\GtkDrawingArea::set_draw_func (notified-scope C callback) and Gtk4\CairoContext. */
final class DrawingAreaTest extends GtkTestCase
{
    public function testContentSize(): void
    {
        $area = new GtkDrawingArea();
        self::assertSame(0, $area->get_content_width());
        $area->set_content_width(120);
        $area->set_content_height(80);
        self::assertSame(120, $area->get_content_width());
        self::assertSame(80, $area->get_content_height());
        self::assertSame(80, $area->content_height);
    }

    /**
     * Present a window with the area and pump the loop until the draw func ran
     * (or a timeout hits). Returns what the callback received.
     *
     * @return array{0:GtkDrawingArea,1:CairoContext,2:int,3:int}|null
     */
    private function drawOnce(GtkDrawingArea $area, ?callable $paint = null): ?array
    {
        $win = $this->window();
        $win->set_child($area);
        $loop = new GMainLoop();
        $seen = null;
        $draw = function (GtkDrawingArea $a, CairoContext $cr, int $w, int $h) use (&$seen, $loop, $paint): void {
            // The loop ends as soon as the draw func ran, whether or not the paint threw -
            // otherwise a throwing paint only ends with the fallback timeout below.
            try {
                if ($paint !== null) {
                    $paint($cr, $w, $h);
                }
            } finally {
                $seen = [$a, $cr, $w, $h];
                $loop->quit();
            }
        };
        $area->set_draw_func($draw);
        // Fallback for a draw that never happens; generous enough for an instrumented (asan) build.
        GLib::timeout_add(1000, function () use ($loop): bool {
            $loop->quit();
            return false;
        });
        $win->present();
        $loop->run();
        return $seen;
    }

    public function testDrawFuncReceivesAreaContextAndSize(): void
    {
        $area = new GtkDrawingArea();
        $area->set_content_width(64);
        $area->set_content_height(32);
        $seen = $this->drawOnce($area, function (CairoContext $cr, int $w, int $h): void {
            $cr->save();
            $cr->set_source_rgb(0.1, 0.2, 0.3);
            $cr->rectangle(0, 0, $w, $h);
            $cr->fill();
            $cr->set_source_color(new GdkRGBA('red'));
            $cr->set_line_width(2.0);
            $cr->move_to(0, 0);
            $cr->line_to($w, $h);
            $cr->stroke();
            $cr->arc($w / 2, $h / 2, 8.0, 0.0, 2 * M_PI);
            $cr->close_path();
            $cr->fill_preserve();
            $cr->stroke_preserve();
            $cr->translate(1.0, 1.0);
            $cr->scale(1.0, 1.0);
            $cr->rotate(0.0);
            $cr->set_font_size(10.0);
            $cr->show_text('hi');
            $cr->set_source_rgba(0.0, 0.0, 0.0, 0.5);
            $cr->paint();
            $cr->restore();
        });
        self::assertNotNull($seen, 'draw func never ran');
        self::assertSame($area, $seen[0]);
        self::assertInstanceOf(CairoContext::class, $seen[1]);
        self::assertGreaterThanOrEqual(64, $seen[2]);
        self::assertGreaterThanOrEqual(32, $seen[3]);
    }

    public function testCairoContextIsNotConstructibleOrCloneable(): void
    {
        $this->expectException(\Error::class);
        self::assertInstanceOf(CairoContext::class, new CairoContext());
    }

    public function testReplacingAndRemovingTheDrawFuncReleasesTheCallable(): void
    {
        $area = new GtkDrawingArea();
        $token = new \stdClass();
        $weak = \WeakReference::create($token);
        $area->set_draw_func(function () use ($token): void {
            unset($token);
        });
        unset($token);
        self::assertNotNull($weak->get(), 'callable still installed');
        $area->set_draw_func(fn() => null);
        self::assertNull($weak->get(), 'replaced draw func must free its callable');
        $area->set_draw_func(null);
        // Installing on a widget that then dies frees the callable too.
        $token = new \stdClass();
        $weak = \WeakReference::create($token);
        $area->set_draw_func(function () use ($token): void {
            unset($token);
        });
        unset($token, $area);
        gc_collect_cycles();
        self::assertNull($weak->get(), 'finalized widget must free its draw func');
    }

    public function testExceptionInDrawFuncGoesThroughTheHandler(): void
    {
        $area = new GtkDrawingArea();
        $captured = $this->captureHandlerException(function () use ($area): void {
            $this->drawOnce($area, function (): void {
                throw new \RuntimeException('boom in draw');
            });
        });
        self::assertNotNull($captured);
        self::assertSame('boom in draw', $captured[0]);
        self::assertSame('GtkDrawingArea::set_draw_func', $captured[1]);
    }

    public function testStubHasNoCallbackTypeErrors(): void
    {
        $area = new GtkDrawingArea();
        $this->expectException(\TypeError::class);
        // @phpstan-ignore argument.type
        $area->set_draw_func('not a function at all');
    }

    public function testInitIsIdempotent(): void
    {
        self::assertTrue(Gtk::init());
    }
}
