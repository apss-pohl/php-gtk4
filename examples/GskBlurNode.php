<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskBlurNode - A render node applying a blur effect to its single child.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskBlurNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskBlurNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskBlurNode',
    'A render node applying a blur effect to its single child.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_child',
            'get_radius',
        ]);
        $label->set_markup("<b>GskBlurNode</b>\n3 generated methods\n<small>$names</small>");
        return $label;
    },
);
