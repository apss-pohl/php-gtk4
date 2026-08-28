<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GdkMemoryFormat;
use Gtk4\GdkTexture;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkMemoryFormat - the pixel layout a texture keeps its data in.
 *
 * A 4x4 PNG is decoded into a GdkTexture; get_format() names the layout GDK chose
 * for it (premultiplied RGBA for an alpha PNG). The canvas draws the pixels.
 *
 *   bin/php-gtk4 examples/demo.php GdkMemoryFormat
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkMemoryFormat',
    'the pixel layout of a texture',
    function (GtkWindow $win): GtkWidget {
        $size = 4;
        $raw = '';
        for ($y = 0; $y < $size; $y++) {
            $raw .= "\x00";
            for ($x = 0; $x < $size; $x++) {
                $raw .= (($x + $y) % 2) === 0 ? "\x35\x84\xe4\xff" : "\xfd\xfd\xfd\xff";
            }
        }
        $chunk = static fn(string $type, string $data): string
        => pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
        $png = "\x89PNG\r\n\x1a\n"
        . $chunk('IHDR', pack('NNCCCCC', $size, $size, 8, 6, 0, 0, 0))
        . $chunk('IDAT', (string) gzcompress($raw, 9))
        . $chunk('IEND', '');
        $texture = GdkTexture::new_from_bytes($png);
        $format = $texture->get_format();

        return Demo::canvas(420, 220, static function (
            GtkDrawingArea $self,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (
            $texture,
            $format,
            $size
        ): void {
            Demo::sheet($cr);
            $cell = 30;
            for ($y = 0; $y < $size; $y++) {
                for ($x = 0; $x < $size; $x++) {
                    $dark = (($x + $y) % 2) === 0;
                    $cr->set_source_rgb($dark ? 0.2 : 0.99, $dark ? 0.5 : 0.99, $dark ? 0.9 : 0.99);
                    $cr->rectangle(20 + $x * $cell, 20 + $y * $cell, $cell, $cell);
                    $cr->fill();
                }
            }
            $dims = sprintf('%dx%d texture', $texture->get_width(), $texture->get_height());
            Demo::text($cr, 160, 50, $dims, Demo::INK, 14);
            Demo::text($cr, 160, 80, sprintf('get_format() = GdkMemoryFormat::%s', $format->name), Demo::INK, 12);
            $count = count(GdkMemoryFormat::cases());
            Demo::text($cr, 160, 104, sprintf('value %d of %d formats', $format->value, $count), Demo::MUTED, 12);
        });
    },
    480,
    340,
);
