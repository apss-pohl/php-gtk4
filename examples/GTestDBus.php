<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GTestDBus
 * A helper class for testing code which uses D-Bus without touching the…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows the class doing something and move it out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GTestDBus
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GTestDBus',
    'A helper class for testing code which uses D-Bus without touching the…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'add_service_dir',
            'down',
            'get_bus_address',
            'get_flags',
            'stop',
            'unset',
            'up',
        ]);
        $label->set_markup(
            "<b>GTestDBus</b>\n8 generated methods\n<small>$names</small>",
        );
        return $label;
    },
);
