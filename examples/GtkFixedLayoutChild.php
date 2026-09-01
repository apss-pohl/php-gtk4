<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkFixedLayoutChild - GtkLayoutChild subclass for children in a GtkFixedLayout.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkFixedLayoutChild doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkFixedLayoutChild
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkFixedLayoutChild',
    'GtkLayoutChild subclass for children in a GtkFixedLayout.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_transform',
            'set_transform',
        ]);
        $label->set_markup("<b>GtkFixedLayoutChild</b>\n2 generated methods\n<small>$names</small>");
        return $label;
    },
);
