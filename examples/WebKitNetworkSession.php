<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitNetworkSession - Manages network configuration.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitNetworkSession doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitNetworkSession
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitNetworkSession',
    'Manages network configuration.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'allow_tls_certificate_for_host',
            'download_uri',
            'get_cookie_manager',
            'get_default',
            'get_itp_enabled',
            'get_itp_summary',
            'get_itp_summary_finish',
            'get_persistent_credential_storage_enabled',
            'get_tls_errors_policy',
            'get_website_data_manager',
            'is_ephemeral',
            '…',
        ]);
        $label->set_markup("<b>WebKitNetworkSession</b>\n19 generated methods\n<small>$names</small>");
        return $label;
    },
);
