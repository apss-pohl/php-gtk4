<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskColorMatrixNode - A render node controlling the color matrix of its single child node.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskColorMatrixNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskColorMatrixNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskColorMatrixNode',
    'A render node controlling the color matrix of its single child node.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_child',
            'get_color_matrix',
            'get_color_offset',
        ]);
        $label->set_markup("<b>GskColorMatrixNode</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
