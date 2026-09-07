<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskTextureScaleNode - A render node for a GdkTexture.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskTextureScaleNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskTextureScaleNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskTextureScaleNode',
    'A render node for a GdkTexture.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_filter',
            'get_texture',
        ]);
        $label->set_markup("<b>GskTextureScaleNode</b>\n3 generated methods\n<small>$names</small>");
        return $label;
    },
);
