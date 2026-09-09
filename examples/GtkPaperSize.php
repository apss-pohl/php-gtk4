<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPaperSize - GtkPaperSize handles paper sizes.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkPaperSize doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkPaperSize
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPaperSize',
    'GtkPaperSize handles paper sizes.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'copy',
            'free',
            'get_default',
            'get_default_bottom_margin',
            'get_default_left_margin',
            'get_default_right_margin',
            'get_default_top_margin',
            'get_display_name',
            'get_height',
            'get_name',
            'get_paper_sizes',
            '…',
        ]);
        $label->set_markup("<b>GtkPaperSize</b>\n25 generated methods\n<small>$names</small>");
        return $label;
    },
);
