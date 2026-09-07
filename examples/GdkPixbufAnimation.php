<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkPixbufAnimation - An opaque object representing an animation.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GdkPixbufAnimation doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkPixbufAnimation
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkPixbufAnimation',
    'An opaque object representing an animation.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_height',
            'get_iter',
            'get_static_image',
            'get_width',
            'is_static_image',
            'new_from_file',
            'new_from_resource',
            'new_from_stream',
            'new_from_stream_async',
            'new_from_stream_finish',
        ]);
        $label->set_markup("<b>GdkPixbufAnimation</b>\n10 generated methods\n<small>$names</small>");
        return $label;
    },
);
