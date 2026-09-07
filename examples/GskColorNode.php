<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskColorNode - A render node for a solid color.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskColorNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskColorNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskColorNode',
    'A render node for a solid color.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_color',
        ]);
        $label->set_markup("<b>GskColorNode</b>\n2 generated methods\n<small>$names</small>");
        return $label;
    },
);
