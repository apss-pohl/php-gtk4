<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitEditorState - Web editor state.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitEditorState doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitEditorState
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitEditorState',
    'Web editor state.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_typing_attributes',
            'is_copy_available',
            'is_cut_available',
            'is_paste_available',
            'is_redo_available',
            'is_undo_available',
        ]);
        $label->set_markup("<b>WebKitEditorState</b>\n6 generated methods\n<small>$names</small>");
        return $label;
    },
);
