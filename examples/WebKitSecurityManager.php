<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitSecurityManager - Controls security settings in a #WebKitWebContext.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitSecurityManager doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitSecurityManager
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitSecurityManager',
    'Controls security settings in a #WebKitWebContext.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'register_uri_scheme_as_cors_enabled',
            'register_uri_scheme_as_display_isolated',
            'register_uri_scheme_as_empty_document',
            'register_uri_scheme_as_local',
            'register_uri_scheme_as_no_access',
            'register_uri_scheme_as_secure',
            'uri_scheme_is_cors_enabled',
            'uri_scheme_is_display_isolated',
            'uri_scheme_is_empty_document',
            'uri_scheme_is_local',
            'uri_scheme_is_no_access',
            'uri_scheme_is_secure',
        ]);
        $label->set_markup("<b>WebKitSecurityManager</b>\n12 generated methods\n<small>$names</small>");
        return $label;
    },
);
