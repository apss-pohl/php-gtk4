<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitNotification - Holds information about a notification that should be shown to the user.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitNotification doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitNotification
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitNotification',
    'Holds information about a notification that should be shown to the user.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'clicked',
            'close',
            'get_body',
            'get_id',
            'get_tag',
            'get_title',
        ]);
        $label->set_markup("<b>WebKitNotification</b>\n6 generated methods\n<small>$names</small>");
        return $label;
    },
);
