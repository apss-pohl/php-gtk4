<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskCairoNode - A render node for a Cairo surface.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskCairoNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskCairoNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskCairoNode',
    'A render node for a Cairo surface.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_draw_context',
            'get_surface',
        ]);
        $label->set_markup("<b>GskCairoNode</b>\n3 generated methods\n<small>$names</small>");
        return $label;
    },
);
