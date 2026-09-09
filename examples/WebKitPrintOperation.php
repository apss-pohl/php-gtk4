<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitPrintOperation - Controls a print operation.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitPrintOperation doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitPrintOperation
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitPrintOperation',
    'Controls a print operation.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_page_setup',
            'get_print_settings',
            'print',
            'run_dialog',
            'set_page_setup',
            'set_print_settings',
        ]);
        $label->set_markup("<b>WebKitPrintOperation</b>\n7 generated methods\n<small>$names</small>");
        return $label;
    },
);
