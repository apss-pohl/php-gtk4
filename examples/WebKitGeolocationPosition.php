<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitGeolocationPosition - An opaque struct to provide position updates to a #WebKitGeolocationManager.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitGeolocationPosition doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitGeolocationPosition
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitGeolocationPosition',
    'An opaque struct to provide position updates to a #WebKitGeolocationManager.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'copy',
            'free',
            'set_altitude',
            'set_altitude_accuracy',
            'set_heading',
            'set_speed',
            'set_timestamp',
        ]);
        $label->set_markup("<b>WebKitGeolocationPosition</b>\n8 generated methods\n<small>$names</small>");
        return $label;
    },
);
