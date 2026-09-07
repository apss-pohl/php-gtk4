<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitColorChooserRequest - A request to open a color chooser.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitColorChooserRequest doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitColorChooserRequest
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitColorChooserRequest',
    'A request to open a color chooser.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'cancel',
            'finish',
            'get_element_rectangle',
            'get_rgba',
            'set_rgba',
        ]);
        $label->set_markup("<b>WebKitColorChooserRequest</b>\n5 generated methods\n<small>$names</small>");
        return $label;
    },
);
