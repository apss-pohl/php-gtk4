<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GrapheneRect - The location and size of a rectangle region.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GrapheneRect doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GrapheneRect
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GrapheneRect',
    'The location and size of a rectangle region.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'alloc',
            'contains_point',
            'contains_rect',
            'equal',
            'expand',
            'free',
            'get_area',
            'get_bottom_left',
            'get_bottom_right',
            'get_center',
            'get_height',
            'get_top_left',
            '…',
        ]);
        $label->set_markup("<b>GrapheneRect</b>\n31 generated methods\n<small>$names</small>");
        return $label;
    },
);
