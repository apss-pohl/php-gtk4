<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskPathMeasure - GskPathMeasure is an object that allows measurements on GskPaths such as…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskPathMeasure doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskPathMeasure
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskPathMeasure',
    'GskPathMeasure is an object that allows measurements on GskPaths such as…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_length',
            'get_path',
            'get_point',
            'get_tolerance',
            'new_with_tolerance',
            'ref',
            'unref',
        ]);
        $label->set_markup("<b>GskPathMeasure</b>\n8 generated methods\n<small>$names</small>");
        return $label;
    },
);
