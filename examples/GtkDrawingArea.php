<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GdkRGBA;
use Gtk4\GLib;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkDrawingArea - a widget that paints through a PHP callback.
 *
 * The draw func is called with the context and the widget's current size; a
 * timeout bumps a frame counter and calls queue_draw(), so the clock hand sweeps
 * and the content size is re-reported on every frame.
 *
 *   bin/php-gtk4 examples/demo.php GtkDrawingArea
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkDrawingArea',
    'a widget that paints through a PHP callback',
    function (GtkWindow $win): GtkWidget {
        $area = new GtkDrawingArea();
        $area->set_content_width(360);      // the size the widget asks for
        $area->set_content_height(260);

        $frame = 0;
        $area->set_draw_func(function (
            GtkDrawingArea $self,
            CairoContext $cr,
            int $width,
            int $height,
        ) use (&$frame): void {
            Demo::sheet($cr);

            $cx = $width / 2;
            $cy = $height / 2 - 6;
            $radius = min($width, $height) / 3;

            $cr->set_source_color(new GdkRGBA(Demo::ACCENT));
            $cr->set_line_width(4);
            $cr->arc($cx, $cy, $radius, 0, 2 * M_PI);
            $cr->stroke();

            // One sweep every 60 frames.
            $angle = $frame / 60 * 2 * M_PI - M_PI / 2;
            $cr->set_source_color(new GdkRGBA(Demo::WARN));
            $cr->set_line_width(3);
            $cr->move_to($cx, $cy);
            $cr->line_to($cx + cos($angle) * $radius * 0.85, $cy + sin($angle) * $radius * 0.85);
            $cr->stroke();

            Demo::text(
                $cr,
                12,
                $height - 12,
                sprintf(
                    'frame %d · allocation %d x %d · content %d x %d',
                    $frame,
                    $width,
                    $height,
                    $self->get_content_width(),
                    $self->get_content_height(),
                ),
                Demo::MUTED,
                12,
            );
        });

        // queue_draw() is what turns a static drawing into an animation.
        GLib::timeout_add(33, function () use ($area, &$frame): bool {
            $frame++;
            $area->queue_draw();
            return true;
        });

        return $area;
    },
    400,
    300,
);
