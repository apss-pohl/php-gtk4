<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitPermissionStateQuery - This query represents a user\'s choice to allow or deny access to "powerful…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitPermissionStateQuery doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitPermissionStateQuery
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitPermissionStateQuery',
    'This query represents a user\'s choice to allow or deny access to "powerful…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'finish',
            'get_name',
            'get_security_origin',
            'ref',
            'unref',
        ]);
        $label->set_markup("<b>WebKitPermissionStateQuery</b>\n5 generated methods\n<small>$names</small>");
        return $label;
    },
);
