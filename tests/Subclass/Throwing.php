<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;

/** A vfunc that throws: routed through the exception boundary, GTK gets the fallback. */
final class Throwing extends GtkWidget
{
    public function vfunc_measure(GtkOrientation $orientation, int $for_size): array
    {
        throw new \RuntimeException('measure failed');
    }
}
