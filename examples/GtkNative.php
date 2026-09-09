<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkNative - GtkNative is the interface implemented by all widgets that have their own…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkNative doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkNative
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkNative',
    'GtkNative is the interface implemented by all widgets that have their own…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_for_surface',
            'get_renderer',
            'get_surface',
            'get_surface_transform',
            'realize',
            'unrealize',
        ]);
        $label->set_markup("<b>GtkNative</b>\n6 generated methods\n<small>$names</small>");
        return $label;
    },
);
