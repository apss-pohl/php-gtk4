<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPrintSetup - A GtkPrintSetup is an auxiliary object for printing that allows decoupling…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkPrintSetup doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkPrintSetup
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPrintSetup',
    'A GtkPrintSetup is an auxiliary object for printing that allows decoupling…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_page_setup',
            'get_print_settings',
            'ref',
            'unref',
        ]);
        $label->set_markup("<b>GtkPrintSetup</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
