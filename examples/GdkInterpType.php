<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GdkInterpType;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkInterpType - Interpolation modes for scaling functions.
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the enum's cases. Replace it with a
 * page that shows the enum in action and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkInterpType
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkInterpType',
    'Interpolation modes for scaling functions.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $rows = array_map(
            static fn(\BackedEnum $c): string => sprintf('%s = %d', $c->name, $c->value),
            GdkInterpType::cases(),
        );
        $label->set_markup("<b>GdkInterpType</b>\n" . implode("\n", $rows));
        return $label;
    },
);
