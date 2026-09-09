<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskRadialGradientNode - A render node for a radial gradient.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskRadialGradientNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskRadialGradientNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskRadialGradientNode',
    'A render node for a radial gradient.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_center',
            'get_color_stops',
            'get_end',
            'get_hradius',
            'get_n_color_stops',
            'get_start',
            'get_vradius',
        ]);
        $label->set_markup("<b>GskRadialGradientNode</b>\n8 generated methods\n<small>$names</small>");
        return $label;
    },
);
