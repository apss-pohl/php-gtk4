<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GObject;
use Gtk4\GtkFilter;
use Gtk4\GtkFilterMatch;
use Gtk4\PhpValue;

/** A GtkFilter implemented in PHP: keeps even PhpValue payloads. */
final class EvenFilter extends GtkFilter
{
    public int $calls = 0;

    public function vfunc_match(?GObject $item): bool
    {
        $this->calls++;

        return $item instanceof PhpValue && is_int($item->get_value()) && $item->get_value() % 2 === 0;
    }

    public function vfunc_get_strictness(): GtkFilterMatch
    {
        return GtkFilterMatch::Some;
    }
}
