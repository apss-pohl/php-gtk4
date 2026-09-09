<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkGridLayout - GtkGridLayout is a layout manager which arranges child widgets in rows and…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkGridLayout doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkGridLayout
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkGridLayout',
    'GtkGridLayout is a layout manager which arranges child widgets in rows and…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_baseline_row',
            'get_column_homogeneous',
            'get_column_spacing',
            'get_row_baseline_position',
            'get_row_homogeneous',
            'get_row_spacing',
            'set_baseline_row',
            'set_column_homogeneous',
            'set_column_spacing',
            'set_row_baseline_position',
            'set_row_homogeneous',
            '…',
        ]);
        $label->set_markup("<b>GtkGridLayout</b>\n13 generated methods\n<small>$names</small>");
        return $label;
    },
);
