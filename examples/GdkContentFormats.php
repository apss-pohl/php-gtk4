<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkContentFormats - The GdkContentFormats structure is used to advertise and negotiate the format…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GdkContentFormats doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkContentFormats
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkContentFormats',
    'The GdkContentFormats structure is used to advertise and negotiate the format…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'contain_gtype',
            'contain_mime_type',
            'get_gtypes',
            'get_mime_types',
            'match',
            'match_gtype',
            'match_mime_type',
            'new_for_gtype',
            'parse',
            'print',
            'ref',
            '…',
        ]);
        $label->set_markup("<b>GdkContentFormats</b>\n19 generated methods\n<small>$names</small>");
        return $label;
    },
);
