<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitFeatureList - Contains a set of toggle-able web engine features.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitFeatureList doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitFeatureList
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitFeatureList',
    'Contains a set of toggle-able web engine features.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get',
            'get_length',
            'ref',
            'unref',
        ]);
        $label->set_markup("<b>WebKitFeatureList</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
