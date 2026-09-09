<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkTexture;
use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkContentFit;
use Gtk4\GtkOrientation;
use Gtk4\GtkPicture;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPicture - shows a GdkPaintable at its natural size, or fitted.
 *
 * Where GtkImage is for icons, GtkPicture is for pictures: it scales its
 * paintable into whatever space it gets, ruled by GtkContentFit and can_shrink.
 * The paintable here is a 16x8 two-tone texture built from a hand-made PNG;
 * the page cycles the content fit and the shrink flag so the picture visibly
 * changes shape.
 *
 *   bin/php-gtk4 examples/demo.php GtkPicture
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPicture',
    'shows a GdkPaintable at its natural size, or fitted',
    function (GtkWindow $win): GtkWidget {
        // A 16x8 PNG: left half accent, right half white - the 2:1 aspect makes fitting obvious.
        $w = 16;
        $h = 8;
        $raw = '';
        for ($y = 0; $y < $h; $y++) {
            $raw .= "\x00" . str_repeat("\x35\x84\xe4\xff", $w / 2) . str_repeat("\xfd\xfd\xfd\xff", $w / 2);
        }
        $chunk = static fn(string $type, string $data): string
        => pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
        $png = "\x89PNG\r\n\x1a\n"
        . $chunk('IHDR', pack('NNCCCCC', $w, $h, 8, 6, 0, 0, 0))
        . $chunk('IDAT', (string) gzcompress($raw, 9))
        . $chunk('IEND', '');
        $texture = GdkTexture::new_from_bytes($png);

        $picture = GtkPicture::new_for_paintable($texture);
        $picture->set_alternative_text('a blue and white rectangle');
        $picture->set_vexpand(true);
        $picture->set_hexpand(true);
        $picture->add_css_class('card');

        $status = Demo::label();
        $describe = function () use ($picture, $status): void {
            $paintable = $picture->get_paintable();
            $status->set_markup(sprintf(
                "<tt>paintable         %s\ncontent_fit       %s\ncan_shrink        %s\nalternative_text  %s</tt>",
                $paintable === null ? 'null' : $paintable::class,
                $picture->get_content_fit()->name,
                $picture->get_can_shrink() ? 'true' : 'false',
                htmlspecialchars($picture->get_alternative_text() ?? 'null'),
            ));
        };

        // Every content fit, then the same with can_shrink off (the picture insists on 16x8 minimum).
        $fits = GtkContentFit::cases();
        $step = 0;
        GLib::timeout_add(1200, function () use ($picture, $fits, $describe, &$step): bool {
            $picture->set_content_fit($fits[$step % count($fits)]);
            $picture->set_can_shrink(intdiv($step, count($fits)) % 2 === 0);
            $step++;
            $describe();
            return true;
        });

        $describe();

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $status->set_halign(GtkAlign::Start);
        $page->append($status);
        $page->append($picture);
        return $page;
    },
    520,
    380,
);
