<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitPermissionRequest - A permission request.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitPermissionRequest doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitPermissionRequest
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitPermissionRequest',
    'A permission request.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'allow',
            'deny',
        ]);
        $label->set_markup("<b>WebKitPermissionRequest</b>\n2 generated methods\n<small>$names</small>");
        return $label;
    },
);
