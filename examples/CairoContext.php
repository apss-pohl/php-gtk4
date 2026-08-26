<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GdkRGBA;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\CairoContext - the cairo drawing API, one cell per group of calls.
 *
 * A sampler: paths (rectangle, arc, line_to/close_path), painting (fill,
 * fill_preserve, stroke, stroke_preserve, paint), sources (rgb, rgba, GdkRGBA),
 * the matrix (save/restore, translate, scale, rotate) and text.
 *
 *   bin/php-gtk4 examples/demo.php CairoContext
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'CairoContext',
    'the cairo drawing API, one cell per group of calls',
    function (GtkWindow $win): GtkWidget {
        return Demo::canvas(560, 320, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ): void {
            // paint() floods the clip with the current source - the background.
            $cr->set_source_color(new GdkRGBA(Demo::PAPER));
            $cr->paint();

            $accent = new GdkRGBA(Demo::ACCENT);
            $cell = 130.0;

            $caption = static function (float $x, float $y, string $text) use ($cr): void {
                Demo::text($cr, $x, $y, $text, Demo::MUTED, 11);
            };

            // 1 - rectangle + fill, and a translucent one on top (set_source_rgba).
            $cr->set_source_color($accent);
            $cr->rectangle(20, 30, 90, 60);
            $cr->fill();
            $cr->set_source_rgba(0.9, 0.1, 0.15, 0.55);
            $cr->rectangle(55, 55, 70, 50);
            $cr->fill();
            $caption(20, 125, 'rectangle · fill · rgba');

            // 2 - arc + stroke, then a filled dot (set_source_rgb).
            $cr->set_source_color($accent);
            $cr->set_line_width(6);
            $cr->arc(200, 68, 38, 0, 2 * M_PI);
            $cr->stroke();
            $cr->set_source_rgb(0.18, 0.76, 0.49);
            $cr->arc(200, 68, 12, 0, 2 * M_PI);
            $cr->fill();
            $caption(160, 125, 'arc · stroke · fill');

            // 3 - a path closed by hand, filled and outlined in one go.
            $cr->move_to(300, 100);
            $cr->line_to(345, 26);
            $cr->line_to(390, 100);
            $cr->close_path();
            $cr->set_source_rgba(0.21, 0.52, 0.89, 0.35);
            $cr->fill_preserve();          // keeps the path...
            $cr->set_source_color($accent);
            $cr->set_line_width(3);
            $cr->stroke();                 // ...so it can be outlined too
            $caption(300, 125, 'close_path · fill_preserve');

            // 4 - stroke_preserve: outline, then fill the same path.
            $cr->rectangle(440, 34, 76, 62);
            $cr->set_source_color(new GdkRGBA(Demo::INK));
            $cr->set_line_width(8);
            $cr->stroke_preserve();
            $cr->set_source_rgb(1.0, 0.85, 0.2);
            $cr->fill();
            $caption(440, 125, 'stroke_preserve · fill');

            // 5 - the matrix: save/restore isolate translate + rotate + scale.
            for ($i = 0; $i < 6; $i++) {
                $cr->save();
                $cr->translate(90, 210);
                $cr->rotate($i * M_PI / 8);
                $cr->scale(1.0, 0.55);
                $cr->set_source_rgba(0.21, 0.52, 0.89, 0.25 + $i * 0.12);
                $cr->rectangle(0, -14, 78, 28);
                $cr->fill();
                $cr->restore();            // back to the untransformed matrix
            }
            $caption(20, 275, 'save · translate · rotate · scale · restore');

            // 6 - text: set_font_size + show_text at the current point.
            $cr->set_source_color(new GdkRGBA(Demo::INK));
            $cr->set_font_size(30);
            $cr->move_to(250, 200);
            $cr->show_text('php-gtk4');
            $cr->set_font_size(14);
            $cr->move_to(250, 224);
            $cr->show_text(sprintf('%d x %d px', $width, $height));
            $caption(250, 275, 'set_font_size · show_text');

            // A hairline frame, to show line widths below 1px still render.
            $cr->set_source_color(new GdkRGBA(Demo::MUTED));
            $cr->set_line_width(0.75);
            $cr->rectangle(6.5, 6.5, $width - 13, $height - 13);
            $cr->stroke();
        });
    },
    580,
    340,
);
