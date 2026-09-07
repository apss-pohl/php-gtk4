<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitUserContentFilterStore - Handles storage of user content filters on disk.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitUserContentFilterStore doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitUserContentFilterStore
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitUserContentFilterStore',
    'Handles storage of user content filters on disk.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'fetch_identifiers',
            'fetch_identifiers_finish',
            'get_path',
            'load',
            'load_finish',
            'remove',
            'remove_finish',
            'save',
            'save_finish',
            'save_from_file',
            'save_from_file_finish',
        ]);
        $label->set_markup("<b>WebKitUserContentFilterStore</b>\n12 generated methods\n<small>$names</small>");
        return $label;
    },
);
