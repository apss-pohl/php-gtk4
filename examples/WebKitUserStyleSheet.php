<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitUserStyleSheet - A CSS style sheet which can be injected in loaded pages.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitUserStyleSheet doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitUserStyleSheet
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitUserStyleSheet',
    'A CSS style sheet which can be injected in loaded pages.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'new_for_world',
            'ref',
            'unref',
        ]);
        $label->set_markup("<b>WebKitUserStyleSheet</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
