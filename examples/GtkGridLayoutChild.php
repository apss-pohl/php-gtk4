<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkGridLayoutChild - GtkLayoutChild subclass for children in a GtkGridLayout.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkGridLayoutChild doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkGridLayoutChild
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkGridLayoutChild',
    'GtkLayoutChild subclass for children in a GtkGridLayout.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_column',
            'get_column_span',
            'get_row',
            'get_row_span',
            'set_column',
            'set_column_span',
            'set_row',
            'set_row_span',
        ]);
        $label->set_markup("<b>GtkGridLayoutChild</b>\n8 generated methods\n<small>$names</small>");
        return $label;
    },
);
