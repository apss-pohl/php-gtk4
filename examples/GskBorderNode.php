<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskBorderNode - A render node for a border.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskBorderNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskBorderNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskBorderNode',
    'A render node for a border.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_colors',
            'get_outline',
            'get_widths',
        ]);
        $label->set_markup("<b>GskBorderNode</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
