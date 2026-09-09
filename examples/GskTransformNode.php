<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskTransformNode - A render node applying a GskTransform to its single child node.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskTransformNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskTransformNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskTransformNode',
    'A render node applying a GskTransform to its single child node.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_child',
            'get_transform',
        ]);
        $label->set_markup("<b>GskTransformNode</b>\n3 generated methods\n<small>$names</small>");
        return $label;
    },
);
