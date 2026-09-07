<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitPointerLockPermissionRequest - A permission request for locking the pointer.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitPointerLockPermissionRequest doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitPointerLockPermissionRequest
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitPointerLockPermissionRequest',
    'A permission request for locking the pointer.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
        ]);
        $label->set_markup("<b>WebKitPointerLockPermissionRequest</b>\n0 generated methods\n<small>$names</small>");
        return $label;
    },
);
