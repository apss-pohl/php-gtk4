<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkOrientable;
use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;

/**
 * A widget implementing GtkOrientable from PHP. The interface has no slots, only the
 * `orientation` property, which GObject routes to the two accessors; `$orientation` itself is
 * a plain PHP property. Drives tests/InterfacePropertyTest.php.
 */
final class PhpOrientable extends GtkWidget implements GtkOrientable
{
    /** @var list<string> accessor calls in order */
    public array $calls = [];

    public GtkOrientation $orientation = GtkOrientation::Horizontal;

    public function get_orientation(): GtkOrientation
    {
        $this->calls[] = 'get_orientation';

        return $this->orientation;
    }

    public function set_orientation(GtkOrientation $orientation): void
    {
        $this->calls[] = 'set_orientation';
        $this->orientation = $orientation;
    }
}
