<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkButton;

/** WrapTest: a PHP subclass carrying state GTK knows nothing about. */
final class StatefulButton extends GtkButton
{
    public int $state = 42;
}
