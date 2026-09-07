<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPrintSettings - A GtkPrintSettings object represents the settings of a print dialog in a…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkPrintSettings doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkPrintSettings
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPrintSettings',
    'A GtkPrintSettings object represents the settings of a print dialog in a…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'copy',
            'foreach',
            'get',
            'get_bool',
            'get_collate',
            'get_default_source',
            'get_dither',
            'get_double',
            'get_double_with_default',
            'get_duplex',
            'get_finishings',
            '…',
        ]);
        $label->set_markup("<b>GtkPrintSettings</b>\n76 generated methods\n<small>$names</small>");
        return $label;
    },
);
