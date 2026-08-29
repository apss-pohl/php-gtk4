<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkTexture;
use Gtk4\GLib;
use Gtk4\GtkAlign;
use Gtk4\GtkBox;
use Gtk4\GtkEntry;
use Gtk4\GtkEntryIconPosition;
use Gtk4\GtkIconSize;
use Gtk4\GtkImage;
use Gtk4\GtkImageType;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkImageType - where an image's pixels come from.
 *
 * GtkImage::get_storage_type() and GtkEntry::get_icon_storage_type() answer
 * with this enum: Empty (nothing set), IconName (a themed icon looked up by
 * name), Gicon (a GIcon - not constructible from PHP, so only ever read back)
 * or Paintable (a GdkPaintable such as a GdkTexture). The timer feeds an image
 * and an entry icon from each source in turn, and the readout shows the storage
 * type each one reports.
 *
 *   bin/php-gtk4 examples/demo.php GtkImageType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkImageType',
    'where an image\'s pixels come from - empty, icon name, GIcon or paintable',
    function (GtkWindow $win): GtkWidget {
        $image = new GtkImage();
        $image->set_icon_size(GtkIconSize::Large);
        $image->set_pixel_size(64);
        $entry = new GtkEntry();
        $entry->set_text('the secondary icon follows the image');
        $entry->set_hexpand(true);

        // A 16x16 orange PNG built in memory - the smallest paintable there is.
        $size = 16;
        $raw = '';
        for ($y = 0; $y < $size; $y++) {
            $raw .= "\0" . str_repeat("\xf0\x90\x20\xff", $size);
        }
        $chunk = static fn(string $type, string $data): string
        => pack('N', strlen($data)) . $type . $data . pack('N', crc32($type . $data));
        $texture = GdkTexture::new_from_bytes(
            "\x89PNG\r\n\x1a\n"
            . $chunk('IHDR', pack('NNCCCCC', $size, $size, 8, 6, 0, 0, 0))
            . $chunk('IDAT', (string) gzcompress($raw, 9))
            . $chunk('IEND', ''),
        );

        $sources = [
            'clear() / set_icon_from_icon_name(null)' => static function () use ($image, $entry): void {
                $image->clear();
                $entry->set_icon_from_icon_name(GtkEntryIconPosition::Secondary, null);
            },
            'set_from_icon_name("face-smile")' => static function () use ($image, $entry): void {
                $image->set_from_icon_name('face-smile');
                $entry->set_icon_from_icon_name(GtkEntryIconPosition::Secondary, 'face-smile');
            },
            'set_from_paintable($texture)' => static function () use ($image, $entry, $texture): void {
                $image->set_from_paintable($texture);
                $entry->set_icon_from_paintable(GtkEntryIconPosition::Secondary, $texture);
            },
        ];
        $names = array_keys($sources);
        $step = 0;

        $readout = Demo::label();
        $readout->set_halign(GtkAlign::Start);
        $show = function () use ($image, $entry, $sources, $names, $readout, &$step): void {
            $name = $names[$step % count($names)];
            $sources[$name]();
            $type = $image->get_storage_type();
            $iconType = $entry->get_icon_storage_type(GtkEntryIconPosition::Secondary);
            $rows = array_map(
                static fn(GtkImageType $c): string => sprintf(
                    '%s %-9s = %d',
                    $c === $type ? '●' : '○',
                    $c->name,
                    $c->value,
                ),
                GtkImageType::cases(),
            );
            $readout->set_markup(sprintf(
                "<b>%s</b>\nGtkImage::get_storage_type() = %s\n"
                . "GtkEntry::get_icon_storage_type(Secondary) = %s\n\n<tt>%s</tt>",
                htmlspecialchars($name),
                $type->name,
                $iconType->name,
                implode("\n", $rows),
            ));
        };

        $show();
        GLib::timeout_add(1200, function () use ($show, &$step): bool {
            $step++;
            $show();
            return true;
        });
        Demo::status(GtkImageType::from(3)->name . ' = GtkImageType::from(3)');

        $page = new GtkBox(GtkOrientation::Vertical, 12);
        $image->set_halign(GtkAlign::Center);
        $page->append($image);
        $page->append($entry);
        $page->append($readout);
        return $page;
    },
    560,
    400,
);
