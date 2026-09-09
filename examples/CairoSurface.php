<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GdkRGBA;
use Gtk4\GdkTexture;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\CairoSurface - the pixels a draw func paints an image from.
 *
 * GTK 3 painted an image into a cairo context with gdk_cairo_set_source_pixbuf(); GTK 4 has no
 * such call and no GdkPixbuf. GdkTexture::download() is the way in: GDK writes the texture out
 * in exactly cairo's ARGB32 layout, so what comes back is an image surface that
 * CairoContext::set_source_surface() can use as the source pattern. The page decodes an 8x8
 * PNG, downloads it, and paints it three times - once as-is, once scaled up, once as the fill
 * pattern of a circle.
 *
 *   bin/php-gtk4 examples/demo.php CairoSurface
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'CairoSurface',
    'the pixels a draw func paints an image from',
    function (GtkWindow $win): GtkWidget {
        $size = 8;
        $light = "\xfd\xfd\xfd\xff";
        $dark = "\x35\x84\xe4\xff";

        // Raw RGBA scanlines, each prefixed with PNG filter type 0 (no GD needed).
        $raw = '';
        for ($y = 0; $y < $size; $y++) {
            $raw .= "\x00";
            for ($x = 0; $x < $size; $x++) {
                $raw .= (($x + $y) % 2) === 0 ? $dark : $light;
            }
        }
        $chunk = static fn(string $type, string $data): string
        => pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
        $png = "\x89PNG\r\n\x1a\n"
            . $chunk('IHDR', pack('NNCCCCC', $size, $size, 8, 6, 0, 0, 0))
            . $chunk('IDAT', (string) gzcompress($raw, 9))
            . $chunk('IEND', '');

        $texture = GdkTexture::new_from_bytes($png);
        $surface = $texture->download();     // an ARGB32 image surface, ready for cairo

        return Demo::canvas(520, 320, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (
            $surface,
            $texture
        ): void {
            Demo::sheet($cr);

            // 1:1 - the surface's own 8x8 pixels at the top left.
            $cr->save();
            $cr->set_source_surface($surface, 30.0, 46.0);
            $cr->rectangle(30, 46, $surface->get_width(), $surface->get_height());
            $cr->fill();
            $cr->restore();
            Demo::text($cr, 30, 34, '1:1', Demo::MUTED, 12);

            // Scaled: the transform applies to the source pattern too.
            $cr->save();
            $cr->translate(110, 46);
            $cr->scale(12.0, 12.0);
            $cr->set_source_surface($surface, 0.0, 0.0);
            $cr->rectangle(0, 0, $surface->get_width(), $surface->get_height());
            $cr->fill();
            $cr->restore();
            Demo::text($cr, 110, 34, 'scaled 12x', Demo::MUTED, 12);

            // The same source as the fill pattern of a circle: the surface repeats under it.
            $cr->save();
            $cr->scale(7.0, 7.0);
            $cr->set_source_surface($surface, 39.0, 26.0);
            $cr->arc(330 / 7.0, 240 / 7.0, 56 / 7.0, 0, 2 * M_PI);
            $cr->fill();
            $cr->restore();
            $cr->set_source_color(new GdkRGBA(Demo::MUTED));
            $cr->set_line_width(1);
            $cr->arc(330, 240, 56, 0, 2 * M_PI);
            $cr->stroke();
            Demo::text($cr, 274, 172, 'as the fill pattern of a circle', Demo::MUTED, 12);

            $facts = sprintf(
                'texture %dx%d -> surface %dx%d',
                $texture->get_width(),
                $texture->get_height(),
                $surface->get_width(),
                $surface->get_height(),
            );
            Demo::text($cr, 30, 176, $facts, Demo::INK, 13);
            Demo::text($cr, 30, 196, 'GdkTexture::download() + set_source_surface()', Demo::MUTED, 11);
        });
    },
    540,
    360,
);
