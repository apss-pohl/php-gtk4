<?php

declare(strict_types=1);

namespace PhpGtk4\Tests\Scripts;

use Gtk4\GtkOrientation;
use Gtk4\GtkWidget;

/** stress.php: a PHP GType - measure reports 40 + round % 3; every third round it throws (Log mode). */
final class StressSquare extends GtkWidget
{
    public static int $round = 0;

    public function vfunc_measure(GtkOrientation $orientation, int $for_size): array
    {
        if (self::$round % 3 === 2) {
            throw new \RuntimeException('expected');
        }

        return [40 + self::$round % 3, 40 + self::$round % 3, -1, -1];
    }
}
