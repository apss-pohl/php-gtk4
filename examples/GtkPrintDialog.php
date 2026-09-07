<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPrintDialog - A GtkPrintDialog object collects the arguments that are needed to present a…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkPrintDialog doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkPrintDialog
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPrintDialog',
    'A GtkPrintDialog object collects the arguments that are needed to present a…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_accept_label',
            'get_modal',
            'get_page_setup',
            'get_print_settings',
            'get_title',
            'print',
            'print_file',
            'print_file_finish',
            'print_finish',
            'set_accept_label',
            'set_modal',
            '…',
        ]);
        $label->set_markup("<b>GtkPrintDialog</b>\n17 generated methods\n<small>$names</small>");
        return $label;
    },
);
