<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitInputMethodUnderline - Range of text in an preedit string to be shown underlined.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitInputMethodUnderline doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitInputMethodUnderline
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitInputMethodUnderline',
    'Range of text in an preedit string to be shown underlined.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'copy',
            'free',
            'set_color',
        ]);
        $label->set_markup("<b>WebKitInputMethodUnderline</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
