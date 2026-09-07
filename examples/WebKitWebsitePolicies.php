<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitWebsitePolicies - View specific website policies.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitWebsitePolicies doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitWebsitePolicies
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitWebsitePolicies',
    'View specific website policies.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_autoplay_policy',
        ]);
        $label->set_markup("<b>WebKitWebsitePolicies</b>\n2 generated methods\n<small>$names</small>");
        return $label;
    },
);
