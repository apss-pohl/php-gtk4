<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkApplicationInhibitFlags;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkApplicationInhibitFlags - what an application may ask the session not to do: log out,
 * switch user, suspend, idle.
 *
 * GFlags stay ints with the values as constants. GtkApplication::inhibit() itself is
 * not bound (it crashes inside GTK without a registered application and a realized
 * window - see gen/skip.txt), so this page shows the flag values and how they combine.
 *
 *   bin/php-gtk4 examples/demo.php GtkApplicationInhibitFlags
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkApplicationInhibitFlags',
    'the session-inhibit flag values and how they combine',
    function (GtkWindow $win): GtkWidget {
        $flags = array_filter(new \ReflectionClass(GtkApplicationInhibitFlags::class)->getConstants(), 'is_int');
        $rows = [];
        foreach ($flags as $name => $bit) {
            $rows[] = sprintf('GtkApplicationInhibitFlags::%s = %d (bit %d)', $name, $bit, (int) log($bit, 2));
        }
        $rows[] = sprintf("\nall of them ORed: <b>%d</b> = %s", array_sum($flags), implode(' | ', array_keys($flags)));
        Demo::status('inhibit() is not bound; the flags are plain ints');
        return Demo::label(implode("\n", $rows));
    },
    480,
    340,
);
