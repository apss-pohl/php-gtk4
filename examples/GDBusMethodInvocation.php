<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GDBusMethodInvocation
 * Instances of the GDBusMethodInvocation class are used when handling D-Bus…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows the class doing something and move it out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GDBusMethodInvocation
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GDBusMethodInvocation',
    'Instances of the GDBusMethodInvocation class are used when handling D-Bus…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_connection',
            'get_interface_name',
            'get_message',
            'get_method_info',
            'get_method_name',
            'get_object_path',
            'get_parameters',
            'get_property_info',
            'get_sender',
            'get_user_data',
            'return_dbus_error',
            'return_error_literal',
            '…',
        ]);
        $label->set_markup(
            "<b>GDBusMethodInvocation</b>\n17 generated methods\n<small>$names</small>",
        );
        return $label;
    },
);
