<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkOverlayLayoutChild - GtkLayoutChild subclass for children in a GtkOverlayLayout.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkOverlayLayoutChild doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkOverlayLayoutChild
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkOverlayLayoutChild',
    'GtkLayoutChild subclass for children in a GtkOverlayLayout.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_clip_overlay',
            'get_measure',
            'set_clip_overlay',
            'set_measure',
        ]);
        $label->set_markup("<b>GtkOverlayLayoutChild</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
