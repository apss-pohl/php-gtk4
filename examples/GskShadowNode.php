<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskShadowNode - A render node drawing one or more shadows behind its single child node.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskShadowNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskShadowNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskShadowNode',
    'A render node drawing one or more shadows behind its single child node.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_child',
            'get_n_shadows',
            'get_shadow',
        ]);
        $label->set_markup("<b>GskShadowNode</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
