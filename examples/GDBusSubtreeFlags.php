<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GDBusSubtreeFlags;
use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GDBusSubtreeFlags
 * Flags passed to g_dbus_connection_register_subtree().
 *
 * GENERATED skeleton (gen/gir.php): lists every one of the bitfield's constants. Replace it
 * with a page that shows the bitfield in action and move the class out of the
 * 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GDBusSubtreeFlags
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GDBusSubtreeFlags',
    'Flags passed to g_dbus_connection_register_subtree().',
    function (GtkWindow $win): GtkWidget {
        $constants = array_filter(new \ReflectionClass(GDBusSubtreeFlags::class)->getConstants(), 'is_int');
        $label = new GtkLabel();
        $rows = array_map(
            static fn(string $k, int $v): string => sprintf('%s = %d', $k, $v),
            array_keys($constants),
            $constants,
        );
        $label->set_markup("<b>GDBusSubtreeFlags</b>\n" . implode("\n", $rows));
        return $label;
    },
);
