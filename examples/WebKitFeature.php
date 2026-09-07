<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitFeature - Describes a web engine feature that may be toggled at runtime.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitFeature doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitFeature
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitFeature',
    'Describes a web engine feature that may be toggled at runtime.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_category',
            'get_default_value',
            'get_details',
            'get_identifier',
            'get_name',
            'get_status',
            'ref',
            'unref',
        ]);
        $label->set_markup("<b>WebKitFeature</b>\n8 generated methods\n<small>$names</small>");
        return $label;
    },
);
