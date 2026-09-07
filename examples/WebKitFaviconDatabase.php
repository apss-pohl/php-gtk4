<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitFaviconDatabase - Provides access to the icons associated with web sites.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitFaviconDatabase doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitFaviconDatabase
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitFaviconDatabase',
    'Provides access to the icons associated with web sites.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'clear',
            'get_favicon',
            'get_favicon_finish',
            'get_favicon_uri',
        ]);
        $label->set_markup("<b>WebKitFaviconDatabase</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
