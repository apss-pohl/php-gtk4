<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitNetworkProxySettings - Configures network proxies.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitNetworkProxySettings doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitNetworkProxySettings
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitNetworkProxySettings',
    'Configures network proxies.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'add_proxy_for_scheme',
            'copy',
            'free',
        ]);
        $label->set_markup("<b>WebKitNetworkProxySettings</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
