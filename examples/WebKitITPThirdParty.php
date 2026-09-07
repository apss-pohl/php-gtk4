<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitITPThirdParty - Describes a third party origin.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitITPThirdParty doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitITPThirdParty
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitITPThirdParty',
    'Describes a third party origin.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_domain',
            'get_first_parties',
            'ref',
            'unref',
        ]);
        $label->set_markup("<b>WebKitITPThirdParty</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
