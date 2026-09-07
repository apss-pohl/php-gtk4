<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskStroke - A GskStroke struct collects the parameters that influence the operation of…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskStroke doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskStroke
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskStroke',
    'A GskStroke struct collects the parameters that influence the operation of…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'copy',
            'free',
            'get_dash',
            'get_dash_offset',
            'get_line_cap',
            'get_line_join',
            'get_line_width',
            'get_miter_limit',
            'set_dash',
            'set_dash_offset',
            'set_line_cap',
            '…',
        ]);
        $label->set_markup("<b>GskStroke</b>\n16 generated methods\n<small>$names</small>");
        return $label;
    },
);
