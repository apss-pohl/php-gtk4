<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\CairoContext;
use Gtk4\GLib;
use Gtk4\GdkRGBA;
use Gtk4\GtkDrawingArea;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GLib - idle and timeout sources on the main context.
 *
 * A callback returning true keeps its source alive, false removes it. The bar is
 * advanced by a timeout, the label under it was filled in by a one-shot idle, and
 * the timeout removes itself with source_remove() when the bar is full.
 *
 *   bin/php-gtk4 examples/GLib.php
 */

require __DIR__ . '/bootstrap.php';

Demo::run('GLib', function (GtkWindow $win): GtkWidget {
    $progress = 0.0;
    $idleNote = 'idle_add() has not run yet';
    $ticks = 0;
    $sourceId = 0;
    $removed = false;

    $area = Demo::canvas(420, 200, static function (
        GtkDrawingArea $self,
        CairoContext $cr,
        int $width,
        int $height,
    ) use (&$progress, &$idleNote, &$ticks, &$sourceId, &$removed): void {
        Demo::sheet($cr);
        Demo::text($cr, 18, 30, 'GLib::timeout_add(40, ...)', Demo::INK, 14);

        $cr->set_source_color(new GdkRGBA('#e6e6e6'));
        $cr->rectangle(18, 46, $width - 36, 26);
        $cr->fill();
        $cr->set_source_color(new GdkRGBA($removed ? Demo::GOOD : Demo::ACCENT));
        $cr->rectangle(18, 46, ($width - 36) * $progress, 26);
        $cr->fill();

        Demo::text($cr, 18, 96, sprintf('%d tick(s), source id %d', $ticks, $sourceId), Demo::MUTED, 12);
        Demo::text(
            $cr,
            18,
            122,
            $removed ? 'source_remove() called - the bar is frozen' : 'returning true keeps the source alive',
            $removed ? Demo::GOOD : Demo::MUTED,
            12,
        );
        Demo::text($cr, 18, 156, $idleNote, Demo::INK, 12);
    });

    // Runs once, as soon as the loop has nothing else to do.
    GLib::idle_add(function () use (&$idleNote, $area): bool {
        $idleNote = 'idle_add() ran once and returned false, so it is gone';
        $area->queue_draw();
        return false;
    });

    $sourceId = GLib::timeout_add(40, function () use (&$progress, &$ticks, $area): bool {
        $ticks++;
        $progress = min(1.0, $progress + 0.01);
        $area->queue_draw();
        return true;        // true = call me again
    });

    // Removing a source from outside it - the counterpart to returning false.
    GLib::timeout_add(4200, function () use (&$sourceId, &$removed, $area): bool {
        $removed = GLib::source_remove($sourceId);   // false if it was already gone
        $area->queue_draw();
        return false;
    });

    return $area;
}, 440, 240);
