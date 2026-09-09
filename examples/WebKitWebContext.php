<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitWebContext - Manages aspects common to all #WebKitWebView<!-- -->s
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitWebContext doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitWebContext
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitWebContext',
    'Manages aspects common to all #WebKitWebView<!-- -->s',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'add_path_to_sandbox',
            'get_cache_model',
            'get_default',
            'get_geolocation_manager',
            'get_network_session_for_automation',
            'get_security_manager',
            'get_spell_checking_enabled',
            'get_spell_checking_languages',
            'get_time_zone_override',
            'initialize_notification_permissions',
            'is_automation_allowed',
            '…',
        ]);
        $label->set_markup("<b>WebKitWebContext</b>\n20 generated methods\n<small>$names</small>");
        return $label;
    },
);
