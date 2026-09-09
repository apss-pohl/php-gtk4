<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkPageSetup - A GtkPageSetup object stores the page size, orientation and margins.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkPageSetup doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkPageSetup
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkPageSetup',
    'A GtkPageSetup object stores the page size, orientation and margins.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'copy',
            'get_bottom_margin',
            'get_left_margin',
            'get_orientation',
            'get_page_height',
            'get_page_width',
            'get_paper_height',
            'get_paper_size',
            'get_paper_width',
            'get_right_margin',
            'get_top_margin',
            '…',
        ]);
        $label->set_markup("<b>GtkPageSetup</b>\n27 generated methods\n<small>$names</small>");
        return $label;
    },
);
