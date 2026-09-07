<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskTextNode - A render node drawing a set of glyphs.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskTextNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskTextNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskTextNode',
    'A render node drawing a set of glyphs.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_color',
            'get_font',
            'get_glyphs',
            'get_num_glyphs',
            'get_offset',
            'has_color_glyphs',
        ]);
        $label->set_markup("<b>GskTextNode</b>\n7 generated methods\n<small>$names</small>");
        return $label;
    },
);
