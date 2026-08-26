<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GApplicationFlags;
use Gtk4\GdkRGBA;
use Gtk4\GtkApplication;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GApplicationFlags - a bitmask, so a constant class rather than an enum.
 *
 * PHP enums cannot be OR-ed, so flags types are classes of typed constants whose
 * values are verified against GLib's GFlagsClass when the extension loads. Each
 * row below is one constant; the filled ones are set in this application's mask.
 *
 *   bin/php-gtk4 examples/GApplicationFlags.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GApplicationFlags', function (GtkWindow $win, GtkApplication $app): GtkWidget {
    // What Demo::run() built the application with, plus one more for the picture.
    $mask = GApplicationFlags::NON_UNIQUE | GApplicationFlags::HANDLES_OPEN;

    /** @var array<string, int> $flags */
    $flags = (new \ReflectionClass(GApplicationFlags::class))->getConstants();

    return Demo::canvas(520, 340, static function (
        GtkDrawingArea $area,
        CairoContext $cr,
        int $width,
        int $height,
    ) use ($flags, $mask, $app): void {
        Demo::sheet($cr);
        Demo::text($cr, 18, 28, sprintf('mask = %d (0b%b)', $mask, $mask), Demo::INK, 15);

        $y = 52.0;
        foreach ($flags as $name => $value) {
            $set = $value !== 0 && ($mask & $value) === $value;

            $cr->set_source_color(new GdkRGBA($set ? Demo::ACCENT : '#e6e6e6'));
            $cr->rectangle(18, $y - 10, 13, 13);
            $cr->fill();

            Demo::text($cr, 40, $y, $name, $set ? Demo::INK : Demo::MUTED, 12);
            Demo::text($cr, 260, $y, sprintf('%3d  0b%09b', $value, $value), Demo::MUTED, 12);
            $y += 19;
        }

        Demo::text(
            $cr,
            18,
            $y + 14,
            sprintf('application_id = %s', (string) $app->get_application_id()),
            Demo::MUTED,
            12,
        );
    });
}, 540, 360);
