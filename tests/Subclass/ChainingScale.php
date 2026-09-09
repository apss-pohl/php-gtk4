<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkScale;

/**
 * ArgumentGuardTest: `parent::vfunc_change_value()` is the only way to reach the argument check
 * on `GtkScrollType`, an enum GTK never bound as a PHP enum, so it crosses as an int.
 */
final class ChainingScale extends GtkScale
{
    public function vfunc_change_value(int $scroll, float $new_value): bool
    {
        return parent::vfunc_change_value($scroll, $new_value);
    }
}
