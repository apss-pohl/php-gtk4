<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GdkRGBA;
use Gtk4\GrapheneRect;
use Gtk4\GtkOrientation;
use Gtk4\GtkSnapshot;
use Gtk4\GtkWidget;

/**
 * A widget that paints itself, written in PHP: `vfunc_measure()` asks for a size and
 * `vfunc_snapshot()` fills the snapshot GTK hands it - a GSK colour node first, then the same
 * snapshot through `append_cairo()`, which is what lets an existing CairoContext routine draw
 * into a real widget rather than a GtkDrawingArea.
 */
final class PaintedWidget extends GtkWidget
{
    public int $snapshots = 0;

    /** The allocation the last snapshot saw. */
    public int $lastWidth = 0;

    /** True once the cairo half of a snapshot has run to completion. */
    public bool $cairoDrawn = false;

    /** @return array{int, int, int, int} */
    public function vfunc_measure(GtkOrientation $orientation, int $for_size): array
    {
        return [80, 80, -1, -1];
    }

    public function vfunc_snapshot(GtkSnapshot $snapshot): void
    {
        $this->snapshots++;
        $this->lastWidth = $this->get_width();

        $bounds = GrapheneRect::alloc()->init(0.0, 0.0, (float) $this->lastWidth, 40.0);
        $snapshot->append_color(new GdkRGBA('#3584e4'), $bounds);

        $cr = $snapshot->append_cairo($bounds);
        $cr->set_source_rgb(1.0, 1.0, 1.0);
        $cr->rectangle(0, 0, 10, 10);
        $cr->fill();
        $this->cairoDrawn = true;
    }
}
