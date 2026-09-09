<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitWebsiteData - Data stored locally by a web site.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitWebsiteData doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitWebsiteData
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitWebsiteData',
    'Data stored locally by a web site.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_name',
            'get_size',
            'get_types',
            'ref',
            'unref',
        ]);
        $label->set_markup("<b>WebKitWebsiteData</b>\n5 generated methods\n<small>$names</small>");
        return $label;
    },
);
