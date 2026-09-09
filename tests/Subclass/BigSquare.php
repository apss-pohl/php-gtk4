<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkOrientation;

/** Two PHP levels: chains to Square through parent::vfunc_measure(). */
final class BigSquare extends Square
{
    public function vfunc_measure(GtkOrientation $orientation, int $for_size): array
    {
        [$min, $nat, $mb, $nb] = parent::vfunc_measure($orientation, $for_size);

        return [$min + 100, $nat + 100, $mb, $nb];
    }
}
