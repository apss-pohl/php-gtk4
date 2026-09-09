<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;

/** parent::vfunc_measure() from the first PHP level reaches GTK's own implementation. */
final class NativeChain extends GtkWidget
{
    /** @var list<int> */
    public array $native = [];

    public function vfunc_measure(GtkOrientation $orientation, int $for_size): array
    {
        $this->native = parent::vfunc_measure($orientation, $for_size);

        return [7, 7, -1, -1];
    }
}
