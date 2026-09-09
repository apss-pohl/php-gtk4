<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkSurface - A GdkSurface is a rectangular region on the screen.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GdkSurface doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkSurface
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkSurface',
    'A GdkSurface is a rectangular region on the screen.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'beep',
            'create_cairo_context',
            'create_gl_context',
            'destroy',
            'get_cursor',
            'get_device_cursor',
            'get_device_position',
            'get_display',
            'get_frame_clock',
            'get_height',
            'get_mapped',
            'get_scale',
            '…',
        ]);
        $label->set_markup("<b>GdkSurface</b>\n25 generated methods\n<small>$names</small>");
        return $label;
    },
);
