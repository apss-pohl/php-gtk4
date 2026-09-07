<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitBackForwardList - List of visited pages.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitBackForwardList doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitBackForwardList
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitBackForwardList',
    'List of visited pages.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_back_item',
            'get_back_list',
            'get_back_list_with_limit',
            'get_current_item',
            'get_forward_item',
            'get_forward_list',
            'get_forward_list_with_limit',
            'get_length',
            'get_nth_item',
        ]);
        $label->set_markup("<b>WebKitBackForwardList</b>\n9 generated methods\n<small>$names</small>");
        return $label;
    },
);
