<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitCookieManager - Defines how to handle cookies in a #WebKitWebContext.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitCookieManager doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitCookieManager
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitCookieManager',
    'Defines how to handle cookies in a #WebKitWebContext.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'add_cookie',
            'add_cookie_finish',
            'delete_cookie',
            'delete_cookie_finish',
            'get_accept_policy',
            'get_accept_policy_finish',
            'get_all_cookies',
            'get_all_cookies_finish',
            'get_cookies',
            'get_cookies_finish',
            'replace_cookies',
            'replace_cookies_finish',
            '…',
        ]);
        $label->set_markup("<b>WebKitCookieManager</b>\n14 generated methods\n<small>$names</small>");
        return $label;
    },
);
