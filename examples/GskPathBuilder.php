<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskPathBuilder - GskPathBuilder is an auxiliary object for constructing GskPath objects.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskPathBuilder doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskPathBuilder
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskPathBuilder',
    'GskPathBuilder is an auxiliary object for constructing GskPath objects.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'add_cairo_path',
            'add_circle',
            'add_layout',
            'add_path',
            'add_rect',
            'add_reverse_path',
            'add_rounded_rect',
            'add_segment',
            'arc_to',
            'close',
            'conic_to',
            '…',
        ]);
        $label->set_markup("<b>GskPathBuilder</b>\n31 generated methods\n<small>$names</small>");
        return $label;
    },
);
