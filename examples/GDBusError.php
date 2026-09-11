<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GDBusError;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GDBusError
 * Error codes for the %G_DBUS_ERROR error domain.
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the enum's cases. Replace it
 * with a page that shows the enum in action and move the class out of the
 * 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GDBusError
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GDBusError',
    'Error codes for the %G_DBUS_ERROR error domain.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $rows = array_map(
            static fn(\BackedEnum $c): string => sprintf('%s = %d', $c->name, $c->value),
            GDBusError::cases(),
        );
        $label->set_markup("<b>GDBusError</b>\n" . implode("\n", $rows));
        return $label;
    },
);
