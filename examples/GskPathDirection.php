<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GskPathDirection;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskPathDirection - The values of the GskPathDirection enum are used to pick one of the four…
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the enum's cases. Replace it with a
 * page that shows the enum in action and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskPathDirection
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskPathDirection',
    'The values of the GskPathDirection enum are used to pick one of the four…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $rows = array_map(
            static fn(\BackedEnum $c): string => sprintf('%s = %d', $c->name, $c->value),
            GskPathDirection::cases(),
        );
        $label->set_markup("<b>GskPathDirection</b>\n" . implode("\n", $rows));
        return $label;
    },
);
