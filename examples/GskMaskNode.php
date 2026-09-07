<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskMaskNode - A render node masking one child node with another.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskMaskNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskMaskNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskMaskNode',
    'A render node masking one child node with another.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_mask',
            'get_mask_mode',
            'get_source',
        ]);
        $label->set_markup("<b>GskMaskNode</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
