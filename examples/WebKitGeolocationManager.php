<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitGeolocationManager - Geolocation manager.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitGeolocationManager doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitGeolocationManager
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitGeolocationManager',
    'Geolocation manager.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'failed',
            'get_enable_high_accuracy',
            'update_position',
        ]);
        $label->set_markup("<b>WebKitGeolocationManager</b>\n3 generated methods\n<small>$names</small>");
        return $label;
    },
);
