<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkBox;
use Gtk4\GtkOrientation;

/** Constructor arguments of the native class become construct properties of the subtype. */
final class VBox extends GtkBox
{
    public function __construct(public string $tag = 'vbox')
    {
        parent::__construct(GtkOrientation::Vertical, 7);
    }
}
