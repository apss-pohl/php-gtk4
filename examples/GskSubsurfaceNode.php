<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskSubsurfaceNode - A render node that potentially diverts a part of the scene graph to a…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskSubsurfaceNode doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskSubsurfaceNode
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskSubsurfaceNode',
    'A render node that potentially diverts a part of the scene graph to a…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_child',
            'get_subsurface',
        ]);
        $label->set_markup("<b>GskSubsurfaceNode</b>\n2 generated methods\n<small>$names</small>");
        return $label;
    },
);
