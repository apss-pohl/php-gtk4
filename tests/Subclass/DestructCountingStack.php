<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Subclass;

use Gtk4\GtkStack;

/** WrapTest: observes when a stack held only by something it handed out is freed. */
final class DestructCountingStack extends GtkStack
{
    public static int $destructed = 0;

    /** WrapTest: somewhere for a subclass to store what the stack handed it (an owner cycle). */
    public mixed $kept = null;

    public function __destruct()
    {
        self::$destructed++;
    }
}
