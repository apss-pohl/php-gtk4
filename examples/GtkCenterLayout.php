<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkCenterLayout - GtkCenterLayout is a layout manager that manages up to three children.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkCenterLayout doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkCenterLayout
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkCenterLayout',
    'GtkCenterLayout is a layout manager that manages up to three children.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_baseline_position',
            'get_center_widget',
            'get_end_widget',
            'get_orientation',
            'get_shrink_center_last',
            'get_start_widget',
            'set_baseline_position',
            'set_center_widget',
            'set_end_widget',
            'set_orientation',
            'set_shrink_center_last',
            '…',
        ]);
        $label->set_markup("<b>GtkCenterLayout</b>\n13 generated methods\n<small>$names</small>");
        return $label;
    },
);
