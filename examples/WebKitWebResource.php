<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitWebResource - Represents a resource at the end of a URI.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitWebResource doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitWebResource
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitWebResource',
    'Represents a resource at the end of a URI.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_data',
            'get_data_finish',
            'get_response',
            'get_uri',
        ]);
        $label->set_markup("<b>WebKitWebResource</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
