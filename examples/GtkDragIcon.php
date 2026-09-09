<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkDragIcon - GtkDragIcon is a GtkRoot implementation for drag icons.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkDragIcon doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkDragIcon
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkDragIcon',
    'GtkDragIcon is a GtkRoot implementation for drag icons.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'create_widget_for_value',
            'get_child',
            'get_for_drag',
            'set_child',
            'set_from_paintable',
        ]);
        $label->set_markup("<b>GtkDragIcon</b>\n5 generated methods\n<small>$names</small>");
        return $label;
    },
);
