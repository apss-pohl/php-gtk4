<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkDrop - The GdkDrop object represents the target of an ongoing DND operation.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GdkDrop doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkDrop
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkDrop',
    'The GdkDrop object represents the target of an ongoing DND operation.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'finish',
            'get_actions',
            'get_device',
            'get_display',
            'get_drag',
            'get_formats',
            'get_surface',
            'read_async',
            'read_finish',
            'read_value_async',
            'read_value_finish',
            'status',
        ]);
        $label->set_markup("<b>GdkDrop</b>\n12 generated methods\n<small>$names</small>");
        return $label;
    },
);
