<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitWebsiteDataAccessPermissionRequest
 * A permission request for accessing website data from third-party domains.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows the class doing something and move it out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitWebsiteDataAccessPermissionRequest
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitWebsiteDataAccessPermissionRequest',
    'A permission request for accessing website data from third-party domains.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_current_domain',
            'get_requesting_domain',
        ]);
        $label->set_markup(
            "<b>WebKitWebsiteDataAccessPermissionRequest</b>\n2 generated methods\n<small>$names</small>",
        );
        return $label;
    },
);
