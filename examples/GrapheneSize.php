<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GrapheneSize - A size.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GrapheneSize doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GrapheneSize
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GrapheneSize',
    'A size.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'alloc',
            'equal',
            'free',
            'init',
            'init_from_size',
            'interpolate',
            'scale',
            'zero',
        ]);
        $label->set_markup("<b>GrapheneSize</b>\n8 generated methods\n<small>$names</small>");
        return $label;
    },
);
