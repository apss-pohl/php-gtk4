<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\JSCException - JSCException represents a JavaScript exception.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows JSCException doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php JSCException
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'JSCException',
    'JSCException represents a JavaScript exception.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'get_backtrace_string',
            'get_column_number',
            'get_line_number',
            'get_message',
            'get_name',
            'get_source_uri',
            'new_vprintf',
            'new_with_name',
            'new_with_name_vprintf',
            'report',
            'to_string',
        ]);
        $label->set_markup("<b>JSCException</b>\n12 generated methods\n<small>$names</small>");
        return $label;
    },
);
