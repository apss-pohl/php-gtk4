<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkIconTheme - GtkIconTheme provides a facility for loading themed icons.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkIconTheme doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkIconTheme
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkIconTheme',
    'GtkIconTheme provides a facility for loading themed icons.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'add_resource_path',
            'add_search_path',
            'get_display',
            'get_for_display',
            'get_icon_names',
            'get_icon_sizes',
            'get_resource_path',
            'get_search_path',
            'get_theme_name',
            'has_gicon',
            'has_icon',
            '…',
        ]);
        $label->set_markup("<b>GtkIconTheme</b>\n17 generated methods\n<small>$names</small>");
        return $label;
    },
);
