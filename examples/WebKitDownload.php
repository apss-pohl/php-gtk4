<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitDownload - Object used to communicate with the application when downloading.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitDownload doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitDownload
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitDownload',
    'Object used to communicate with the application when downloading.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'cancel',
            'get_allow_overwrite',
            'get_destination',
            'get_elapsed_time',
            'get_estimated_progress',
            'get_received_data_length',
            'get_request',
            'get_response',
            'get_web_view',
            'set_allow_overwrite',
            'set_destination',
        ]);
        $label->set_markup("<b>WebKitDownload</b>\n11 generated methods\n<small>$names</small>");
        return $label;
    },
);
