<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkColorspace;
use Gtk4\GdkInterpType;
use Gtk4\GdkMemoryFormat;
use Gtk4\GdkMemoryTexture;
use Gtk4\GdkPixbuf;
use Gtk4\GdkPixbufLoader;
use Gtk4\GdkPixbufRotation;
use Gtk4\GtkButton;
use Gtk4\GtkContentFit;
use Gtk4\GtkPicture;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkPixbuf - the image library: decode, scale, rotate, composite, encode.
 *
 * GTK 4 widgets want a GdkPaintable, and the direct pixbuf-to-texture calls are deprecated, so
 * pixels cross through bytes: read_pixel_bytes() into a GdkMemoryTexture (shown by the
 * GtkPicture here), and GdkTextureDownloader the other way. The page builds a small picture
 * with cairo, encodes it as PNG with GdkPixbuf, decodes it again through a GdkPixbufLoader and
 * walks through transforms on every click; the status line shows what each one did and how
 * big the JPEG encoding of the result is.
 *
 *   bin/php-gtk4 examples/demo.php GdkPixbuf
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkPixbuf',
    'the image library: decode, scale, rotate, composite, encode',
    function (GtkWindow $win): GtkWidget {
        // A source image, painted by cairo and pushed through the PNG encoder and the loader
        // once, so the rest of the page works on what a file would have given it.
        $source = new GdkPixbuf(GdkColorspace::Rgb, true, 8, 120, 80);
        $source->fill(0x3584e4ff);
        $badge = new GdkPixbuf(GdkColorspace::Rgb, true, 8, 40, 40);
        $badge->fill(0xe01b24ff);
        $badge->composite($source, 70, 30, 40, 40, 70.0, 30.0, 1.0, 1.0, GdkInterpType::Nearest, 255);
        $loader = new GdkPixbufLoader();
        $loader->write_bytes($source->save_to_bufferv('png'));
        $loader->close();
        $original = $loader->get_pixbuf() ?? throw new \RuntimeException('a PNG the loader wrote itself');

        /** @var list<array{string, callable(GdkPixbuf): GdkPixbuf}> $steps */
        $steps = [
            ['as decoded (PNG through GdkPixbufLoader)', static fn(GdkPixbuf $p): GdkPixbuf => $p],
            ['scale_simple(240, 160, Bilinear)', static fn(GdkPixbuf $p): GdkPixbuf
                => $p->scale_simple(240, 160, GdkInterpType::Bilinear) ?? $p],
            ['rotate_simple(Clockwise)', static fn(GdkPixbuf $p): GdkPixbuf
                => $p->rotate_simple(GdkPixbufRotation::Clockwise) ?? $p],
            ['flip(horizontal)', static fn(GdkPixbuf $p): GdkPixbuf => $p->flip(true) ?? $p],
            ['new_subpixbuf(60, 20, 60, 60)', static fn(GdkPixbuf $p): GdkPixbuf => $p->new_subpixbuf(60, 20, 60, 60)],
            ['saturate_and_pixelate(0.0, true)', static function (GdkPixbuf $p): GdkPixbuf {
                $grey = $p->copy() ?? $p;
                $p->saturate_and_pixelate($grey, 0.0, true);
                return $grey;
            }],
        ];

        $picture = new GtkPicture();
        $picture->set_content_fit(GtkContentFit::Contain);
        $picture->set_size_request(260, 180);
        $show = static function (GdkPixbuf $pixbuf) use ($picture): void {
            $format = $pixbuf->get_has_alpha() ? GdkMemoryFormat::R8g8b8a8 : GdkMemoryFormat::R8g8b8;
            $picture->set_paintable(new GdkMemoryTexture(
                $pixbuf->get_width(),
                $pixbuf->get_height(),
                $format,
                $pixbuf->read_pixel_bytes(),
                $pixbuf->get_rowstride(),
            ));
        };

        $step = 0;
        $render = function () use (&$step, $steps, $original, $show): void {
            [$name, $apply] = $steps[$step % count($steps)];
            $result = $apply($original);
            $show($result);
            Demo::status(sprintf(
                '%s → %dx%d, %d channels · JPEG %d bytes',
                $name,
                $result->get_width(),
                $result->get_height(),
                $result->get_n_channels(),
                strlen($result->save_to_bufferv('jpeg', ['quality' => '85'])),
            ));
        };

        $button = new GtkButton();
        $button->set_child($picture);
        $button->connect('clicked', function () use (&$step, $render): void {
            $step++;
            $render();
        });
        $render();
        return $button;
    },
    320,
    240,
);
