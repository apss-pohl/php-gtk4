<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GdkRGBA;
use Gtk4\GdkTexture;
use Gtk4\GError;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkTexture - decoded pixel data; byte buffers are plain PHP strings.
 *
 * An 8x8 checkerboard PNG is assembled by hand (no GD), decoded with
 * new_from_bytes(), written back out with save_to_png() and read again with
 * new_from_filename(). The canvas draws the same checkerboard the texture holds,
 * magnified, next to what each texture reports.
 *
 *   bin/php-gtk4 examples/demo.php GdkTexture
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkTexture',
    'decoded pixel data; byte buffers are plain PHP strings',
    function (GtkWindow $win): GtkWidget {
        $size = 8;
        $light = "\xfd\xfd\xfd\xff";
        $dark = "\x35\x84\xe4\xff";

        // Raw RGBA scanlines, each prefixed with PNG filter type 0.
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

        $texture = GdkTexture::new_from_bytes($png);          // throws GError on bad data

        // Round-trip through the filesystem: save_to_png() then new_from_filename().
        $path = sys_get_temp_dir() . '/php-gtk4-texture.png';
        $texture->save_to_png($path);
        $reloaded = GdkTexture::new_from_filename($path);

        // The fallible C APIs throw instead of returning false.
        $failure = 'none';
        try {
            GdkTexture::new_from_filename($path . '.missing');
        } catch (GError $e) {
            $failure = sprintf('%s / %d: %s', $e->getDomain(), $e->getCode(), $e->getMessage());
        }

        return Demo::canvas(520, 300, static function (
            GtkDrawingArea $area,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (
            $size,
            $texture,
            $reloaded,
            $png,
            $path,
            $failure
        ): void {
            Demo::sheet($cr);

            // The pixels the texture carries, drawn at 18x.
            $scale = 18.0;
            for ($y = 0; $y < $size; $y++) {
                for ($x = 0; $x < $size; $x++) {
                    $cr->set_source_color(new GdkRGBA((($x + $y) % 2) === 0 ? Demo::ACCENT : '#fdfdfd'));
                    $cr->rectangle(20 + $x * $scale, 30 + $y * $scale, $scale, $scale);
                    $cr->fill();
                }
            }
            $cr->set_source_color(new GdkRGBA(Demo::MUTED));
            $cr->set_line_width(1);
            $cr->rectangle(20.5, 30.5, $size * $scale, $size * $scale);
            $cr->stroke();
            Demo::text($cr, 20, 190, 'the 8x8 pixels, magnified 18x', Demo::MUTED, 11);

            $x = 190.0;
            Demo::text($cr, $x, 44, 'new_from_bytes()', Demo::MUTED, 12);
            Demo::text($cr, $x, 62, sprintf('%d x %d px', $texture->width, $texture->height), Demo::INK, 13);
            Demo::text($cr, $x, 80, sprintf('source PNG %d bytes', strlen($png)), Demo::INK, 12);
            $encoded = sprintf('save_to_png_bytes() %d bytes', strlen($texture->save_to_png_bytes()));
            Demo::text($cr, $x, 98, $encoded, Demo::INK, 12);

            Demo::text($cr, $x, 132, 'save_to_png() + new_from_filename()', Demo::MUTED, 12);
            $dimensions = sprintf('%d x %d px', $reloaded->get_width(), $reloaded->get_height());
            Demo::text($cr, $x, 150, $dimensions, Demo::INK, 13);
            Demo::text($cr, $x, 168, basename($path), Demo::INK, 12);

            Demo::text($cr, $x, 202, 'missing file:', Demo::MUTED, 12);
            Demo::text($cr, $x, 220, $failure, Demo::WARN, 11);
        });
    },
    540,
    340,
);
