<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GraphenePoint - A point with two coordinates.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GraphenePoint doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GraphenePoint
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GraphenePoint',
    'A point with two coordinates.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'alloc',
            'distance',
            'equal',
            'free',
            'init',
            'init_from_point',
            'init_from_vec2',
            'interpolate',
            'near',
            'to_vec2',
            'zero',
        ]);
        $label->set_markup("<b>GraphenePoint</b>\n11 generated methods\n<small>$names</small>");
        return $label;
    },
);
