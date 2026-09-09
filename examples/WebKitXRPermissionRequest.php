<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitXRPermissionRequest - A permission request for accessing virtual reality (VR) and augmented reality…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitXRPermissionRequest doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitXRPermissionRequest
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitXRPermissionRequest',
    'A permission request for accessing virtual reality (VR) and augmented reality…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_consent_optional_features',
            'get_consent_required_features',
            'get_granted_features',
            'get_optional_features_requested',
            'get_required_features_requested',
            'get_security_origin',
            'get_session_mode',
            'set_granted_optional_features',
        ]);
        $label->set_markup("<b>WebKitXRPermissionRequest</b>\n8 generated methods\n<small>$names</small>");
        return $label;
    },
);
