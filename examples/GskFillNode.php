<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskFillNode - A render node filling the area given by Path and FillRule with the child node.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskFillNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskFillNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskFillNode',
    'A render node filling the area given by Path and FillRule with the child node.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_child',
            'get_fill_rule',
            'get_path',
        ]);
        $label->set_markup("<b>GskFillNode</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
