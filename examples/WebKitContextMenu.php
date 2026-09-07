<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitContextMenu - Represents the context menu in a #WebKitWebView.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitContextMenu doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitContextMenu
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitContextMenu',
    'Represents the context menu in a #WebKitWebView.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'append',
            'first',
            'get_event',
            'get_item_at_position',
            'get_items',
            'get_n_items',
            'get_position',
            'get_user_data',
            'insert',
            'last',
            'move_item',
            '…',
        ]);
        $label->set_markup("<b>WebKitContextMenu</b>\n17 generated methods\n<small>$names</small>");
        return $label;
    },
);
