<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkLayoutChild - GtkLayoutChild is the base class for objects that are meant to hold layout…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkLayoutChild doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkLayoutChild
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkLayoutChild',
    'GtkLayoutChild is the base class for objects that are meant to hold layout…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_child_widget',
            'get_layout_manager',
        ]);
        $label->set_markup("<b>GtkLayoutChild</b>\n2 generated methods\n<small>$names</small>");
        return $label;
    },
);
