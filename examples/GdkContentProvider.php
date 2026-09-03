<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkContentProvider - A GdkContentProvider is used to provide content for the clipboard or for…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GdkContentProvider doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkContentProvider
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkContentProvider',
    'A GdkContentProvider is used to provide content for the clipboard or for…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'content_changed',
            'get_value',
            'new_for_bytes',
            'new_for_value',
            'new_union',
            'ref_formats',
            'ref_storable_formats',
            'write_mime_type_async',
            'write_mime_type_finish',
        ]);
        $label->set_markup("<b>GdkContentProvider</b>\n9 generated methods\n<small>$names</small>");
        return $label;
    },
);
