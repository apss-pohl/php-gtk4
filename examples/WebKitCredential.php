<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitCredential - Groups information used for user authentication.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitCredential doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitCredential
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitCredential',
    'Groups information used for user authentication.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'copy',
            'free',
            'get_certificate',
            'get_password',
            'get_persistence',
            'get_username',
            'has_password',
            'new_for_certificate',
            'new_for_certificate_pin',
        ]);
        $label->set_markup("<b>WebKitCredential</b>\n10 generated methods\n<small>$names</small>");
        return $label;
    },
);
