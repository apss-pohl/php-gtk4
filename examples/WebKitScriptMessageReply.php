<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitScriptMessageReply - A reply for a script message received. If no reply has been sent by the user…
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitScriptMessageReply doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitScriptMessageReply
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitScriptMessageReply',
    'A reply for a script message received. If no reply has been sent by the user…',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'ref',
            'return_error_message',
            'return_value',
            'unref',
        ]);
        $label->set_markup("<b>WebKitScriptMessageReply</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
