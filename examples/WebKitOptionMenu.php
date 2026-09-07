<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitOptionMenu - Represents the dropdown menu of a select element in a #WebKitWebView.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitOptionMenu doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitOptionMenu
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitOptionMenu',
    'Represents the dropdown menu of a select element in a #WebKitWebView.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'activate_item',
            'close',
            'get_event',
            'get_item',
            'get_n_items',
            'select_item',
        ]);
        $label->set_markup("<b>WebKitOptionMenu</b>\n6 generated methods\n<small>$names</small>");
        return $label;
    },
);
