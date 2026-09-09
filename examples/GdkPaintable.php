<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkPaintableFlags;
use Gtk4\GdkTexture;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkPicture;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkPaintable - the interface behind everything GTK can draw as an image.
 *
 * GdkPaintable is a real PHP interface; GdkTexture implements it, and GtkPicture
 * and GtkImage take any implementor. A 12x6 texture reports its intrinsic size,
 * aspect ratio and flags, get_current_image() hands back an immutable snapshot
 * (for a texture: itself), and a GtkPicture shows the paintable it came from.
 *
 *   bin/php-gtk4 examples/demo.php GdkPaintable
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkPaintable',
    'the interface behind everything GTK can draw as an image',
    function (GtkWindow $win): GtkWidget {
        // A 12x6 PNG with a diagonal split, so the picture has an orientation.
        $w = 12;
        $h = 6;
        $raw = '';
        for ($y = 0; $y < $h; $y++) {
            $raw .= "\x00";
            for ($x = 0; $x < $w; $x++) {
                $raw .= $x < $y * 2 ? "\x35\x84\xe4\xff" : "\xfd\xfd\xfd\xff";
            }
        }
        $chunk = static fn(string $type, string $data): string
        => pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
        $png = "\x89PNG\r\n\x1a\n"
        . $chunk('IHDR', pack('NNCCCCC', $w, $h, 8, 6, 0, 0, 0))
        . $chunk('IDAT', (string) gzcompress($raw, 9))
        . $chunk('IEND', '');
        $texture = GdkTexture::new_from_bytes($png);

        // Everything below is interface API - it works on any GdkPaintable, not just textures.
        $paintable = $texture;
        $current = $paintable->get_current_image();
        $flags = $paintable->get_flags();
        $names = [];
        foreach (['SIZE' => GdkPaintableFlags::SIZE, 'CONTENTS' => GdkPaintableFlags::CONTENTS] as $name => $bit) {
            if (($flags & $bit) !== 0) {
                $names[] = $name;
            }
        }

        $status = Demo::label(sprintf(
            "<tt>implements               %s\nintrinsic_width          %d\nintrinsic_height         %d\n"
            . "intrinsic_aspect_ratio   %.3f\nflags                    %d (%s)\ncurrent_image            %s%s</tt>",
            implode(', ', array_keys(class_implements($paintable) ?: [])),
            $paintable->get_intrinsic_width(),
            $paintable->get_intrinsic_height(),
            $paintable->get_intrinsic_aspect_ratio(),
            $flags,
            $names === [] ? 'none' : implode(' | ', $names),
            $current::class,
            $current === $paintable ? ' (same handle)' : '',
        ));

        $picture = GtkPicture::new_for_paintable($current);
        $picture->set_vexpand(true);
        $picture->add_css_class('card');
        Demo::status(sprintf(
            'a texture is static: flags SIZE | CONTENTS = %d means neither size nor contents ever change',
            GdkPaintableFlags::SIZE | GdkPaintableFlags::CONTENTS,
        ));

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($picture);
        return $page;
    },
    520,
    360,
);
