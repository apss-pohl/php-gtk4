<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\GtkText - The GtkText widget is a single-line text entry widget.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows GtkText doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php GtkText
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'GtkText',
    'The GtkText widget is a single-line text entry widget.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            '__construct',
            'compute_cursor_extents',
            'get_activates_default',
            'get_attributes',
            'get_buffer',
            'get_enable_emoji_completion',
            'get_extra_menu',
            'get_input_hints',
            'get_input_purpose',
            'get_invisible_char',
            'get_max_length',
            'get_overwrite_mode',
            '…',
        ]);
        $label->set_markup("<b>GtkText</b>\n36 generated methods\n<small>$names</small>");
        return $label;
    },
);
