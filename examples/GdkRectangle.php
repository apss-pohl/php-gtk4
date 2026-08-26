<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GLib;
use Gtk4\GdkRGBA;
use Gtk4\GdkRectangle;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkRectangle - a boxed integer rectangle.
 *
 * Two rectangles are drawn outlined; their intersect() is filled solid and their
 * union() is the thin frame around both. A dot travels across the canvas and
 * turns green exactly while contains_point() is true for the first rectangle.
 *
 *   bin/php-gtk4 examples/GdkRectangle.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GdkRectangle', function (GtkWindow $win): GtkWidget {
    $a = new GdkRectangle(40, 40, 180, 120);
    $b = new GdkRectangle(150, 100, 200, 110);

    $probeX = 0;
    $area = Demo::canvas(420, 300, static function (
        GtkDrawingArea $self,
        CairoContext $cr,
        int $width,
        int $height,
    ) use ($a, $b, &$probeX): void {
        Demo::sheet($cr);

        $outline = static function (GdkRectangle $r, string $css, float $lineWidth) use ($cr): void {
            $cr->set_source_color(new GdkRGBA($css));
            $cr->set_line_width($lineWidth);
            $cr->rectangle($r->x, $r->y, $r->width, $r->height);
            $cr->stroke();
        };

        $outline($a->union($b), Demo::MUTED, 1.0);          // smallest rectangle containing both
        $outline($a, Demo::ACCENT, 2.0);
        $outline($b, Demo::INK, 2.0);

        $overlap = $a->intersect($b);                        // null when they do not touch
        if ($overlap !== null) {
            $cr->set_source_rgba(0.21, 0.52, 0.89, 0.35);
            $cr->rectangle($overlap->x, $overlap->y, $overlap->width, $overlap->height);
            $cr->fill();
            Demo::text(
                $cr,
                $overlap->x + 6,
                $overlap->y + 20,
                sprintf('intersect %dx%d', $overlap->width, $overlap->height),
                Demo::INK,
                12,
            );
        }

        $probeY = 235;
        $inside = $a->contains_point($probeX, $probeY);
        $cr->set_source_color(new GdkRGBA($inside ? Demo::GOOD : Demo::WARN));
        $cr->arc($probeX, $probeY, 7, 0, 2 * M_PI);
        $cr->fill();

        Demo::text($cr, 14, 262, sprintf('$a = %d,%d %dx%d', $a->x, $a->y, $a->width, $a->height), Demo::ACCENT, 12);
        Demo::text($cr, 14, 278, sprintf('$b = %d,%d %dx%d', $b->x, $b->y, $b->width, $b->height), Demo::INK, 12);
        Demo::text(
            $cr,
            14,
            294,
            sprintf('$a->contains_point(%d, %d) = %s', $probeX, $probeY, $inside ? 'true' : 'false'),
            $inside ? Demo::GOOD : Demo::WARN,
            12,
        );
    });

    GLib::timeout_add(30, function () use ($area, &$probeX): bool {
        $probeX = ($probeX + 4) % 420;
        $area->queue_draw();
        return true;
    });

    // Boxed values compare by value, not by handle.
    $win->set_title(sprintf(
        'php-gtk4 · GdkRectangle — equal(clone) = %s',
        $a->equal(clone $a) ? 'true' : 'false',
    ));

    return $area;
}, 440, 340);
