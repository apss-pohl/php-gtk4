<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkDrag - The GdkDrag object represents the source of an ongoing DND operation.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GdkDrag doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkDrag
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkDrag',
    'The GdkDrag object represents the source of an ongoing DND operation.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'begin',
            'drop_done',
            'get_actions',
            'get_content',
            'get_device',
            'get_display',
            'get_drag_surface',
            'get_formats',
            'get_selected_action',
            'get_surface',
            'set_hotspot',
        ]);
        $label->set_markup("<b>GdkDrag</b>\n11 generated methods\n<small>$names</small>");
        return $label;
    },
);
