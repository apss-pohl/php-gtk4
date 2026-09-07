<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitApplicationInfo - Information about an application running in automation mode.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitApplicationInfo doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitApplicationInfo
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitApplicationInfo',
    'Information about an application running in automation mode.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_name',
            'get_version',
            'ref',
            'set_name',
            'set_version',
            'unref',
        ]);
        $label->set_markup("<b>WebKitApplicationInfo</b>\n7 generated methods\n<small>$names</small>");
        return $label;
    },
);
