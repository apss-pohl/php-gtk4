<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkMonitor - GdkMonitor objects represent the individual outputs that are associated with…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GdkMonitor doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkMonitor
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkMonitor',
    'GdkMonitor objects represent the individual outputs that are associated with…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_connector',
            'get_description',
            'get_display',
            'get_geometry',
            'get_height_mm',
            'get_manufacturer',
            'get_model',
            'get_refresh_rate',
            'get_scale',
            'get_scale_factor',
            'get_subpixel_layout',
            'get_width_mm',
            '…',
        ]);
        $label->set_markup("<b>GdkMonitor</b>\n13 generated methods\n<small>$names</small>");
        return $label;
    },
);
