<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskTransform - GskTransform is an object to describe transform matrices.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskTransform doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskTransform
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskTransform',
    'GskTransform is an object to describe transform matrices.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'equal',
            'get_category',
            'invert',
            'matrix',
            'parse',
            'perspective',
            'print',
            'ref',
            'rotate',
            'rotate_3d',
            'scale',
            '…',
        ]);
        $label->set_markup("<b>GskTransform</b>\n26 generated methods\n<small>$names</small>");
        return $label;
    },
);
