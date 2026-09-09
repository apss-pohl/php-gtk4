<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskLinearGradientNode - A render node for a linear gradient.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskLinearGradientNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskLinearGradientNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskLinearGradientNode',
    'A render node for a linear gradient.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_color_stops',
            'get_end',
            'get_n_color_stops',
            'get_start',
        ]);
        $label->set_markup("<b>GskLinearGradientNode</b>\n5 generated methods\n<small>$names</small>");
        return $label;
    },
);
