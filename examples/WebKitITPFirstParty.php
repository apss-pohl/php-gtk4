<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitITPFirstParty - Describes a first party origin.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitITPFirstParty doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitITPFirstParty
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitITPFirstParty',
    'Describes a first party origin.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_domain',
            'get_last_update_time',
            'get_website_data_access_allowed',
            'ref',
            'unref',
        ]);
        $label->set_markup("<b>WebKitITPFirstParty</b>\n5 generated methods\n<small>$names</small>");
        return $label;
    },
);
