<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitBackForwardListItem - One item of the #WebKitBackForwardList.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitBackForwardListItem doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitBackForwardListItem
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitBackForwardListItem',
    'One item of the #WebKitBackForwardList.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_original_uri',
            'get_title',
            'get_uri',
        ]);
        $label->set_markup("<b>WebKitBackForwardListItem</b>\n3 generated methods\n<small>$names</small>");
        return $label;
    },
);
