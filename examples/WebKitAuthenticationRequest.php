<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitAuthenticationRequest - Represents an authentication request.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitAuthenticationRequest doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitAuthenticationRequest
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitAuthenticationRequest',
    'Represents an authentication request.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'authenticate',
            'can_save_credentials',
            'cancel',
            'get_certificate_pin_flags',
            'get_host',
            'get_port',
            'get_proposed_credential',
            'get_realm',
            'get_scheme',
            'get_security_origin',
            'is_for_proxy',
            'is_retry',
            '…',
        ]);
        $label->set_markup("<b>WebKitAuthenticationRequest</b>\n14 generated methods\n<small>$names</small>");
        return $label;
    },
);
