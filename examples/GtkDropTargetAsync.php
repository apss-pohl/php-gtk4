<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkDropTargetAsync - GtkDropTargetAsync is an event controller to receive Drag-and-Drop…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkDropTargetAsync doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkDropTargetAsync
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkDropTargetAsync',
    'GtkDropTargetAsync is an event controller to receive Drag-and-Drop…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_actions',
            'get_formats',
            'reject_drop',
            'set_actions',
            'set_formats',
        ]);
        $label->set_markup("<b>GtkDropTargetAsync</b>\n6 generated methods\n<small>$names</small>");
        return $label;
    },
);
