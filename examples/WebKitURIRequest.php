<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitURIRequest - Represents a URI request.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitURIRequest doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitURIRequest
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitURIRequest',
    'Represents a URI request.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_http_headers',
            'get_http_method',
            'get_uri',
            'set_uri',
        ]);
        $label->set_markup("<b>WebKitURIRequest</b>\n5 generated methods\n<small>$names</small>");
        return $label;
    },
);
