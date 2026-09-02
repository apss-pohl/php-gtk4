<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkCursor - GdkCursor is used to create and destroy cursors.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GdkCursor doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkCursor
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkCursor',
    'GdkCursor is used to create and destroy cursors.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_fallback',
            'get_hotspot_x',
            'get_hotspot_y',
            'get_name',
            'get_texture',
            'new_from_name',
            'new_from_texture',
        ]);
        $label->set_markup("<b>GdkCursor</b>\n7 generated methods\n<small>$names</small>");
        return $label;
    },
);
