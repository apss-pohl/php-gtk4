<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkNumberUpLayout;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkNumberUpLayout - Used to determine the layout of pages on a sheet when printing multiple pages…
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the enum's cases. Replace it with a
 * page that shows the enum in action and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkNumberUpLayout
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkNumberUpLayout',
    'Used to determine the layout of pages on a sheet when printing multiple pages…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $rows = array_map(
            static fn(\BackedEnum $c): string => sprintf('%s = %d', $c->name, $c->value),
            GtkNumberUpLayout::cases(),
        );
        $label->set_markup("<b>GtkNumberUpLayout</b>\n" . implode("\n", $rows));
        return $label;
    },
);
