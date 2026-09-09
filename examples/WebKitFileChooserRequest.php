<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitFileChooserRequest - A request to open a file chooser.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitFileChooserRequest doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitFileChooserRequest
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitFileChooserRequest',
    'A request to open a file chooser.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'cancel',
            'get_mime_types',
            'get_mime_types_filter',
            'get_select_multiple',
            'get_selected_files',
            'select_files',
        ]);
        $label->set_markup("<b>WebKitFileChooserRequest</b>\n6 generated methods\n<small>$names</small>");
        return $label;
    },
);
