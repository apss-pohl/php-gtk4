<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GLib;
use Gtk4\GdkRGBA;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkOrientation - Horizontal or Vertical, the two-case GEnum.
 *
 * Nothing bound yet takes one as an argument, so this draws what the two cases
 * mean instead: the same run of bars laid out along each axis, swapping every
 * couple of seconds.
 *
 *   bin/php-gtk4 examples/GtkOrientation.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GtkOrientation', function (GtkWindow $win): GtkWidget {
    $current = GtkOrientation::Horizontal;

    $area = Demo::canvas(420, 260, static function (
        GtkDrawingArea $self,
        CairoContext $cr,
        int $width,
        int $height,
    ) use (&$current): void {
        Demo::sheet($cr);

        $lengths = [40, 70, 100, 130, 160];
        foreach ($lengths as $index => $length) {
            $cr->set_source_color(new GdkRGBA(Demo::ACCENT));
            if ($current === GtkOrientation::Horizontal) {
                $cr->rectangle(24, 60 + $index * 30, $length, 18);
            } else {
                $cr->rectangle(24 + $index * 40, 220 - $length, 26, $length);
            }
            $cr->fill();
        }

        Demo::text($cr, 24, 34, sprintf('GtkOrientation::%s (value %d)', $current->name, $current->value), Demo::INK, 16);
        Demo::text(
            $cr,
            24,
            250,
            implode('  ·  ', array_map(
                static fn(GtkOrientation $o): string => sprintf('%s = %d', $o->name, $o->value),
                GtkOrientation::cases(),
            )),
            Demo::MUTED,
            12,
        );
    });

    GLib::timeout_add(1800, function () use ($area, &$current): bool {
        $current = $current === GtkOrientation::Horizontal
            ? GtkOrientation::Vertical
            : GtkOrientation::Horizontal;
        $area->queue_draw();
        return true;
    });

    return $area;
}, 440, 300);
