<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskContainerNode - A render node that can contain other render nodes.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskContainerNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskContainerNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskContainerNode',
    'A render node that can contain other render nodes.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_child',
            'get_n_children',
        ]);
        $label->set_markup("<b>GskContainerNode</b>\n3 generated methods\n<small>$names</small>");
        return $label;
    },
);
