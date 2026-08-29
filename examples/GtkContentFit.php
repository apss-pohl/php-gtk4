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
 * Gtk4\GtkContentFit - how a GtkPicture fits its paintable into the allocation.
 *
 * A GEnum bound as a native PHP enum. Fill stretches and ignores the aspect
 * ratio, Contain letterboxes, Cover crops, ScaleDown only ever shrinks. Four
 * pictures of the same 4x2 texture show every case at once; the wide one
 * underneath cycles through them.
 *
 *   bin/php-gtk4 examples/demo.php GtkContentFit
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkContentFit',
    'how a GtkPicture fits its paintable into the allocation',
    function (GtkWindow $win): GtkWidget {
        // A 4x2 PNG, accent on the left half - the 2:1 aspect shows what each fit does with it.
        $raw = "\x00\x35\x84\xe4\xff\x35\x84\xe4\xff\xfd\xfd\xfd\xff\xfd\xfd\xfd\xff";
        $raw .= $raw;
        $chunk = static fn(string $type, string $data): string
        => pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
        $png = "\x89PNG\r\n\x1a\n"
        . $chunk('IHDR', pack('NNCCCCC', 4, 2, 8, 6, 0, 0, 0))
        . $chunk('IDAT', (string) gzcompress($raw, 9))
        . $chunk('IEND', '');
        $texture = GdkTexture::new_from_bytes($png);

        $row = new GtkBox(GtkOrientation::Horizontal, 12);
        $row->set_homogeneous(true);
        foreach (GtkContentFit::cases() as $case) {
            $picture = GtkPicture::new_for_paintable($texture);
            $picture->set_content_fit($case);   // typed setter takes the enum, never an int
            $picture->set_size_request(80, 80);
            $picture->add_css_class('card');
            $column = new GtkBox(GtkOrientation::Vertical, 6);
            $column->append($picture);
            $column->append(Demo::label(sprintf('<small>%s (%d)</small>', $case->name, $case->value)));
            $row->append($column);
        }

        // One wide picture cycles the cases so the change itself is visible.
        $cycling = GtkPicture::new_for_paintable($texture);
        $cycling->set_vexpand(true);
        $cycling->add_css_class('card');
        $cases = GtkContentFit::cases();
        $step = 0;
        $show = function () use ($cycling, $cases, &$step): void {
            $case = $cases[$step % count($cases)];
            $cycling->content_fit = $case;      // property access takes it too
            Demo::status(sprintf(
                'set_content_fit(GtkContentFit::%s) - get_content_fit() = %s',
                $case->name,
                $cycling->get_content_fit()->name,
            ));
        };
        $show();
        GLib::timeout_add(1200, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });

        $page = new GtkBox(GtkOrientation::Vertical, 18);
        $row->set_halign(GtkAlign::Fill);
        $page->append($row);
        $page->append($cycling);
        return $page;
    },
    520,
    380,
);
