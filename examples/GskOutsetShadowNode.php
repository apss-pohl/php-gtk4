<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskOutsetShadowNode - A render node for an outset shadow.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskOutsetShadowNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskOutsetShadowNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskOutsetShadowNode',
    'A render node for an outset shadow.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_blur_radius',
            'get_color',
            'get_dx',
            'get_dy',
            'get_outline',
            'get_spread',
        ]);
        $label->set_markup("<b>GskOutsetShadowNode</b>\n7 generated methods\n<small>$names</small>");
        return $label;
    },
);
