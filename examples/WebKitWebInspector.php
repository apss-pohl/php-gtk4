<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitWebInspector - Access to the WebKit inspector.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitWebInspector doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitWebInspector
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitWebInspector',
    'Access to the WebKit inspector.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'attach',
            'close',
            'detach',
            'get_attached_height',
            'get_can_attach',
            'get_inspected_uri',
            'get_web_view',
            'is_attached',
            'show',
        ]);
        $label->set_markup("<b>WebKitWebInspector</b>\n9 generated methods\n<small>$names</small>");
        return $label;
    },
);
