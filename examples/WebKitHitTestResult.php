<?php

declare(strict_types=1);

namespace PhpGtk4\Examples;

use Gtk4\GtkLabel;
use Gtk4\GtkWidget;
use Gtk4\GtkWindow;

/*
 * Gtk4\WebKitHitTestResult - Result of a Hit Test.
 *
 * GENERATED skeleton (gen/gir.php): names the API surface only. Replace it with a page
 * that shows WebKitHitTestResult doing something and move the class out of the 'Generated' section.
 *
 *   bin/php-gtk4 examples/demo.php WebKitHitTestResult
 */

require_once __DIR__ . '/bootstrap.php';

return Demo::page(
    'WebKitHitTestResult',
    'Result of a Hit Test.',
    function (GtkWindow $win): GtkWidget {
        $label = new GtkLabel();
        $names = implode(', ', [
            'context_is_editable',
            'context_is_image',
            'context_is_link',
            'context_is_media',
            'context_is_scrollbar',
            'context_is_selection',
            'get_context',
            'get_image_uri',
            'get_link_label',
            'get_link_title',
            'get_link_uri',
            'get_media_uri',
        ]);
        $label->set_markup("<b>WebKitHitTestResult</b>\n12 generated methods\n<small>$names</small>");
        return $label;
    },
);
