<?php

declare(strict_types=1);

namespace PhpGtk4\Tests;

use Gtk4\GtkButton;

/** WrapTest: observes when a handle GTK held is freed. */
final class DestructCountingButton extends GtkButton
{
    public static int $destructed = 0;

    public function __destruct()
    {
        self::$destructed++;
    }
}
