<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitMemoryPressureSettings - A boxed type representing the settings for the memory pressure handler
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitMemoryPressureSettings doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitMemoryPressureSettings
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitMemoryPressureSettings',
    'A boxed type representing the settings for the memory pressure handler',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'copy',
            'free',
            'get_conservative_threshold',
            'get_kill_threshold',
            'get_memory_limit',
            'get_poll_interval',
            'get_strict_threshold',
            'set_conservative_threshold',
            'set_kill_threshold',
            'set_memory_limit',
            'set_poll_interval',
            '…',
        ]);
        $label->set_markup("<b>WebKitMemoryPressureSettings</b>\n13 generated methods\n<small>$names</small>");
        return $label;
    },
);
