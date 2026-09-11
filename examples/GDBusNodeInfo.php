<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GDBusNodeInfo
 * Information about nodes in a remote object hierarchy.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows the class doing something and move it out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GDBusNodeInfo
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GDBusNodeInfo',
    'Information about nodes in a remote object hierarchy.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'generate_xml',
            'lookup_interface',
            'new_for_xml',
            'ref',
            'unref',
        ]);
        $label->set_markup(
            "<b>GDBusNodeInfo</b>\n5 generated methods\n<small>$names</small>",
        );
        return $label;
    },
);
