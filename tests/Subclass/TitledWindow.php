<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkWindow;

/** A GtkRoot subtype: GTK's toplevel list owns the initial reference (ownership rule). */
final class TitledWindow extends GtkWindow
{
    public function __construct()
    {
        parent::__construct();
        $this->set_title('titled');
    }
}
