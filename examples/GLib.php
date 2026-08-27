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
 * Gtk4\GLib - idle and timeout sources on the main context.
 *
 * A callback returning true keeps its source alive, false removes it. The bar is
 * advanced by a timeout, the label under it was filled in by a one-shot idle, and
 * the timeout removes itself with source_remove() when the bar is full.
 *
 *   bin/php-gtk4 examples/demo.php GLib
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GLib',
    'idle and timeout sources on the main context',
    function (GtkWindow $win): GtkWidget {
        // Shared mutable state. An object rather than by-ref captures, because the
        // draw func is written before the callbacks that change it.
        $state = new class {
            public float $progress = 0.0;
            public string $idleNote = 'idle_add() has not run yet';
            public int $ticks = 0;
            public int $source_id = 0;
            public bool $removed = false;
            public int $pumped = -1;
        };

        $area = Demo::canvas(420, 200, static function (
            GtkDrawingArea $self,
            CairoContext $cr,
            int $width,
            int $height,
        ) use ($state): void {
            Demo::sheet($cr);
            Demo::text($cr, 18, 30, 'GLib::timeout_add(40, ...)', Demo::INK, 14);

            $cr->set_source_color(new GdkRGBA('#e6e6e6'));
            $cr->rectangle(18, 46, $width - 36, 26);
            $cr->fill();
            $cr->set_source_color(new GdkRGBA($state->removed ? Demo::GOOD : Demo::ACCENT));
            $cr->rectangle(18, 46, ($width - 36) * $state->progress, 26);
            $cr->fill();

            Demo::text(
                $cr,
                18,
                96,
                sprintf('%d tick(s), source id %d', $state->ticks, $state->source_id),
                Demo::MUTED,
                12,
            );
            Demo::text(
                $cr,
                18,
                122,
                $state->removed
                ? 'source_remove() called - the bar is frozen'
                : 'returning true keeps the source alive',
                $state->removed ? Demo::GOOD : Demo::MUTED,
                12,
            );
            Demo::text($cr, 18, 156, $state->idleNote, Demo::INK, 12);
            if ($state->pumped >= 0) {
                Demo::text(
                    $cr,
                    18,
                    180,
                    sprintf('main_context_iteration() pumped %d pending source(s) synchronously', $state->pumped),
                    Demo::MUTED,
                    12,
                );
            }
        });

        // Runs once, as soon as the loop has nothing else to do.
        GLib::idle_add(function () use ($state, $area): bool {
            $state->idleNote = 'idle_add() ran once and returned false, so it is gone';
            $area->queue_draw();
            return false;
        });

        $state->source_id = GLib::timeout_add(40, function () use ($state, $area): bool {
            $state->ticks++;
            $state->progress = min(1.0, $state->progress + 0.01);
            $area->queue_draw();
            return true;        // true = call me again
        });

        // Removing a source from outside it - the counterpart to returning false.
        GLib::timeout_add(4200, function () use ($state, $area): bool {
            $state->removed = GLib::source_remove($state->source_id);   // false if it was already gone
            $area->queue_draw();
            // Pump whatever is ready right now without returning to run() - the redraw
            // queued above is dispatched before this callback even returns.
            $state->pumped = 0;
            for ($i = 0; $i < 10 && GLib::main_context_iteration(false); $i++) {
                $state->pumped++;
            }
            return false;
        });

        return $area;
    },
    440,
    240,
);
