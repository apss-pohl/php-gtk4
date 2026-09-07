<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitURIResponse - Represents an URI response.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitURIResponse doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitURIResponse
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitURIResponse',
    'Represents an URI response.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_content_length',
            'get_http_headers',
            'get_mime_type',
            'get_status_code',
            'get_suggested_filename',
            'get_uri',
        ]);
        $label->set_markup("<b>WebKitURIResponse</b>\n6 generated methods\n<small>$names</small>");
        return $label;
    },
);
