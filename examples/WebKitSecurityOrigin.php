<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitSecurityOrigin - A security boundary for websites.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitSecurityOrigin doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitSecurityOrigin
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitSecurityOrigin',
    'A security boundary for websites.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_host',
            'get_port',
            'get_protocol',
            'new_for_uri',
            'ref',
            'to_string',
            'unref',
        ]);
        $label->set_markup("<b>WebKitSecurityOrigin</b>\n8 generated methods\n<small>$names</small>");
        return $label;
    },
);
