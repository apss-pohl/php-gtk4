<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitNotificationPermissionRequest - A permission request for displaying web notifications.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitNotificationPermissionRequest doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitNotificationPermissionRequest
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitNotificationPermissionRequest',
    'A permission request for displaying web notifications.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
        ]);
        $label->set_markup("<b>WebKitNotificationPermissionRequest</b>\n0 generated methods\n<small>$names</small>");
        return $label;
    },
);
