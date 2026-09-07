<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPrintContext - A GtkPrintContext encapsulates context information that is required when…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkPrintContext doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkPrintContext
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPrintContext',
    'A GtkPrintContext encapsulates context information that is required when…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'create_pango_context',
            'create_pango_layout',
            'get_cairo_context',
            'get_dpi_x',
            'get_dpi_y',
            'get_hard_margins',
            'get_height',
            'get_page_setup',
            'get_pango_fontmap',
            'get_width',
            'set_cairo_context',
        ]);
        $label->set_markup("<b>GtkPrintContext</b>\n11 generated methods\n<small>$names</small>");
        return $label;
    },
);
