<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkDropTarget - GtkDropTarget is an event controller to receive Drag-and-Drop operations.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkDropTarget doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkDropTarget
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkDropTarget',
    'GtkDropTarget is an event controller to receive Drag-and-Drop operations.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_actions',
            'get_current_drop',
            'get_formats',
            'get_gtypes',
            'get_preload',
            'get_value',
            'reject',
            'set_actions',
            'set_gtypes',
            'set_preload',
        ]);
        $label->set_markup("<b>GtkDropTarget</b>\n11 generated methods\n<small>$names</small>");
        return $label;
    },
);
