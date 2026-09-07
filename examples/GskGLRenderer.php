<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GskGLRenderer - the GskGLRenderer class
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GskGLRenderer doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GskGLRenderer
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GskGLRenderer',
    'the GskGLRenderer class',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
        ]);
        $label->set_markup("<b>GskGLRenderer</b>\n1 generated methods\n<small>$names</small>");
        return $label;
    },
);
