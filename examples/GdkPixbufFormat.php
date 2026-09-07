<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkPixbufFormat - A GdkPixbufFormat contains information about the image format accepted by a…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GdkPixbufFormat doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkPixbufFormat
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkPixbufFormat',
    'A GdkPixbufFormat contains information about the image format accepted by a…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'copy',
            'free',
            'get_description',
            'get_extensions',
            'get_license',
            'get_mime_types',
            'get_name',
            'is_disabled',
            'is_save_option_supported',
            'is_scalable',
            'is_writable',
            'set_disabled',
        ]);
        $label->set_markup("<b>GdkPixbufFormat</b>\n12 generated methods\n<small>$names</small>");
        return $label;
    },
);
