<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitResponsePolicyDecision - A policy decision for resource responses.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitResponsePolicyDecision doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitResponsePolicyDecision
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitResponsePolicyDecision',
    'A policy decision for resource responses.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'get_request',
            'get_response',
            'is_main_frame_main_resource',
            'is_mime_type_supported',
        ]);
        $label->set_markup("<b>WebKitResponsePolicyDecision</b>\n4 generated methods\n<small>$names</small>");
        return $label;
    },
);
