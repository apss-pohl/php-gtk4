<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GDBusInterfaceInfo
 * Information about a D-Bus interface.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows the class doing something and move it out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GDBusInterfaceInfo
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GDBusInterfaceInfo',
    'Information about a D-Bus interface.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'cache_build',
            'cache_release',
            'generate_xml',
            'lookup_method',
            'lookup_property',
            'lookup_signal',
            'ref',
            'unref',
        ]);
        $label->set_markup(
            "<b>GDBusInterfaceInfo</b>\n8 generated methods\n<small>$names</small>",
        );
        return $label;
    },
);
