<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkPaintableFlags;
use Gtk4\GdkTexture;
use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkOrientation;
use Gtk4\GtkPicture;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkPaintableFlags - what a paintable promises never to change.
 *
 * A GFlags type: a final class of int constants (GFlags stay ints, combined with
 * `|`, checked with `&`). SIZE means the intrinsic size is fixed, CONTENTS that
 * the pixels are; a GdkTexture sets both. The page decodes the flags of a real
 * texture and cycles through every combination of the bits to show how they
 * are read.
 *
 *   bin/php-gtk4 examples/demo.php GdkPaintableFlags
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkPaintableFlags',
    'what a paintable promises never to change - a GFlags bitfield',
    function (GtkWindow $win): GtkWidget {
        $constants = array_filter(new \ReflectionClass(GdkPaintableFlags::class)->getConstants(), 'is_int');

        // A 2x2 PNG is enough for a texture that reports its flags.
        $raw = "\x00\x35\x84\xe4\xff\xfd\xfd\xfd\xff\x00\xfd\xfd\xfd\xff\x35\x84\xe4\xff";
        $chunk = static fn(string $type, string $data): string
        => pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
        $png = "\x89PNG\r\n\x1a\n"
        . $chunk('IHDR', pack('NNCCCCC', 2, 2, 8, 6, 0, 0, 0))
        . $chunk('IDAT', (string) gzcompress($raw, 9))
        . $chunk('IEND', '');
        $texture = GdkTexture::new_from_bytes($png);
        $real = $texture->get_flags();

        $decode = static fn(int $flags): string => implode(' | ', array_keys(
            array_filter($constants, static fn(int $bit): bool => ($flags & $bit) !== 0),
        )) ?: 'none';

        $picture = GtkPicture::new_for_paintable($texture);
        $picture->set_size_request(64, 64);
        $picture->set_halign(GtkAlign::Center);
        $picture->add_css_class('card');

        $face = Demo::label();
        $rows = array_map(
            static fn(string $k, int $v): string => sprintf('%-9s = %d', $k, $v),
            array_keys($constants),
            $constants,
        );
        $head = sprintf(
            "<tt>%s\n\nGdkTexture::get_flags() = %d  (%s)</tt>\n\n",
            implode("\n", $rows),
            $real,
            $decode($real),
        );

        // Walk every combination 0..3 so each bit is seen set and clear.
        $step = 0;
        $show = function (int $flags) use ($face, $head, $decode): void {
            $face->set_markup($head . sprintf(
                "flags <b>%d</b> = <b>%s</b>\n<small>SIZE %s · CONTENTS %s</small>",
                $flags,
                $decode($flags),
                ($flags & GdkPaintableFlags::SIZE) !== 0 ? 'set' : 'clear',
                ($flags & GdkPaintableFlags::CONTENTS) !== 0 ? 'set' : 'clear',
            ));
        };
        $show(0);
        GLib::timeout_add(1000, function () use ($show, &$step): bool {
            $show(++$step % 4);
            return true;
        });

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $face->set_halign(GtkAlign::Start);
        $page->append($face);
        $page->append($picture);
        return $page;
    },
    480,
    360,
);
