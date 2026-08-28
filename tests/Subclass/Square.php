<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkOrientation;
use Gtk4\GtkSizeRequestMode;
use Gtk4\GtkWidget;

/** A widget written in PHP: fixed 40x60 request, constant-size mode. */
class Square extends GtkWidget
{
    public int $measured = 0;

    public function vfunc_measure(GtkOrientation $orientation, int $for_size): array
    {
        $this->measured++;

        return $orientation === GtkOrientation::Horizontal ? [40, 40, -1, -1] : [60, 60, -1, -1];
    }

    public function vfunc_get_request_mode(): GtkSizeRequestMode
    {
        return GtkSizeRequestMode::ConstantSize;
    }
}
