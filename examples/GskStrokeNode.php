<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskStrokeNode - A render node that will fill the area determined by stroking the the given…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskStrokeNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskStrokeNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskStrokeNode',
    'A render node that will fill the area determined by stroking the the given…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_child',
            'get_path',
            'get_stroke',
        ]);
        $label->set_markup("<b>GskStrokeNode</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
