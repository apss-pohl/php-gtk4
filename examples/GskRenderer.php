<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskRenderer - GskRenderer is a class that renders a scene graph defined via a tree of…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskRenderer doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskRenderer
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskRenderer',
    'GskRenderer is a class that renders a scene graph defined via a tree of…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_surface',
            'is_realized',
            'new_for_surface',
            'realize',
            'realize_for_display',
            'render',
            'render_texture',
            'unrealize',
        ]);
        $label->set_markup("<b>GskRenderer</b>\n8 generated methods\n<small>$names</small>");
        return $label;
    },
);
