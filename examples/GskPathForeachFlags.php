<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GskPathForeachFlags;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskPathForeachFlags - Flags that can be passed to gsk_path_foreach() to influence what kinds of…
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the bitfield's constants. Replace it with a
 * page that shows the bitfield in action and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskPathForeachFlags
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskPathForeachFlags',
    'Flags that can be passed to gsk_path_foreach() to influence what kinds of…',
    function (GtkWindow $win): GtkWidget {
        $constants = array_filter(new \ReflectionClass(GskPathForeachFlags::class)->getConstants(), 'is_int');
        $label = new GtkLabel();
        $rows = array_map(
            static fn(string $k, int $v): string => sprintf('%s = %d', $k, $v),
            array_keys($constants),
            $constants,
        );
        $label->set_markup("<b>GskPathForeachFlags</b>\n" . implode("\n", $rows));
        return $label;
    },
);
