<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitOptionMenuItem - One item of a #WebKitOptionMenu.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitOptionMenuItem doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitOptionMenuItem
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitOptionMenuItem',
    'One item of a #WebKitOptionMenu.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'copy',
            'free',
            'get_label',
            'get_tooltip',
            'is_enabled',
            'is_group_child',
            'is_group_label',
            'is_selected',
        ]);
        $label->set_markup("<b>WebKitOptionMenuItem</b>\n8 generated methods\n<small>$names</small>");
        return $label;
    },
);
