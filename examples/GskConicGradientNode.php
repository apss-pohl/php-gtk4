<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskConicGradientNode - A render node for a conic gradient.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskConicGradientNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskConicGradientNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskConicGradientNode',
    'A render node for a conic gradient.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_angle',
            'get_center',
            'get_color_stops',
            'get_n_color_stops',
            'get_rotation',
        ]);
        $label->set_markup("<b>GskConicGradientNode</b>\n6 generated methods\n<small>$names</small>");
        return $label;
    },
);
