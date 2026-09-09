<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkColorspace;
use Gtk4\GdkMemoryFormat;
use Gtk4\GdkMemoryTexture;
use Gtk4\GdkPixbuf;
use Gtk4\GMemoryInputStream;
use Gtk4\GMemoryOutputStream;
use Gtk4\GtkButton;
use Gtk4\GtkContentFit;
use Gtk4\GtkPicture;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GMemoryOutputStream - a stream that collects what is written into it.
 *
 * The counterpart of GMemoryInputStream: anything GIO can write to a stream can be written to a
 * PHP string. GdkPixbuf encodes straight into one here, and the bytes go back through the read
 * side to be decoded again - out to PNG and in again without touching the disk. The buffer
 * belongs to the stream until it is closed, which is what steal_as_bytes() insists on.
 *
 *   bin/php-gtk4 examples/demo.php GMemoryOutputStream
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GMemoryOutputStream',
    'a stream that collects what is written into it',
    function (GtkWindow $win): GtkWidget {
        $source = new GdkPixbuf(GdkColorspace::Rgb, true, 8, 120, 80);
        $source->fill(0x3584e4ff);

        $picture = new GtkPicture();
        $picture->set_content_fit(GtkContentFit::Contain);
        $picture->set_size_request(240, 160);

        $formats = ['png', 'jpeg', 'bmp'];
        $at = 0;
        $render = function () use (&$at, $formats, $source, $picture): void {
            $type = $formats[$at % count($formats)];

            // Out: the pixbuf encodes itself into the stream.
            $out = GMemoryOutputStream::new_resizable();
            $source->save_to_streamv($out, $type, null, null, null);
            $out->close(null);
            $bytes = $out->steal_as_bytes();

            // ...and in again, through the read side.
            $decoded = GdkPixbuf::new_from_stream(GMemoryInputStream::new_from_bytes($bytes));
            $picture->set_paintable(new GdkMemoryTexture(
                $decoded->get_width(),
                $decoded->get_height(),
                $decoded->get_has_alpha() ? GdkMemoryFormat::R8g8b8a8 : GdkMemoryFormat::R8g8b8,
                $decoded->read_pixel_bytes(),
                $decoded->get_rowstride(),
            ));
            Demo::status(sprintf(
                'encoded as %s into %d bytes, decoded back to %dx%d - no file anywhere',
                strtoupper($type),
                strlen($bytes),
                $decoded->get_width(),
                $decoded->get_height(),
            ));
        };

        $button = new GtkButton();
        $button->set_child($picture);
        $button->connect('clicked', function () use (&$at, $render): void {
            $at++;
            $render();
        });
        $render();
        return $button;
    },
    360,
    260,
);
