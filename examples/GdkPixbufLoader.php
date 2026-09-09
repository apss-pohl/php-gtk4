<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkPixbufLoader - Incremental image loader.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GdkPixbufLoader doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkPixbufLoader
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkPixbufLoader',
    'Incremental image loader.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'close',
            'get_animation',
            'get_format',
            'get_pixbuf',
            'new_with_mime_type',
            'new_with_type',
            'set_size',
            'write',
            'write_bytes',
        ]);
        $label->set_markup("<b>GdkPixbufLoader</b>\n10 generated methods\n<small>$names</small>");
        return $label;
    },
);
