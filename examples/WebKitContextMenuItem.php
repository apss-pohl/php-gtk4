<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitContextMenuItem - One item of a #WebKitContextMenu.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitContextMenuItem doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitContextMenuItem
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitContextMenuItem',
    'One item of a #WebKitContextMenu.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_gaction',
            'get_gaction_target',
            'get_stock_action',
            'get_submenu',
            'get_title',
            'is_separator',
            'new_from_gaction',
            'new_from_stock_action',
            'new_from_stock_action_with_label',
            'new_separator',
            'new_with_submenu',
            'set_submenu',
        ]);
        $label->set_markup("<b>WebKitContextMenuItem</b>\n12 generated methods\n<small>$names</small>");
        return $label;
    },
);
