<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GdkPixbufAnimationIter - An opaque object representing an iterator which points to a certain position…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GdkPixbufAnimationIter doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GdkPixbufAnimationIter
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GdkPixbufAnimationIter',
    'An opaque object representing an iterator which points to a certain position…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'advance',
            'get_delay_time',
            'get_pixbuf',
            'on_currently_loading_frame',
        ]);
        $label->set_markup("<b>GdkPixbufAnimationIter</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
