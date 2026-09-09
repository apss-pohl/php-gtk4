<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkIconPaintable - Contains information found when looking up an icon in GtkIconTheme.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkIconPaintable doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkIconPaintable
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkIconPaintable',
    'Contains information found when looking up an icon in GtkIconTheme.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_file',
            'get_icon_name',
            'is_symbolic',
            'new_for_file',
        ]);
        $label->set_markup("<b>GtkIconPaintable</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
