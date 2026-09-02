<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkClipboard - The GdkClipboard object represents data shared between applications or inside…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GdkClipboard doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkClipboard
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkClipboard',
    'The GdkClipboard object represents data shared between applications or inside…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_content',
            'get_display',
            'get_formats',
            'is_local',
            'read_async',
            'read_finish',
            'read_text_async',
            'read_text_finish',
            'read_texture_async',
            'read_texture_finish',
            'read_value_async',
            'read_value_finish',
            '…',
        ]);
        $label->set_markup("<b>GdkClipboard</b>\n19 generated methods\n<small>$names</small>");
        return $label;
    },
);
