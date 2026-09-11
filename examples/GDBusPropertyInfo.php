<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GDBusPropertyInfo
 * Information about a D-Bus property on a D-Bus interface.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows the class doing something and move it out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GDBusPropertyInfo
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GDBusPropertyInfo',
    'Information about a D-Bus property on a D-Bus interface.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'ref',
            'unref',
        ]);
        $label->set_markup(
            "<b>GDBusPropertyInfo</b>\n2 generated methods\n<small>$names</small>",
        );
        return $label;
    },
);
