<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkBoxLayout - GtkBoxLayout is a layout manager that arranges children in a single row or…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkBoxLayout doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkBoxLayout
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkBoxLayout',
    'GtkBoxLayout is a layout manager that arranges children in a single row or…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_baseline_child',
            'get_baseline_position',
            'get_homogeneous',
            'get_spacing',
            'set_baseline_child',
            'set_baseline_position',
            'set_homogeneous',
            'set_spacing',
        ]);
        $label->set_markup("<b>GtkBoxLayout</b>\n9 generated methods\n<small>$names</small>");
        return $label;
    },
);
