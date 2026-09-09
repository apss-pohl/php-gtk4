<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitInputMethodContext - Base class for input method contexts.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitInputMethodContext doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitInputMethodContext
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitInputMethodContext',
    'Base class for input method contexts.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'filter_key_event',
            'get_input_hints',
            'get_input_purpose',
            'get_preedit',
            'notify_cursor_area',
            'notify_focus_in',
            'notify_focus_out',
            'notify_surrounding',
            'reset',
            'set_enable_preedit',
            'set_input_hints',
            'set_input_purpose',
        ]);
        $label->set_markup("<b>WebKitInputMethodContext</b>\n12 generated methods\n<small>$names</small>");
        return $label;
    },
);
