<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitScriptDialog - Carries details to be shown in user-facing dialogs.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitScriptDialog doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitScriptDialog
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitScriptDialog',
    'Carries details to be shown in user-facing dialogs.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'close',
            'confirm_set_confirmed',
            'get_dialog_type',
            'get_message',
            'prompt_get_default_text',
            'prompt_set_text',
            'ref',
            'unref',
        ]);
        $label->set_markup("<b>WebKitScriptDialog</b>\n8 generated methods\n<small>$names</small>");
        return $label;
    },
);
