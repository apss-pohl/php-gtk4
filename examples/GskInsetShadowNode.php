<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskInsetShadowNode - A render node for an inset shadow.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskInsetShadowNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskInsetShadowNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskInsetShadowNode',
    'A render node for an inset shadow.',
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
        $label->set_markup("<b>GskInsetShadowNode</b>\n7 generated methods\n<small>$names</small>");
        return $label;
    },
);
