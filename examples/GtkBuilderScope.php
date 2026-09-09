<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkBuilderScope - GtkBuilderScope is an interface to provide language binding support to…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkBuilderScope doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkBuilderScope
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkBuilderScope',
    'GtkBuilderScope is an interface to provide language binding support to…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
        ]);
        $label->set_markup("<b>GtkBuilderScope</b>\n0 generated methods\n<small>$names</small>");
        return $label;
    },
);
