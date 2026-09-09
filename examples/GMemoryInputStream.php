<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkColorspace;
use Gtk4\GdkMemoryFormat;
use Gtk4\GdkMemoryTexture;
use Gtk4\GdkPixbuf;
use Gtk4\GMemoryInputStream;
use Gtk4\GtkButton;
use Gtk4\GtkContentFit;
use Gtk4\GtkPicture;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GMemoryInputStream - a PHP string as a readable GIO stream.
 *
 * Everything GIO can decode from a stream can therefore be decoded from a variable: this page
 * encodes a pixbuf as PNG, hands the bytes to a stream and reads the image back out through
 * GdkPixbuf::new_from_stream(), which is the API a downloaded or embedded image goes through.
 * Click to cycle the decoders - whole, scaled, and scaled without keeping the aspect ratio.
 *
 *   bin/php-gtk4 examples/demo.php GMemoryInputStream
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GMemoryInputStream',
    'a PHP string as a readable GIO stream',
    function (GtkWindow $win): GtkWidget {
        // The "file": a pixbuf encoded to PNG, so what the stream carries is real image bytes
        // and not something this page invented a shortcut for.
        $source = new GdkPixbuf(GdkColorspace::Rgb, true, 8, 120, 80);
        $source->fill(0x3584e4ff);
        $badge = new GdkPixbuf(GdkColorspace::Rgb, true, 8, 40, 40);
        $badge->fill(0xe01b24ff);
        $badge->composite($source, 70, 30, 40, 40, 70.0, 30.0, 1.0, 1.0, \Gtk4\GdkInterpType::Nearest, 255);
        $png = $source->save_to_bufferv('png');

        /** @var list<array{string, callable(GMemoryInputStream): GdkPixbuf}> $decoders */
        $decoders = [
            ['new_from_stream()', static fn(GMemoryInputStream $s): GdkPixbuf
                => GdkPixbuf::new_from_stream($s)],
            ['new_from_stream_at_scale(240, 160, keep aspect)', static fn(GMemoryInputStream $s): GdkPixbuf
                => GdkPixbuf::new_from_stream_at_scale($s, 240, 160, true)],
            ['new_from_stream_at_scale(240, 160, stretch)', static fn(GMemoryInputStream $s): GdkPixbuf
                => GdkPixbuf::new_from_stream_at_scale($s, 240, 160, false)],
        ];

        $picture = new GtkPicture();
        $picture->set_content_fit(GtkContentFit::Contain);
        $picture->set_size_request(260, 180);

        $step = 0;
        $render = function () use (&$step, $decoders, $png, $picture): void {
            [$name, $decode] = $decoders[$step % count($decoders)];
            // A fresh stream each time: a stream is consumed, not rewound.
            $stream = GMemoryInputStream::new_from_bytes($png);
            $pixbuf = $decode($stream);
            $stream->close(null);
            $picture->set_paintable(new GdkMemoryTexture(
                $pixbuf->get_width(),
                $pixbuf->get_height(),
                GdkMemoryFormat::R8g8b8a8,
                $pixbuf->read_pixel_bytes(),
                $pixbuf->get_rowstride(),
            ));
            Demo::status(sprintf(
                '%d PNG bytes in a GMemoryInputStream → %s → %dx%d · closed: %s',
                strlen($png),
                $name,
                $pixbuf->get_width(),
                $pixbuf->get_height(),
                $stream->is_closed() ? 'yes' : 'no',
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
