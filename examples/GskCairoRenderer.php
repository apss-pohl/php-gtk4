<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskCairoRenderer - A GSK renderer that is using cairo.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskCairoRenderer doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskCairoRenderer
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskCairoRenderer',
    'A GSK renderer that is using cairo.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
        ]);
        $label->set_markup("<b>GskCairoRenderer</b>\n1 generated methods\n<small>$names</small>");
        return $label;
    },
);
