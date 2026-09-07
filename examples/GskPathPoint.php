<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskPathPoint - GskPathPoint is an opaque type representing a point on a path.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskPathPoint doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskPathPoint
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskPathPoint',
    'GskPathPoint is an opaque type representing a point on a path.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'compare',
            'copy',
            'equal',
            'free',
            'get_curvature',
            'get_distance',
            'get_position',
            'get_rotation',
            'get_tangent',
        ]);
        $label->set_markup("<b>GskPathPoint</b>\n9 generated methods\n<small>$names</small>");
        return $label;
    },
);
