<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitUserScript - A JavaScript snippet which can be injected in loaded pages.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitUserScript doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitUserScript
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitUserScript',
    'A JavaScript snippet which can be injected in loaded pages.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'new_for_world',
            'ref',
            'unref',
        ]);
        $label->set_markup("<b>WebKitUserScript</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
