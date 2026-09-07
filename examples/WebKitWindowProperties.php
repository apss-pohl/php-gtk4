<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitWindowProperties - Window properties of a #WebKitWebView.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitWindowProperties doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitWindowProperties
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitWindowProperties',
    'Window properties of a #WebKitWebView.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_fullscreen',
            'get_geometry',
            'get_locationbar_visible',
            'get_menubar_visible',
            'get_resizable',
            'get_scrollbars_visible',
            'get_statusbar_visible',
            'get_toolbar_visible',
        ]);
        $label->set_markup("<b>WebKitWindowProperties</b>\n8 generated methods\n<small>$names</small>");
        return $label;
    },
);
