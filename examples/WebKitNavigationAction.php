<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitNavigationAction - Provides details about interaction resulting in a resource load.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitNavigationAction doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitNavigationAction
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitNavigationAction',
    'Provides details about interaction resulting in a resource load.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'copy',
            'free',
            'get_frame_name',
            'get_modifiers',
            'get_mouse_button',
            'get_navigation_type',
            'get_request',
            'is_redirect',
            'is_user_gesture',
        ]);
        $label->set_markup("<b>WebKitNavigationAction</b>\n9 generated methods\n<small>$names</small>");
        return $label;
    },
);
