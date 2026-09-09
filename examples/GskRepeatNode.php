<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskRepeatNode - A render node repeating its single child node.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskRepeatNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskRepeatNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskRepeatNode',
    'A render node repeating its single child node.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_child',
            'get_child_bounds',
        ]);
        $label->set_markup("<b>GskRepeatNode</b>\n3 generated methods\n<small>$names</small>");
        return $label;
    },
);
