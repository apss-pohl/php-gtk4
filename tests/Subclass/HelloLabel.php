<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkLabel;

/** gtk_label_new(str) sets "label": gen/ctor-props.txt maps it for the subtype path. */
final class HelloLabel extends GtkLabel
{
    public function shout(): string
    {
        return strtoupper($this->get_text());
    }
}
